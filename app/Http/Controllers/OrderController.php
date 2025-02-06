<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Order;
use App\Models\Product;
use App\Models\Retur;
use App\Models\Review;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request) {
        $filter = (object) [
            'q' => $request->get('search', ''),
            'field' => $request->get('field', 'created_at'),
            'order' => $request->get('order') === 'oldest' ? 'asc' : 'desc',
        ];

        $orders = Order::with('customer')
            ->where(function ($query) use ($filter) {
                $query->where('code', 'LIKE', '%' . $filter->q . '%')
                    ->orWhereHas('customer', function ($q) use ($filter) {
                        $q->where('email', 'LIKE', '%' . $filter->q . '%')
                            ->orWhere('name', 'LIKE', '%' . $filter->q . '%');
                    });
            })
            ->orderBy($filter->field, $filter->order)
            ->get();

        return view('orders.index', compact('orders', 'filter'));
    }

    public function waiting(Request $request) {
        $filter = (object) [
            'q' => $request->get('search', ''),
            'field' => $request->get('field', 'created_at'),
            'order' => $request->get('order') === 'newest' ? 'desc' : 'asc',
        ];

        $orders = Order::with('customer')
            ->where(function ($query) use ($filter) {
                $query->where('code', 'LIKE', '%' . $filter->q . '%')
                    ->orWhereHas('customer', function ($q) use ($filter) {
                        $q->where('email', 'LIKE', '%' . $filter->q . '%')
                            ->orWhere('name', 'LIKE', '%' . $filter->q . '%');
                    });
            })
            ->where('status', '1')
            ->orderBy($filter->field, $filter->order)
            ->get();

        return view('orders.waiting', compact('orders', 'filter'));
    }
    
    public function my(Request $request) {
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];
        $orders = Order::where('user_id', auth()->user()->id)
                        ->where('code', 'LIKE', '%'.$filter->q.'%')
                        ->orderBy($filter->field, $filter->order)
                        ->get();
        return view('orders.my', compact('orders', 'filter'));
    }

    public function add(){
        $customers = User::role('pelanggan')->get();
        return view('orders.add', compact('customers'));
    }

    public function checkout(Request $request){
        $request->validate([
            'customer_id' => 'nullable|exists:users,id',
        ]);
        $cart = session()->get('cart', []);
        foreach ($cart as $key => $value) {
            $product = Product::find($key);

            if (!$product) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Produk dengan ID ' . $key . ' tidak ditemukan.');
            }

            if ($product->stock->stok < $value['qty']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Stok produk ' . $product->nama . ' tersisa ' . $product->stock->stok . ' unit. Silahkan update jumlah barang yang ingin dibeli.');
            }
        }
        $totalHarga = $this->calcTotalCartPrice($cart);
        try {
            $order = new Order();
            $order->user_id = $request->get('customer_id') ?? auth()->user()->id;
            $order->produk = json_encode($cart);
            $order->status = '0';
            $order->total_harga = $totalHarga;
            $order->save();

            // Hapus session cart
            session()->forget('cart');

            return redirect()->route('order.payment', $order->id)->with('success', 'Berhasil membuat pesanan silahkan bayar ke Rekening tertera, dan Upload Bukti Bayar untuk segera di proses');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat pesanan..., Error: '.$e->getMessage());
        }
    }

    public function calcTotalCartPrice(array $cart)
    {
        $ppnRate = setting('site.ppn');
        $total = 0;

        foreach ($cart as $item) {
            // Ambil data produk berdasarkan id
            $product = Product::find($item['id']);

            if ($product) {
                $subtotal = $product->harga * $item['qty'];
                $total += $subtotal;
            }
        }

        // Hitung total termasuk PPN
        $totalWithPPN = $total + ($total * $ppnRate / 100);

        return $totalWithPPN;
    }

    public function payment($id){
        $order = Order::find($id);
        if ($this->checkExpired($order)) {
            $remainingTime = 0;
        } else {
            $now = now();
            $orderExpiry = $order->created_at->addHours(12);
            $remainingTime = $orderExpiry->diffInSeconds($now);
        }
        $banks = Bank::orderBy('nama_bank', 'ASC')->get();
        return view('orders.payment', compact('order', 'remainingTime', 'banks'));
    }

    public function paid($id, Request $request){
        $order = Order::find($id);
        $request->validate([
            'bukti_pembayaran' =>'required|file|mimes:jpeg,png,jpg,webp,pdf,docx,doc,pptx|max:10240',
        ]);
        
        try {
            $products = json_decode($order->produk);
            foreach ($products as $key => $value) {
                $product = Product::find($key);

                if (!$product) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Produk dengan ID ' . $key . ' tidak ditemukan.');
                }

                if ($product->stock->stok < $value->qty) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Stok produk ' . $product->nama . ' tersisa ' . $product->stock->stok . ' unit. Silahkan update jumlah barang yang ingin dibeli.');
                }
            }

            if($order->status != '0' && $order->status != '1' && !$this->checkExpired($order)){
                return redirect()->back()->withInput()->with('error', 'Gagal upload pembayaran pesanan, Status tidak valid');
            }

            if ($request->hasFile('bukti_pembayaran')) {
                if ($order->bukti_pembayaran && file_exists(storage_path('app/public/' . $order->bukti_pembayaran))) {
                    unlink(storage_path('app/public/' . $order->bukti_pembayaran));
                }

                $attachmentPath = $request->file('bukti_pembayaran')->store('orders', 'public');
                $order->bukti_pembayaran = $attachmentPath;
            }
            $order->status = '1';
            $order->save();
            return redirect()->back()->with('success', 'Berhasil mengupload bukti pembayaran untuk Kode Pesanan '.$order->code);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupload pembayaran, Error: '.$e->getMessage());
        }
    }

    public function approve($id){
        try {
            $order = Order::find($id);
            if($order->status != '1'){
                return redirect()->back()->withInput()->with('error', 'Gagal menyetujui pesanan, Status tidak valid');
            }
            $products = json_decode($order->produk);
            foreach($products as $key => $value){
                $stock = Stock::where('produk_id', $key)->first();
                $stock->stok = $stock->stok - $value->qty;
                $stock->save();
            }
            $order->status = '2';
            $order->save();
            return redirect()->back()->with('success', 'Menyetujui Pesanan dengan Kode Pesanan '.$order->code);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyetujui pesanan, Error: '.$e->getMessage());
        }
    }

    public function rejected($id){
        try {
            $order = Order::find($id);
            if($order->status != '1'){
                return redirect()->back()->withInput()->with('error', 'Gagal menolak pesanan, Status tidak valid');
            }
            $order->status = '3';
            $order->save();
            return redirect()->back()->with('success', 'Menolak Pesanan dengan Kode Pesanan '.$order->code);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menolak pesanan, Error: '.$e->getMessage());
        }
    }

    public function reorder($id){
        try {
            $order = Order::find($id);
            if($order->status != '3'){
                return redirect()->back()->withInput()->with('error', 'Gagal membuat ulang pesanan, Status tidak valid');
            }
            $order->status = '0';
            $order->created_at = Carbon::now();
            $order->updated_at = Carbon::now();
            $order->save();
            return redirect()->back()->with('success', 'Membuat Ulang Pesanan dengan Kode Pesanan '.$order->code);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat ulang pesanan, Error: '.$e->getMessage());
        }
    }

    private function checkExpired($order){
        if( $order->status == '0' ){
            if($order->created_at->addHours(12) < now()){
                $order->status = '3';
                $order->save();
                return true;
            }
        }
        return false;
    }

    public function destroy($id){
        try {
            $order = Order::find($id);
            if($order->status != '3' && $order->status != '0'){
                return redirect()->back()->withInput()->with('error', 'Gagal menghapus pesanan, Hanya pesanan yang Reject / Invalid yang bisa dihapus');
            }
            $order->delete();
            return redirect()->back()->with('success', 'Menghapus Pesanan dengan Kode Pesanan '.$order->code);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus pesanan, Error: '.$e->getMessage());
        }
    }

    public function review($id, Request $request){
        try {
            $order = Order::find($id);
            if($order->status != '2' || $order->retur || auth()->user()->getRoleNames()[0] != 'pelanggan'){
                return redirect()->back()->withInput()->with('error', 'Pesanan '.$order->code.' status tidak valid atau dalam pengembalian, pesanan tidak bisa di ulas');
            }
            // Filter data rating dari request
            $ratings = collect($request->all())
                ->filter(function ($value, $key) {
                    return str_starts_with($key, 'rating-');
                });

            // Loop untuk validasi setiap rating
            $errors = [];
            foreach ($ratings as $key => $rating) {
                $productId = str_replace('rating-', '', $key);

                // Validasi apakah produk dengan ID ini ada
                if (!Product::find($productId)) {
                    $errors[$key] = "Produk dengan ID $productId tidak ditemukan.";
                    continue;
                }

                // Validasi rating apakah antara 1 dan 5
                if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
                    $errors[$key] = "Rating untuk produk $productId harus antara 1 dan 5.";
                }
            }

            // Validasi keterangan (opsional)
            $validator = Validator::make($request->only('keterangan'), [
                'keterangan' => 'nullable|string|max:500',
            ]);

            if ($validator->fails() || !empty($errors)) {
                throw new Exception('Validasi gagal.', 422);
            }

            if(!Review::where('pesanan_id', $id)->first()){
                // Simpan ulasan ke database
                foreach ($ratings as $key => $rating) {
                    $productId = str_replace('rating-', '', $key);
                    Review::create([
                        'pesanan_id' => $id,
                        'produk_id' => $productId,
                        'user_id' => auth()->id(),
                        'rating' => $rating,
                        'keterangan' => $request->input('keterangan'),
                    ]);
                }
            }else{
                return redirect()->back()->with('error', 'Pesanan ini sudah diulas');
            }

            $order->status = '4'; //Selesai
            $order->save();

            return redirect()->back()->with('success', 'Ulasan berhasil disimpan.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function retur($id, Request $request){
        $request->validate([
            'keterangan' => 'required|string|min:4',
        ]);
        $order = Order::find($id);
        if($order->reviews->count() > 0 || $order->status != '2' || $order->retur){
            return redirect()->back()->withInput()->with('error', 'Pesanan '.$order->code.' status tidak valid atau telah di konfirmasi dan ulas atau telah di retur, pesanan tidak bisa di kembalikan');
        }
        try {
            $retur = new Retur();
            $retur->pesanan_id = $id;
            $retur->user_id = auth()->user()->id;
            $retur->keterangan = $request->get('keterangan');
            $retur->save();

            $order->status = '5'; //Pengembalian
            $order->save();

            return redirect()->back()->with('success', 'Berhasil mengajukan Pengembalian pesanan dengan kode '.$order->code);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengajukan Pengembalian pesanan, Error: '.$e->getMessage());
        }
    }
}
