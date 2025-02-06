<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(){
        $suppliers = Supplier::orderBy('nama', 'asc')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request){
        $request->validate([
           'name' => 'required|string|min:3',
           'email' => 'required|string|email',
           'description' => 'required|string',
        ]);
        try {
            $supplier = new Supplier();
            $supplier->nama = $request->get('name');
            $supplier->email = $request->get('email');
            $supplier->keterangan = $request->get('description');
            $supplier->save();

            return redirect()->route('supplier.index')->with('success', 'Supplier '.$supplier->nama.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan supplier, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $request->validate([
           'name' => 'required|string|min:3',
           'email' => 'required|string|email',
           'description' => 'required|string',
        ]);
        try {
            $supplier = Supplier::find($id);
            $supplier->nama = $request->get('name');
            $supplier->email = $request->get('email');
            $supplier->keterangan = $request->get('description');
            $supplier->save();

            return redirect()->route('supplier.index')->with('success', 'Supplier '.$supplier->nama.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui supplier, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $supplier = Supplier::find($id);
            $supplier->delete();

            return redirect()->route('supplier.index')->with('success', 'Supplier '.$supplier->nama.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui supplier, Error: '.$e->getMessage());
        }
    }
}
