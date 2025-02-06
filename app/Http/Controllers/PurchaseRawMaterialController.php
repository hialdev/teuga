<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRawMaterial;
use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseRawMaterialController extends Controller
{
    public function index(Request $request)
    {
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        // Query dasar dengan relasi supplier dan raw_material
        $purchases = PurchaseRawMaterial::with(['supplier', 'rawMaterial'])
            ->where(function ($query) use ($filter) {
                $query->where('keterangan', 'LIKE', '%' . $filter->q . '%')
                    ->orWhereHas('supplier', function ($q) use ($filter) {
                        $q->where('nama', 'LIKE', '%' . $filter->q . '%');
                    })
                    ->orWhereHas('rawMaterial', function ($q) use ($filter) {
                        $q->where('nama', 'LIKE', '%' . $filter->q . '%');
                    });
            })->orWhere('code', 'LIKE', '%' . $filter->q . '%');

        // Filter berdasarkan field
        if ($filter->field == 'supplier') {
            $purchases = $purchases->orderBy(
                Supplier::select('nama')
                    ->whereColumn('suppliers.id', 'pembelian_bahan_baku.supplier_id'),
                $filter->order
            );
        } elseif ($filter->field == 'raw_material') {
            $purchases = $purchases->orderBy(
                RawMaterial::select('nama')
                    ->whereColumn('bahan_baku.id', 'pembelian_bahan_baku.bahan_baku_id'),
                $filter->order
            );
        } else {
            $purchases = $purchases->orderBy($filter->field, $filter->order);
        }

        $purchases = $purchases->get();

        return view('raw_materials.purchase.index', compact('purchases', 'filter'));
    }

    public function add(){
        $suppliers = Supplier::orderBy('nama', 'ASC')->get();
        $raw_materials = RawMaterial::orderBy('nama', 'ASC')->get();
        return view('raw_materials.purchase.add', compact('suppliers', 'raw_materials'));
    }

    public function store(Request $request){
        $request->merge([
            'total_purchase' => parseRupiah($request->get('total_purchase'))
        ]);
        $request->validate([
            'date' => 'required|date',
            'supplier_id' => 'required|string|exists:suppliers,id',
            'raw_material_id' => 'required|string|exists:bahan_baku,id',
            'description' => 'nullable|string',
            'proof_file' => 'required|file|mimes:webp,png,jpeg,jpg,pdf,doc,ppt,pptx,jfif',
            'total_purchase' => 'required|numeric',
            'is_available' => 'nullable|boolean',
        ]);
        try {
            if ($request->hasFile('proof_file')) {
                $proof_filePath = $request->file('proof_file')->store('purchase_raw_materials', 'public');
            } else {
                return redirect()->back()->with('error', 'proof_file is required'); 
            }

            $purchase = new PurchaseRawMaterial();
            $purchase->file_bukti = $proof_filePath;
            $purchase->tgl_pembelian = $request->get('date');
            $purchase->user_id = auth()->user()->id;
            $purchase->supplier_id = $request->get('supplier_id');
            $purchase->bahan_baku_id = $request->get('raw_material_id');
            $purchase->keterangan = $request->get('description');
            $purchase->total_harga_beli = $request->get('total_purchase');
            $purchase->save();
            
            $raw_material = RawMaterial::find($purchase->bahan_baku_id);
            if($raw_material && $request->get('is_available') && $raw_material->cek_tersedia != $request->get('is_available')){
                $raw_material->cek_tersedia = $request->get('is_available');
                $raw_material->save();
            }

            return redirect()->route('raw_material.purchase.index')->with('success', 'Berhasil mencatat pembelian Bahan Baku '.$raw_material->nama.'.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat Pembelian Bahan Baku / Raw Material, Error: '.$e->getMessage());
        }

    }

    public function edit($id){
        $purchase = PurchaseRawMaterial::with(['supplier', 'rawMaterial'])->findOrFail($id);
        $suppliers = Supplier::orderBy('nama', 'ASC')->get();
        $raw_materials = RawMaterial::orderBy('nama', 'ASC')->get();
        return view('raw_materials.purchase.edit', compact('purchase', 'suppliers', 'raw_materials'));
    }

    public function update($id, Request $request){
        $request->merge([
            'total_purchase' => parseRupiah($request->get('total_purchase'))
        ]);
        $request->validate([
            'date' => 'required|date',
            'supplier_id' => 'required|string|exists:suppliers,id',
            'raw_material_id' => 'required|string|exists:bahan_baku,id',
            'description' => 'nullable|string',
            'proof_file' => 'nullable|file|mimes:webp,png,jpeg,jpg,pdf,doc,ppt,pptx,jfif',
            'total_purchase' => 'required|numeric',
        ]);
        try {
            $purchase = PurchaseRawMaterial::find($id);
            if ($request->hasFile('proof_file')) {
                if ($purchase->file_bukti && file_exists(storage_path('app/public/' . $purchase->file_bukti))) {
                    unlink(storage_path('app/public/' . $purchase->file_bukti));
                }

                $proof_filePath = $request->file('proof_file')->store('purchase_raw_materials', 'public');
                $purchase->file_bukti = $proof_filePath;
            }
            $purchase->tgl_pembelian = $request->get('date');
            $purchase->supplier_id = $request->get('supplier_id');
            $purchase->bahan_baku_id = $request->get('raw_material_id');
            $purchase->keterangan = $request->get('description');
            $purchase->total_harga_beli = $request->get('total_purchase');
            $purchase->save();

            return redirect()->route('raw_material.purchase.index')->with('success', 'Berhasil merubah catatan pembelian Bahan Baku '.$purchase->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal merubah catatan Pembelian Bahan Baku / Raw Material, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $purchase = PurchaseRawMaterial::find($id);
            $purchase->delete();

            return redirect()->route('raw_material.purchase.index')->with('success', 'Berhasil menghapus catatan pembelian Bahan Baku '.$purchase->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus catatan Pembelian Bahan Baku / Raw Material, Error: '.$e->getMessage());
        }
    }
}
