<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientPic;
use App\Models\Product;
use App\Models\RequestOrder;
use App\Models\RequestOrderInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class RequestOrderController extends Controller
{
    public function index(Request $request) {
        $filter = (object) [
            'q' => $request->get('search', ''),
            'field' => $request->get('field', 'code'),
            'order' => $request->get('order') === 'oldest' ? 'asc' : 'desc',
        ];

        $reqorders = RequestOrder::where('code', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)
                                ->get();

        return view('request_orders.index', compact('reqorders', 'filter'));
    }

    public function add(){
        $clients = Client::orderBy('name', 'ASC')->get();
        $pics = ClientPic::orderBy('name', 'ASC')->get();
        return view('request_orders.add', compact('clients', 'pics'));
    }

    public function store(Request $request){
        $request->validate([
            'attachment' => 'nullable|file|mimes:webp,png,jpg,jpeg,jfif,pdf,doc,docx,pptx,ppt|max:10240',
            'client_id' => 'required|string|exists:osano.clients,id',
            'client_pic_id' => 'required|string|exists:osano.client_pics,id',
            'date' => 'required|date',
            'no_refrence' => 'required|string|min:4',
            'description' => 'nullable|string|min:4',
        ]);
        try {
            $reqorder = new RequestOrder();
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('request-orders', 'public');
                $reqorder->attachment = $attachmentPath;
            }
            $reqorder->date = $request->get('date');
            $reqorder->client_id = $request->get('client_id');
            $reqorder->client_pic_id = $request->get('client_pic_id');
            $reqorder->no_refrence = $request->get('no_refrence');
            $reqorder->description = $request->get('description');
            
            $reqorder->save();

            return redirect()->route('request-order.setting', ['id' => $reqorder->id])->with('success', 'Permintaan Client (Request Order) '.$reqorder->name.' berhasil ditambahkan, sekarang tentukan produknya.')->with('redirect_hash', 'produk');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Permintaan Client (Request Order), Error: '.$e->getMessage())->with('redirect_hash', 'produk');
        }
    }

    public function setting($id, Request $request){
        $reqorder = RequestOrder::find($id);
        if($reqorder->status == 0 && $reqorder->purchaseOrder){
            $reqorder->status == 1;
            $reqorder->save();
        }

        $clients = Client::orderBy('name', 'ASC')->get();
        $pics = ClientPic::orderBy('name', 'ASC')->get();
        $filter = (object) [
            'q' => $request->get('search') ?? '',
        ];

        $products = Product::where('name', 'LIKE', '%'.$filter->q.'%')
                    ->orWhere('description', 'LIKE', '%'.$filter->q.'%')
                    ->orderBy('name', 'asc')->paginate((int) setting('site.product-limit') ?? 6);
        
        if ($request->filled('hashProduct')) {
            session(['redirect_hash' => 'produk']);
        }

        if(count(session()->get('cart_'.$id, [])) == 0){
            $this->refetch($id, new Request());
        }

        return view('request_orders.setting', compact('clients', 'reqorder', 'pics', 'filter', 'products'));
    }

    public function update($id, Request $request){
        $request->validate([
            'attachment' => 'nullable|file|mimes:webp,png,jpg,jpeg,jfif,pdf,doc,docx,pptx,ppt|max:10240',
            'client_id' => 'required|string|exists:osano.clients,id',
            'client_pic_id' => 'required|string|exists:osano.client_pics,id',
            'date' => 'required|date',
            'no_refrence' => 'required|string|min:4',
            'description' => 'nullable|string|min:4',
        ]);
        try {
            $reqorder = RequestOrder::find($id);
            if($reqorder->status != 0) return redirect()->back()->with('error', 'Permintaan telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'data');

            if ($request->hasFile('attachment')) {
                if ($reqorder->attachment && file_exists(storage_path('app/public/' . $reqorder->attachment))) {
                    unlink(storage_path('app/public/' . $reqorder->attachment));
                }
                $attachmentPath = $request->file('attachment')->store('request-orders', 'public');
                $reqorder->attachment = $attachmentPath;
            }
            $reqorder->date = $request->get('date');
            $reqorder->client_id = $request->get('client_id');
            $reqorder->client_pic_id = $request->get('client_pic_id');
            $reqorder->no_refrence = $request->get('no_refrence');
            $reqorder->description = $request->get('description');
            
            $reqorder->save();

            return redirect()->route('request-order.setting', ['id' => $reqorder->id])->with('success', 'Permintaan Client (Request Order) '.$reqorder->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Permintaan Client (Request Order), Error: '.$e->getMessage());
        }
    }

    public function addCart($id, Request $request)
    {
        $reqorder = RequestOrder::find($id);
        if($reqorder->status != 0) return redirect()->back()->with('error', 'Permintaan telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'produk');

        $request->validate([
            'product_id' => 'required|exists:osano.products,id',
        ]);

        $cart = session()->get("cart_".$id, []);

        if (isset($cart[$request->get('product_id')])) {
            // Jika produk sudah ada di cart, tambahkan jumlahnya
            $cart[$request->get('product_id')]['qty'] += $request->get('qty', 1);
        } else {
            // Jika produk belum ada di cart, tambahkan dengan qty default 1
            $cart[$request->get('product_id')] = [
                'id' => $request->get('product_id'),
                'qty' => 1,
                'price_sale' => 0,
            ];
        }

        session()->put('cart_'.$id, $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang')->with('redirect_hash', 'produk');
    }

    public function removeCart($id, Request $request)
    {
        $reqorder = RequestOrder::find($id);
        if($reqorder->status != 0) return redirect()->back()->with('error', 'Permintaan telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'produk');
            
        $request->validate([
            'product_id' => 'required|exists:osano.products,id',
        ]);

        $cart = session()->get("cart_".$id, []);

        if (isset($cart[$request->get('product_id')])) {
            unset($cart[$request->get('product_id')]);
            session()->put("cart_".$id, $cart);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang',
            ], 200);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang')->with('redirect_hash', 'produk');
    }

    // Refill Cart Session by Request Order Product
    public function refetch($id, Request $request){
        $reqorder = RequestOrder::find($id);
        
        session()->forget('cart_'.$id);
        $cart = [];
        if($reqorder->products){
            foreach ($reqorder->products as $reqproduct) {
                $cart[$reqproduct->product_id] = [
                    'id' => $reqproduct->product_id,
                    'qty' => $reqproduct->qty,
                    'price_sale' => $reqproduct->price_sale,
                ];
            }
        }
        session()->put('cart_'.$id, $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mereset Ulang dengan data Produk Permintaan Client',
            ], 200);
        }
    }
    
    public function invoice($id){
        $reqorder = RequestOrder::findOrFail($id);
        
        if ($reqorder->status != '2' || $reqorder->invoices->count() > 0) 
            return redirect()->back()->with('error', 'Gagal Generate Invoice, Status belum Selesai atau terdapat Invoice Partial untuk permintaan ini!.');
        
        try {
            $invoiceRequest = new RequestOrderInvoice();
            $invoiceRequest->request_order_id = $id;
            $invoiceRequest->payment_status = '0';
            $invoiceRequest->save();

            $reqorder->generate_invoice = 1;
            $reqorder->save();

            return redirect()->route('purchase-order.setting', $id)->with('success', 'Generate Invoice untuk Pembelian Principal kode '.$purchase->code.' berhasil dilakukan.')
                            ->with('redirect_hash', 'invoice');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal Generate Invoice, Error: '.$e->getMessage())->with('redirect_hash', 'invoice');
        }
        
    }

    public function destroy($id){
        try {
            $reqorder = RequestOrder::find($id);
            if($reqorder->status != 0){
                return redirect()->back()->withInput()->with('error', 'Gagal menghapus Permintaan Client, Error: Status tidak diizinkan untuk dihapus');
            }
            $reqorder->delete();

            return redirect()->route('request-order.index')->with('success', 'Permintaan Client '.$reqorder->code.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Permintaan Client, Error: '.$e->getMessage());
        }
    }
}
