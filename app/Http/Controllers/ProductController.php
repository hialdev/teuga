<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $products = Product::where('name', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        return view('products.index', compact('products', 'filter'));
    }

    public function add(){
        $packs = Pack::orderBy('name', 'ASC')->get();
        $units = Unit::orderBy('name', 'ASC')->get();
        return view('products.add', compact('packs', 'units'));
    }

    public function store(Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.products,name',
            'unit_id' => 'required|string|exists:osano.units,id',
            'description' => 'nullable|string',
        ]);
        try {
            $product = new Product();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }
            $product->name = $request->get('name');
            $product->unit_id = $request->get('unit_id');
            $product->description = $request->get('description');
            $product->save();

            return redirect()->route('product.index')->with('success', 'Product '.$product->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Product, Error: '.$e->getMessage());
        }
    }

    public function edit($id){
        $product = Product::find($id);
        $packs = Pack::orderBy('name', 'ASC')->get();
        $units = Unit::orderBy('name', 'ASC')->get();
        return view('products.edit', compact('product', 'packs', 'units'));
    }

    public function update($id, Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.products,name,'.$id,
            'unit_id' => 'required|string|exists:osano.units,id',
            'description' => 'nullable|string',
        ]);

        try {
            $product = Product::find($id);
            if ($request->hasFile('image')) {
                if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                    unlink(storage_path('app/public/' . $product->image));
                }
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }
            $product->name = $request->get('name');
            $product->unit_id = $request->get('unit_id');
            $product->description = $request->get('description');
            $product->save();

            return redirect()->route('product.index')->with('success', 'Product '.$product->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Product, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $product = Product::find($id);
            $product->delete();

            return redirect()->route('product.index')->with('success', 'Product '.$product->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Product, Error: '.$e->getMessage());
        }
    }
}
