<?php

namespace App\Http\Controllers;

use App\Models\RequestOrder;
use App\Models\RequestOrderProduct;
use Illuminate\Http\Request;

class RequestOrderProductController extends Controller
{
    public function store($id, Request $request){
        $request->merge([
            'request_order_id' => $id,
            'price_sale' => array_map(fn($value) => $value !== null ? parseRupiah($value) : 0, $request->get('price_sale', [])),
        ]);
        $request->validate([
            'product_id.*' => 'required|string|exists:osano.products,id',
            'qty.*' => 'required|numeric|min:1',
            'price_sale.*' => 'required|min:1',
            'total_price' => 'required|min:1',
            'tax' => 'required|min:1',
            'total_price_taxed' => 'required|min:1',
        ]);
        try {
            $reqorder = RequestOrder::find($id);
            if($reqorder->status != 0){
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Produk Permintaan Client, Error: Status tidak diizinkan untuk diperbarui');
            }
            if($reqorder->products){
                $reqorder->products()->delete();
            }
            foreach ($request->get('product_id') as $key => $product_id) {
                RequestOrderProduct::create([
                    'request_order_id' => $request->get('request_order_id'),
                    'product_id' => $product_id,
                    'price_sale' => $request->get('price_sale')[$key],
                    'qty' => $request->get('qty')[$key],
                ]);
            }

            $reqorder->total_price = $request->get('total_price');
            $reqorder->tax = $request->get('tax');
            $reqorder->total_price_taxed = $request->get('total_price_taxed');
            $reqorder->save();

            session()->forget('cart_'.$id);
            return redirect()->route('request-order.setting', ['id' => $id])->with('success', 'Product Permintaan Client (Request Order) berhasil disimpan, sekarang kelola Lampirannya.')->with('redirect_hash', 'lampiran');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Product Permintaan Client (Request Order), Error: '.$e->getMessage())->with('redirect_hash', 'lampiran');
        }
    }
}
