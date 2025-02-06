<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Unit;
use Illuminate\Http\Request;

class PackController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'name',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $packs = Pack::query()
            ->where('name', 'LIKE', '%' . $filter->q . '%');

        // if ($filter->field === 'orders') {
        //     $packs->withCount('orders')->orderBy('orders_count', $filter->order);
        // } else {
            $packs->orderBy($filter->field, $filter->order);
        // }

        $packs = $packs->get();
        $units = Unit::orderBy('name', 'asc')->get();
        return view('packs.index', compact('packs', 'units', 'filter'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'nullable|string',
            'capacity' => 'required|numeric|min:0',
            'unit_id' => 'required|string|exists:osano.units,id',
        ]);
        try {

            $pack = new Pack();
            $pack->name = $request->get('name');
            $pack->capacity = $request->get('capacity');
            $pack->unit_id = $request->get('unit_id');
            $pack->save();

            return redirect()->back()->with('success', 'Kemasan '.$pack->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan unit, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $request->validate([
            'name' => 'nullable|string',
            'capacity' => 'required|numeric|min:0',
            'unit_id' => 'required|string|exists:osano.units,id',
        ]);
        try {
            $pack = Pack::find($id);
            $pack->name = $request->get('name');
            $pack->capacity = $request->get('capacity');
            $pack->unit_id = $request->get('unit_id');
            $pack->save();

            return redirect()->route('pack.index')->with('success', 'Kemasan '.$pack->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui unit, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $pack = Pack::find($id);
            // if($pack->packs->count() > 0){
            //     return redirect()->back()->with('error', 'Gagal menghapus unit, unit '.$pack->name.' memiliki data Pengemasan, Hapus terlebih dahulu atau perbarui ke unit yang lain agar dapat menghapus pack.');
            // }
            $pack->delete();

            return redirect()->route('pack.index')->with('success', 'Kemasan '.$pack->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus unit, Error: '.$e->getMessage());
        }
    }
}
