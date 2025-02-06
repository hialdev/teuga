<?php

namespace App\Http\Controllers;

use App\Models\CustomOrder;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\RequestProduction;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;

class RequestProductionController extends Controller
{
    public function index(Request $request)
    {
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        // Query dasar dengan relasi custom_order dan raw_material
        $prequests = RequestProduction::with(['product','customOrder', 'rawMaterial', 'pic'])
            ->where(function ($query) use ($filter) {
                $query->where('keterangan', 'LIKE', '%' . $filter->q . '%')
                    ->orWhereHas('customOrder', function ($q) use ($filter) {
                        $q->where('code', 'LIKE', '%' . $filter->q . '%')->orWhere('keterangan', 'LIKE', '%' . $filter->q . '%');
                    })
                    ->orWhereHas('rawMaterial', function ($q) use ($filter) {
                        $q->where('nama', 'LIKE', '%' . $filter->q . '%');
                    })
                    ->orWhereHas('product', function ($q) use ($filter) {
                        $q->where('nama', 'LIKE', '%' . $filter->q . '%');
                    })
                    ->orWhereHas('pic', function ($q) use ($filter) {
                        $q->where('name', 'LIKE', '%' . $filter->q . '%');
                    });
            })->orWhere('code', 'LIKE', '%' . $filter->q . '%');

        // Filter berdasarkan field
        if ($filter->field == 'custom_order') {
            $prequests = $prequests->orderBy(
                CustomOrder::select('code')
                    ->whereColumn('pesanan_khusus.id', 'pengajuan_produksi.pesanan_khusus_id'),
                $filter->order
            );
        } elseif ($filter->field == 'raw_material') {
            $prequests = $prequests->orderBy(
                RawMaterial::select('nama')
                    ->whereColumn('bahan_baku.id', 'pengajuan_produksi.pesanan_khusus_id'),
                $filter->order
            );
        }elseif ($filter->field == 'product') {
            $prequests = $prequests->orderBy(
                Product::select('nama')
                    ->whereColumn('produk.id', 'pengajuan_produksi.produk_id'),
                $filter->order
            );
        }elseif ($filter->field == 'user') {
            $prequests = $prequests->orderBy(
                Product::select('nama')
                    ->whereColumn('users.id', 'pengajuan_produksi.user_id'),
                $filter->order
            );
        } else {
            $prequests = $prequests->orderBy($filter->field, $filter->order);
        }

        $prequests = $prequests->get();

        return view('request_productions.index', compact('prequests', 'filter'));
    }

    public function add(){
        $pics = User::role(['employee', 'admin'])->where('id', '!=', auth()->user()->id)->get();
        $products = Product::orderBy('nama', 'ASC')->get();
        $raw_materials = RawMaterial::orderBy('nama', 'ASC')->get();
        $custom_orders = CustomOrder::orderBy('code', 'ASC')->get();
        return view('request_productions.add', compact('products', 'raw_materials', 'custom_orders', 'pics'));
    }

