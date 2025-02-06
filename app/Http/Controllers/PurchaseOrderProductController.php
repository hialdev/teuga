<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use Illuminate\Http\Request;

class PurchaseOrderProductController extends Controller
{
    public function store($id, Request $request){
        $request->merge([
            'purchase_order_id' => $id,
            'price_buy' => array_map(fn($value) => $value !== null ? parseRupiah($value) : 0, $request->get('price_buy', [])),
        ]);

        $request->validate([
            'product_id'   => 'required|array',
            'product_id.*' => 'required|string|exists:osano.products,id',
            'pack_id'      => 'required|array',
            'pack_id.*'    => 'required|string|exists:osano.packs,id',
            'qty'          => 'required|array',
            'qty.*'        => 'required|numeric|min:1',
            'price_buy'    => 'required|array',
            'price_buy.*'  => 'required|numeric|min:1',
            'total_price'  => 'required|numeric|min:1',
            'tax'          => 'required|numeric|min:1',
            'total_price_taxed' => 'required|numeric|min:1',
        ]);

        $purchase = PurchaseOrder::findOrFail($id);

        if($purchase->requestOrder){
            foreach ($request->product_id as $index => $productId) {
                $maxQty = $purchase->requestOrder->getProcessingAnalytics()[$productId]['remaining_qty'] ?? 0;
                $prdct = Product::find($productId);
                if ($request->qty[$index] > $maxQty) {
                    return back()->withErrors(["qty.$index" => "Jumlah produk ID $prdct->name melebihi batas maksimal ($maxQty)."]);
                }
            }
        }
        try {
            if($purchase->payment_status != 0 && $purchase->requestOrder->status > 1){
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Produk Pembelian Ke Principal, Error: Status Pembelian Ke Principal tidak diizinkan untuk perubahan / hapus.');
            }

            if($purchase->products){
                $purchase->products()->delete();
            }
            foreach ($request->get('product_id') as $key => $product_id) {
                PurchaseOrderProduct::create([
                    'purchase_order_id' => $request->get('purchase_order_id'),
                    'product_id' => $product_id,
                    'pack_id' => $request->get('pack_id')[$key],
                    'price_buy' => $request->get('price_buy')[$key],
                    'qty' => $request->get('qty')[$key],
                ]);
            }
            $purchase->total_price = $request->get('total_price');
            $purchase->tax = $request->get('tax');
            $purchase->total_price_taxed = $request->get('total_price_taxed');
            $purchase->save();

            session()->forget('cart_'.$id);
            return redirect()->route('purchase-order.setting', ['id' => $id])->with('success', 'Product Pembelian Ke Principal (Request Order) berhasil disimpan, sekarang kelola Lampirannya.')->with('redirect_hash', 'lampiran');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Product Pembelian Ke Principal (Request Order), Error: '.$e->getMessage())->with('redirect_hash', 'lampiran');
        }
    }
}
