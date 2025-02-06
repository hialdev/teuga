<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'name',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $units = Unit::query()
            ->where('name', 'LIKE', '%' . $filter->q . '%');

        // if ($filter->field === 'orders') {
        //     $units->withCount('orders')->orderBy('orders_count', $filter->order);
        // } else {
            $units->orderBy($filter->field, $filter->order);
        // }

        $units = $units->get();
        return view('units.index', compact('units', 'filter'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'nullable|string',
            'code' => 'required|string|unique:osano.units,code',
        ]);
        try {

            $unit = new Unit();
            $unit->name = $request->get('name');
            $unit->code = $request->get('code');
            $unit->save();

            return redirect()->back()->with('success', 'Unit '.$unit->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan unit, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $request->validate([
            'name' => 'nullable|string',
            'code' => 'required|string|unique:osano.units,code,'.$id,
        ]);
        try {
            $unit = Unit::find($id);
            $unit->name = $request->get('name');
            $unit->code = $request->get('code');
            $unit->save();

            return redirect()->route('unit.index')->with('success', 'Unit '.$unit->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui unit, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $unit = Unit::find($id);
            if($unit->packs->count() > 0){
                return redirect()->back()->with('error', 'Gagal menghapus unit, unit '.$unit->name.' memiliki data Pengemasan, Hapus terlebih dahulu atau perbarui ke unit yang lain agar dapat menghapus unit.');
            }
            $unit->delete();

            return redirect()->route('unit.index')->with('success', 'unit '.$unit->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus unit, Error: '.$e->getMessage());
        }
    }
}