    public function store(Request $request){
        $request->validate([
            'raw_material_id' => 'required|string|exists:bahan_baku,id',
            'product_id' => 'required|string|exists:produk,id',
            'user_id' => 'nullable|string|exists:users,id',
            'deadline' => 'required|date',
            'qty' => 'required|numeric',
            'attachment' => 'nullable|file|mimes:webp,png,jpeg,jpg,pdf,doc,ppt,pptx,jfif',
            'description' => 'nullable|string',
            'is_custom_order' => 'nullable|boolean',
            'custom_order_id' => 'nullable|string|exists:pesanan_khusus,id',
        ]);
        try {
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('request_productions', 'public');
            }

            $production = new RequestProduction();

            if(auth()->user()->getRoleNames()[0] == 'employee'){
                $production->user_id = auth()->user()->id;
            }else{
                $production->user_id = $request->get('user_id') ?? auth()->user()->id;
            }

            if($request->get('is_custom_order') && $request->get('custom_order_id')) $production->pesanan_khusus_id = $request->get('custom_order_id');

            $production->lampiran = $attachmentPath ?? null;

            $production->deadline = $request->get('deadline');
            $production->produk_id = $request->get('product_id');
            $production->bahan_baku_id = $request->get('raw_material_id');
            $production->qty = $request->get('qty');
            $production->cek_pesanan_khusus = $request->get('is_custom_order') ?? 0;
            $production->pesanan_khusus_id = $request->get('custom_order_id');
            $production->keterangan = $request->get('description');
            $production->save();

            return redirect()->route('request_production.index')->with('success', 'Permintaan Produksi dengan Kode '.$production->code.' berhasil diajukan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengajukan Permintaan Produksi (Request Production), Error: '.$e->getMessage());
        }

    }

    public function edit($id){
        $production = RequestProduction::findOrFail($id);
        if($production->status != 0) return redirect()->back()->with('error', 'Permintaan produksi dengan kode '. $production->code. ' sedang diproses atau telah selesai, sehingga tidak dapat mengubahnya.');
        
        $pics = User::role(['employee', 'admin'])->where('id', '!=', auth()->user()->id)->get();
        $products = Product::orderBy('nama', 'ASC')->get();
        $raw_materials = RawMaterial::orderBy('nama', 'ASC')->get();
        $custom_orders = CustomOrder::orderBy('code', 'ASC')->get();
        return view('request_productions.edit', compact('products', 'raw_materials', 'custom_orders', 'pics', 'production'));
    }

    public function update($id, Request $request){
        $request->validate([
            'raw_material_id' => 'required|string|exists:bahan_baku,id',
            'product_id' => 'required|string|exists:produk,id',
            'user_id' => 'required|string|exists:users,id',
            'deadline' => 'required|date',
            'qty' => 'required|numeric',
            'attachment' => 'nullable|file|mimes:webp,png,jpeg,jpg,pdf,doc,ppt,pptx,jfif',
            'description' => 'nullable|string',
            'is_custom_order' => 'nullable|boolean',
            'custom_order_id' => 'nullable|string|exists:pesanan_khusus,id',
        ]);
        try {
            $production = RequestProduction::find($id);

            if($production->status != 0) return redirect()->back()->with('error', 'Permintaan produksi dengan kode '. $production->code. ' sedang diproses atau telah selesai, sehingga tidak dapat mengubahnya.');

            if ($request->hasFile('attachment')) {
                if ($production->lampiran && file_exists(storage_path('app/public/' . $production->lampiran))) {
                    unlink(storage_path('app/public/' . $production->lampiran));
                }

                $attachmentPath = $request->file('attachment')->store('request_productions', 'public');
                $production->lampiran = $attachmentPath;
            }

            if(auth()->user()->getRoleNames()[0] == 'employee'){
                $production->user_id = auth()->user()->id;
            }else{
                $production->user_id = $request->get('user_id') ?? auth()->user()->id;
            }

            if($request->get('is_custom_order') && $request->get('custom_order_id')) $production->pesanan_khusus_id = $request->get('custom_order_id');

            $production->lampiran = $attachmentPath ?? $production->lampiran;

            $production->deadline = $request->get('deadline');
            $production->produk_id = $request->get('product_id');
            $production->bahan_baku_id = $request->get('raw_material_id');
            $production->qty = $request->get('qty');
            $production->cek_pesanan_khusus = $request->get('is_custom_order') ?? 0;
            $production->pesanan_khusus_id = $request->get('custom_order_id');
            $production->keterangan = $request->get('description');
            $production->status = '0';
            $production->save();

            return redirect()->route('request_production.index')->with('success', 'Permintaan Produksi dengan Kode '.$production->code.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Permintaan Produksi (Request Production), Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $production = RequestProduction::find($id);
            $production->delete();

            return redirect()->route('request_production.index')->with('success', 'Berhasil menghapus Permintaan Produksi dengan Kode '.$production->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus Permintaan Produksi dengan Kode, Error: '.$e->getMessage());
        }
    }

    public function process($id, Request $request){
        try {
            $production = RequestProduction::find($id);
            if($production->status == 0){
                $production->status = (string) 1;
            }else if($production->status == 1){
                $production->status = (string) 2;
            }else{
                return redirect()->back()->with('error', 'Tidak ada proses selanjutnya.');
            }
            $production->save();

            if(!$production->cek_pesanan_khusus){
                $stock = Stock::where('produk_id', $production->produk_id)->first();
                $stock->stok = $stock->stok + $production->qty;
                $stock->save();
            }

            return redirect()->route('request_production.index')->with('success', 'Berhasil '.($production->status == 0 ? 'Proses' : 'Selesaikan').' Permintaan Produksi dengan Kode '.$production->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses / selesaikan Permintaan Produksi, Error: '.$e->getMessage());
        }
    }
}
