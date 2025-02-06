<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        // Query dasar
        $products = Product::query()
            ->where('nama', 'LIKE', '%' . $filter->q . '%')
            ->orWhere('keterangan', 'LIKE', '%' . $filter->q . '%');

        if ($filter->field === 'stock') {
            $products->leftJoin('stok', 'produk.id', '=', 'stok.produk_id')
                ->select('produk.*', DB::raw('COALESCE(stok.stok, 0) as stock_count'))
                ->orderBy('stock_count', $filter->order);
        } else {
            $products->orderBy($filter->field, $filter->order);
        }

        $products = $products->get();
        //dd($products);
        return view('products.index', compact('products', 'filter'));
    }

    public function add(){
        $categories = ProductCategory::all();
        return view('products.add', compact('categories'));
    }

    public function store(Request $request){
        $request->merge([
            'price' => parseRupiah($request->get('price'))
        ]);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'name' => 'required|string|min:3',
            'slug' => 'required|string|min:3|unique:produk,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'category' => 'required|string|exists:produk_kategori,id',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            } else {
                return redirect()->back()->with('error', 'Image is required'); 
            }

            $product = new Product();
            $product->image = $imagePath;
            $product->nama = $request->get('name');
            $product->slug = Str::slug($request->get('slug'));
            $product->keterangan = $request->get('description') ?? '';
            $product->harga = $request->get('price');
            $product->produk_kategori_id = $request->get('category');
            $product->save();

            $stock = new Stock();
            $stock->produk_id = $product->id;
            $stock->stok = $request->get('stock');
            $stock->save();

            return redirect()->route('product.index')->with('success', $product->nama.' berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat product, Error : '.$e->getMessage());
        }
    }

    public function edit($id){
        $product = Product::findOrFail($id);
        $categories = ProductCategory::all();
        return view('products.edit', compact('product', 'categories'));
    }
    
    public function update($id, Request $request){
        $request->merge([
            'price' => parseRupiah($request->get('price'))
        ]);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'name' => 'required|string|min:3',
            'slug' => 'required|string|min:3',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|exists:produk_kategori,id',
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
            $product->nama = $request->get('name');
            $product->slug = Str::slug($request->get('slug'));
            $product->keterangan = $request->get('description') ?? $product->keterangan;
            $product->harga = $request->get('price');
            $product->produk_kategori_id = $request->get('category');
            $product->save();

            return redirect()->route('product.index')->with('success', $product->nama.' berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui product, Error : '.$e->getMessage());
        }
    }

    public function updateStock($id, Request $request){
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'stock' => 'required|numeric',
        ]);

        try {
            $stock = Stock::where('id',$id)->where('produk_id',$request->get('product_id'))->first();
            if($stock){
                $stock->stok = $request->get('stock');
            }else{
                $stock = new Stock();
                $stock->produk_id = $request->get('product_id');
                $stock->stok = $request->get('stock');
            }
            $stock->save();

            return redirect()->route('product.index')->with('success', $stock->product->nama . ' stock berhasil diperbarui menjadi '.$stock->stok);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui stock, Error : ' . $e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            
            return redirect()->back()->with('success', 'Product '.$product->nama.' Berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus Product, Error : ' . $e->getMessage());
        }
    }

    //---------------------
    //------------ Category
    //---------------------
    
    public function categoryStore(Request $request){
        $request->validate([
            'category_name' => 'required|string|min:4|unique:produk_kategori,nama',
        ]);
        try {
            $category = new ProductCategory();
            $category->nama = $request->get('category_name');
            $category->save();

            return redirect()->back()->with('success', 'Berhasil menambahkan product category '.$category->nama);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan product category, Error: '.$e->getMessage());
        }
    }

    //-----------------------
    //------------ Etalase
    //-----------------------

    public function etalase(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'order' => $request->get('order', 'asc'),
        ];

        $products = Product::whereHas('stock', function ($query) {
            $query->where('stok', '>', 0); // Hanya produk dengan stok > 0
        })
        ->where('nama', 'LIKE', '%'.$filter->q.'%')
        ->orWhere('keterangan', 'LIKE', '%'.$filter->q.'%')
        ->orderBy('harga', $filter->order)
        ->get();
        return view('products.etalase', compact('products', 'filter'));
    }


    public function addCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->get('product_id')])) {
            // Jika produk sudah ada di cart, tambahkan jumlahnya
            $cart[$request->get('product_id')]['qty'] += $request->get('qty', 1);
        } else {
            // Jika produk belum ada di cart, tambahkan dengan qty default 1
            $cart[$request->get('product_id')] = [
                'id' => $request->get('product_id'),
                'qty' => 1,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }


    public function minQty(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
        ]);

        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->get('product_id')]) && $cart[$request->get('product_id')]['qty'] > 1) {
            $cart[$request->get('product_id')]['qty'] -= 1;
        } else {
            unset($cart[$request->get('product_id')]); // Hapus produk jika qty kurang dari 1
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Jumlah produk di keranjang berhasil dikurangi');
    }


    public function addQty(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
        ]);

        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->get('product_id')])) {
            $cart[$request->get('product_id')]['qty'] += 1;
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Jumlah produk di keranjang berhasil ditambah');
    }

    public function updateQty(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'qty' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $product = Product::find($request->get('product_id'));

        if (isset($cart[$request->get('product_id')])) {
            $cart[$request->get('product_id')]['qty'] = $request->get('qty');
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Jumlah produk di keranjang berhasil diperbarui',
            'product_price' => $product->harga,
            'ppn' => (int) setting('site.ppn'),
            'cart' => collect($cart)->map(function ($item, $id) {
                $product = Product::find($id);
                return [
                    'id' => $id,
                    'harga' => $product->harga,
                    'qty' => $item['qty']
                ];
            })->values()
        ]);
    }

}
