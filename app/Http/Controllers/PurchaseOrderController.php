<?php

namespace App\Http\Controllers;

use App\Models\Principal;
use App\Models\PrincipalPic;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInvoice;
use App\Models\RequestOrder;
use App\Models\RequestOrderInvoice;
use App\Models\Transport;
use App\Models\TransportInvoice;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request) {
        $filter = (object) [
            'q' => $request->get('search', ''),
            'field' => $request->get('field', 'code'),
            'order' => $request->get('order') === 'oldest' ? 'asc' : 'desc',
        ];

        $purchases = PurchaseOrder::where('code', 'LIKE', '%'.$filter->q.'%')->orWhere('description', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)
                                ->get();

        return view('purchase_orders.index', compact('purchases', 'filter'));
    }

    public function add(){
        $reqorders = RequestOrder::orderBy('code', 'ASC')->get();
        $transports = Transport::whereDoesntHave('purchaseOrder')
                                ->orderBy('code', 'ASC')
                                ->get();
        $principals = Principal::orderBy('name', 'ASC')->get();
        $pics = PrincipalPic::orderBy('name', 'ASC')->get();
        return view('purchase_orders.add', compact('transports', 'reqorders', 'principals', 'pics'));
    }

    public function store(Request $request){
        $request->validate([
            'date' => 'required|date',
            'principal_id' => 'required|string|exists:osano.principals,id',
            'principal_pic_id' => 'required|string|exists:osano.principal_pics,id',
            'request_order_id' => 'nullable|string|exists:osano.request_orders,id',
            'delivery_address_id' => 'nullable|string|exists:osano.client_addresses,id',
            'transport_id' => 'nullable|string|exists:osano.transports,id|unique:osano.purchase_orders,transport_id',
            'pickup_address_id' => 'nullable|string|exists:osano.principal_addresses,id',
            'is_handle_logistic' => 'nullable|boolean',
            'description' => 'nullable|string|min:4',
        ]);
        try {
            $purchase = new PurchaseOrder();
            $purchase->date = $request->get('date');
            $purchase->principal_id = $request->get('principal_id');
            $purchase->principal_pic_id = $request->get('principal_pic_id');
            $purchase->request_order_id = $request->get('request_order_id');
            $purchase->transport_id = $request->get('transport_id');
            $purchase->pickup_address_id = $request->get('pickup_address_id');
            $purchase->delivery_address_id = $request->get('delivery_address_id');
            $purchase->is_handle_logistic = $request->get('is_handle_logistic') ?? '0';
            $purchase->description = $request->get('description');
            $purchase->save();

            return redirect()->route('purchase-order.setting', ['id' => $purchase->id])->with('success', 'Pembelian ke Principal '.$purchase->name.' berhasil ditambahkan, sekarang tentukan produknya.')->with('redirect_hash', 'produk');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Pembelian ke Principal, Error: '.$e->getMessage())->with('redirect_hash', 'produk');
        }
    }

    public function setting($id, Request $request){
        $purchase = PurchaseOrder::find($id);
        $reqorders = RequestOrder::orderBy('code', 'ASC')->get();
        $transports = Transport::whereDoesntHave('purchaseOrder')
                                ->orderBy('code', 'ASC')
                                ->get();
        if ($purchase && $purchase->transport) {
            $transports->push($purchase->transport);
        }
        $principals = Principal::orderBy('name', 'ASC')->get();
        $pics = PrincipalPic::orderBy('name', 'ASC')->get();

        $filter = (object) [
            'q' => $request->get('search') ?? '',
        ];
        $products = $purchase->requestOrder && optional($purchase->requestOrder->products)->isNotEmpty()
            ? $purchase->requestOrder->products()->where('name', 'LIKE', "%{$filter->q}%")->get()
            : Product::where('name', 'LIKE', "%{$filter->q}%")->orderBy('name')->paginate((int) setting('site.product-limit') ?? 6);

        if(count(session()->get('cart_'.$id, [])) == 0){
            $this->refetch($id, new Request());
        }

        if ($request->filled('hashProduct')) {
            session(['redirect_hash' => 'produk']);
        }

        return view('purchase_orders.setting', compact('reqorders', 'transports', 'principals', 'pics', 'purchase', 'filter', 'products'));
    }

    public function update($id, Request $request){
        $purchase = PurchaseOrder::find($id);
        $request->validate([
            'date' => 'required|date',
            'principal_id' => 'required|string|exists:osano.principals,id',
            'principal_pic_id' => 'required|string|exists:osano.principal_pics,id',
            'request_order_id' => 'nullable|string|exists:osano.request_orders,id',
            'delivery_address_id' => 'nullable|string|exists:osano.client_addresses,id',
            'transport_id' => 'nullable|string|exists:osano.transports,id|unique:osano.purchase_orders,transport_id',
            'pickup_address_id' => 'nullable|string|exists:osano.principal_addresses,id',
            'is_handle_logistic' => 'nullable|boolean',
            'description' => 'nullable|string|min:4',
        ]);
        try {
            if($purchase->status != '0'){
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Pembelian ke Principal, Error: Status tidak diizinkan untuk diperbarui');
            }
            $purchase->date = $request->get('date');
            $purchase->principal_id = $request->get('principal_id');
            $purchase->principal_pic_id = $request->get('principal_pic_id');
            $purchase->request_order_id = $request->get('request_order_id');
            $purchase->transport_id = $request->get('transport_id');
            $purchase->pickup_address_id = $request->get('pickup_address_id');
            $purchase->delivery_address_id = $request->get('delivery_address_id');
            $purchase->is_handle_logistic = $request->get('is_handle_logistic') ?? '0';
            $purchase->description = $request->get('description');
            $purchase->save();

            return redirect()->route('purchase-order.setting', ['id' => $purchase->id])->with('success', 'Pembelian ke Principal '.$purchase->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Pembelian ke Principal, Error: '.$e->getMessage());
        }
    }

    public function addCart($id, Request $request)
    {
        $purchase = PurchaseOrder::find($id);
        if($purchase->status != '0') return redirect()->back()->with('error', 'Pembelian telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'produk');

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
                'price_buy' => 0,
                'pack_id' => '',
            ];
        }

        session()->put('cart_'.$id, $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang')->with('redirect_hash', 'produk');
    }

    public function removeCart($id, Request $request)
    {
        $purchase = PurchaseOrder::find($id);
        if($purchase->status != '0') return redirect()->back()->with('error', 'Pembelian telah diproses, perubahan tidak diizinkan')->with('redirect_hash', 'produk');

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
        $purchase = PurchaseOrder::find($id);
        session()->forget('cart_'.$id);
        $cart = [];
        if($purchase->products){
            foreach ($purchase->products as $purchaseproduct) {
                $cart[$purchaseproduct->product_id] = [
                    'id' => $purchaseproduct->product_id,
                    'qty' => $purchaseproduct->qty,
                    'price_buy' => $purchaseproduct->price_buy,
                    'pack_id' => $purchaseproduct->pack_id,
                ];
            }
        }
        session()->put('cart_'.$id, $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mereset Ulang dengan data Produk Pembelian ke Principal',
            ], 200);
        }
    }

    public function process($id, Request $request){
        try {
            $purchase = PurchaseOrder::find($id);
            if($purchase->status == '0' && $purchase->requestOrder && !$purchase->requestOrder->isStillRemain())
                return redirect()->back()->with('error', 'Gagal memproses Pembelian, Sesuaikan qty produk yang di proses dengan sisa yang belum diproses!.');
            if($purchase->products->count() == 0)
                return redirect()->back()->with('error', 'Gagal memproses Pembelian, Tidak ada produk yang diproses!.');

            if($purchase->status == 0){
                $purchase->status = (string) 1;
            }else if($purchase->status == 1){
                $purchase->status = (string) 2;
            }else{
                return redirect()->back()->with('error', 'Tidak ada proses selanjutnya.');
            }
            $purchase->save();

            if( $purchase->is_handle_logistic ){
                $transport = $purchase->transport;
                $transport->status = $purchase->status;
                $transport->save();
            }

            if ($purchase->requestOrder){
                $reqOrder = $purchase->requestOrder;
                $reqOrder->status = $reqOrder->isFinished() ? '2' : '1';
                $reqOrder->save();
            }


            return redirect()->back()->with('success', 'Berhasil '.($purchase->status == 1 ? 'Selesaikan' : 'Proses').' Pembelian ke Principal dengan Kode '.$purchase->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses / selesaikan Pembelian ke Principal, Error: '.$e->getMessage());
        }
    }

    public function invoice($id){
        $purchase = PurchaseOrder::findOrFail($id);
        
        if ($purchase->status != '2' || $purchase->invoice) 
            return redirect()->back()->with('error', 'Gagal Generate Invoice, Status tidak valid atau Invoice telah dibuat!.');
        
        try {
            $invoicePurchase = new PurchaseOrderInvoice();
            $invoicePurchase->purchase_order_id = $purchase->id;
            $invoicePurchase->payment_status = '0';
            $invoicePurchase->save();

            if($purchase->transport){
                $invoiceTransport = new TransportInvoice();
                $invoiceTransport->transport_id = $purchase->transport->id;
                $invoiceTransport->payment_status = '0';
                $invoiceTransport->save();

                $purchase->transport->generate_invoice = 1;
                $purchase->transport->save();
            }

            $purchase->generate_invoice = 1;
            $purchase->save();

            return redirect()->route('purchase-order.setting', $id)->with('success', 'Generate Invoice untuk Pembelian Principal kode '.$purchase->code.' berhasil dilakukan.')
                            ->with('redirect_hash', 'invoice');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal Generate Invoice, Error: '.$e->getMessage())->with('redirect_hash', 'invoice');
        }
        
    }    

    public function invoiceClientPartial($id){
        $purchase = PurchaseOrder::findOrFail($id);
        
        if ($purchase->status != '2' || !$purchase->invoice) 
            return redirect()->back()->with('error', 'Gagal Generate Invoice, Status tidak valid atau Invoice Pembelian ke Principal belum dibuat!.')->with('redirect_hash', 'invoice');

        if ($purchase->requestOrder->invoice && !$purchase->requestOrder->invoice->purchaseOrder) 
            return redirect()->back()->with('error', 'Gagal Generate Invoice Partial, Sudah ada Invoice Secara Keseluruhan!.')->with('redirect_hash', 'invoice');

        try {
            $invoiceClient = new RequestOrderInvoice();
            $invoiceClient->request_order_id = $purchase->requestOrder->id;
            $invoiceClient->purchase_order_id = $purchase->id;
            $invoiceClient->payment_status = '0';
            $invoiceClient->save();

            return redirect()->route('purchase-order.setting', $id)->with('success', 'Generate Partial Invoice Client untuk Pembelian Principal kode '.$purchase->code.' berhasil dilakukan.')
                            ->with('redirect_hash', 'invoice');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal Generate Partial Invoice, Error: '.$e->getMessage())->with('redirect_hash', 'invoice');
        }
        
    }    

    public function destroy($id){
        try {
            $purchase = PurchaseOrder::find($id);
            if($purchase->status != '0'){
                return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pembelian ke Principal, Error: Status tidak diizinkan untuk dihapus');
            }
            $purchase->delete();

            return redirect()->route('purchase-order.index')->with('success', 'Pembelian ke Principal '.$purchase->code.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pembelian ke Principal, Error: '.$e->getMessage());
        }
    }
}
