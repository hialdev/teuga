<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $assets = Asset::where('name', 'LIKE', '%'.$filter->q.'%')
                        ->orWhere('description', 'LIKE', '%'.$filter->q.'%')
                        ->orWhereHas('account', fn($q) => $q->where('account_name', 'LIKE', '%'.$filter->q.'%')
                                                            ->orWhere('code', 'LIKE', '%'.$filter->q.'%'))
                        ->orderBy($filter->field, $filter->order)->get();
        return view('assets.index', compact('assets', 'filter'));
    }

    public function add(){
        return view('assets.add');
    }

    public function store(Request $request){
        $request->merge([
            'purchase_price' => parseRupiah($request->get('purchase_price')),
            'residu_price' => parseRupiah($request->get('residu_price')),
        ]);
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,svg|max:2048',
            'name' => 'required|string|min:3',
            'account_id' => 'nullable|string|exists:accounting.accounts,id',
            'description' => 'nullable|string',
            'date' => 'required|string|min:3',
            'purchase_price' => 'required|numeric|min:1',
            'residu_price' => 'nullable|numeric',
            'usefull_life' => 'nullable|numeric',
            'appreciation_rate' => 'nullable|numeric',
            'is_appreciating' => 'nullable|boolean',
        ]);
        try {
            $asset = new Asset();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('assets', 'public');
                $asset->image = $imagePath;
            }

            $asset->name = $request->get('name');
            $asset->account_id = $request->get('account_id');
            $asset->description = $request->get('description');
            $asset->date = $request->get('date');
            $asset->purchase_price = $request->get('purchase_price');
            $asset->residu_price = $request->get('is_appreciating') ? null : $request->get('residu_price');
            $asset->useful_life = $request->get('is_appreciating') ? null : $request->get('usefull_life');
            $asset->is_appreciating = $request->get('is_appreciating') ? 1 : 0;
            $asset->appreciation_rate = $request->get('is_appreciating') ? $request->get('appreciation_rate') : null;
            $asset->save();

            return redirect()->route('asset.index')->with('success', 'Asset '.$asset->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Asset, Error: '.$e->getMessage());
        }
    }

    public function edit($id){
        $asset = Asset::find($id);
        return view('assets.edit', compact('asset'));
    }

    public function update($id, Request $request){
        $request->merge([
            'purchase_price' => parseRupiah($request->get('purchase_price')),
            'residu_price' => parseRupiah($request->get('residu_price')),
        ]);
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,svg|max:2048',
            'name' => 'required|string|min:3',
            'account_id' => 'nullable|string|exists:accounting.accounts,id',
            'description' => 'nullable|string',
            'date' => 'required|string|min:3',
            'purchase_price' => 'required|numeric|min:1',
            'residu_price' => 'nullable|numeric',
            'usefull_life' => 'nullable|numeric',
            'appreciation_rate' => 'nullable|numeric',
            'is_appreciating' => 'nullable|boolean',
        ]);
        try {
            $asset = Asset::find($id);
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('assets', 'public');
                $asset->image = $imagePath;
            }

            $asset->name = $request->get('name');
            $asset->account_id = $request->get('account_id');
            $asset->description = $request->get('description');
            $asset->date = $request->get('date');
            $asset->purchase_price = $request->get('purchase_price');
            $asset->residu_price = $request->get('is_appreciating') ? null : $request->get('residu_price');
            $asset->useful_life = $request->get('is_appreciating') ? null : $request->get('usefull_life');
            $asset->is_appreciating = $request->get('is_appreciating') ? 1 : 0;
            $asset->appreciation_rate = $request->get('is_appreciating') ? $request->get('appreciation_rate') : null;
            $asset->save();

            return redirect()->route('asset.index')->with('success', 'Asset '.$asset->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Asset, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $asset = Asset::find($id);
            if($asset->transaction) return redirect()->back()->withInput()->with('error', 'Gagal menghapus Asset, Error: Terdapat transaksi pada jurnal');
            $asset->delete();

            return redirect()->route('asset.index')->with('success', 'Asset '.$asset->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Asset, Error: '.$e->getMessage());
        }
    }
}
