<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index(){
        $categories = ProductCategory::all();
        return view('products.category', compact('categories'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
        ]);
        try {
            $category = new ProductCategory();
            $category->nama = $request->get('name');
            $category->save();

            return redirect()->route('category.index')->with('success', 'Kategori '.$category->nama.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kategori, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $request->validate([
            'name' => 'required|string',
        ]);
        try {
            $category = ProductCategory::find($id);
            $category->nama = $request->get('name');
            $category->save();

            return redirect()->route('category.index')->with('success', 'Kategori '.$category->nama.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $category = ProductCategory::find($id);
            $category->delete();

            return redirect()->route('category.index')->with('success', 'Kategori '.$category->nama.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus kategori, Error: '.$e->getMessage());
        }
    }
}
