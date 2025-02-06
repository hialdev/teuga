<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $raw_materials = RawMaterial::where('nama', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        return view('raw_materials.index', compact('raw_materials', 'filter'));
    }

    public function add(){
        return view('raw_materials.add');
    }

    public function store(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:webp,png,jpg,jpeg,svg|max:2048',
            'name' => 'required|string|min:3',
            'warna' => 'required|string|min:3',
            'merek' => 'required|string|min:3',
            'is_available' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);
        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('raw_materials', 'public');
            } else {
                return redirect()->back()->with('error', 'Image is required'); 
            }

            $raw_material = new RawMaterial();
            $raw_material->image = $imagePath;
            $raw_material->nama = $request->get('name');
            $raw_material->warna = $request->get('warna');
            $raw_material->merek = $request->get('merek');
            $raw_material->cek_tersedia = $request->get('is_available') ?? 0;
            $raw_material->keterangan = $request->get('description');
            $raw_material->save();

            return redirect()->route('raw_material.index')->with('success', 'Raw Material '.$raw_material->nama.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Raw Material, Error: '.$e->getMessage());
        }
    }

    public function edit($id){
        $raw_material = RawMaterial::find($id);
        return view('raw_materials.edit', compact('raw_material'));
    }

    public function update($id, Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,svg|max:2048',
            'name' => 'required|string|min:3',
            'warna' => 'required|string|min:3',
            'merek' => 'required|string|min:3',
            'is_available' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        try {
            $raw_material = RawMaterial::find($id);
            if ($request->hasFile('image')) {
                if ($raw_material->image && file_exists(storage_path('app/public/' . $raw_material->image))) {
                    unlink(storage_path('app/public/' . $raw_material->image));
                }
                $imagePath = $request->file('image')->store('raw_materials', 'public');
                $raw_material->image = $imagePath;
            }

            $raw_material->nama = $request->get('name');
            $raw_material->warna = $request->get('warna');
            $raw_material->merek = $request->get('merek');
            $raw_material->cek_tersedia = $request->get('is_available') ?? 0;
            $raw_material->keterangan = $request->get('description');
            $raw_material->save();

            return redirect()->route('raw_material.index')->with('success', 'Raw Material '.$raw_material->nama.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Raw Material, Error: '.$e->getMessage());
        }
    }

    public function available($id, Request $request){
        $request->validate([
            'is_available' => 'nullable|boolean',
        ]);
        try {
            $raw_material = RawMaterial::find($id);
            $raw_material->cek_tersedia = $request->get('is_available') ?? 0;
            $raw_material->save();

            return redirect()->route('raw_material.index')->with('success', 'Status Raw Material '.$raw_material->nama.' berhasil diperbarui menjadi '.($request->get('is_available') ? 'Teredia' : 'Tidak Tersedia'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Raw Material, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $raw_material = RawMaterial::find($id);
            $raw_material->delete();

            return redirect()->route('raw_material.index')->with('success', 'Raw Material '.$raw_material->nama.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Raw Material, Error: '.$e->getMessage());
        }
    }

}
