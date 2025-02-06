<?php

namespace App\Http\Controllers;

use App\Models\CustomOrder;
use App\Models\Desain;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Retur;
use App\Models\User;
use Illuminate\Http\Request;

class CustomOrderController extends Controller
{
    public function index(Request $request) {
        $filter = (object) [
            'q' => $request->get('search', ''),
            'field' => $request->get('field', 'created_at'),
            'order' => $request->get('order') === 'oldest' ? 'asc' : 'desc',
        ];

        $custom_orders = CustomOrder::with('customer')
            ->where(function ($query) use ($filter) {
                $query->where('code', 'LIKE', '%' . $filter->q . '%')
                    ->orWhereHas('customer', function ($q) use ($filter) {
                        $q->where('email', 'LIKE', '%' . $filter->q . '%')
                            ->orWhere('name', 'LIKE', '%' . $filter->q . '%');
                    });
            })
            ->orderBy($filter->field, $filter->order)
            ->get();

        return view('custom_orders.index', compact('custom_orders', 'filter'));
    }

    public function waiting(Request $request) {
        $filter = (object) [
            'q' => $request->get('search', ''),
            'field' => $request->get('field', 'created_at'),
            'order' => $request->get('order') === 'newest' ? 'desc' : 'asc',
        ];

        $custom_orders = CustomOrder::with('customer')
            ->where(function ($query) use ($filter) {
                $query->where('code', 'LIKE', '%' . $filter->q . '%')
                    ->orWhereHas('customer', function ($q) use ($filter) {
                        $q->where('email', 'LIKE', '%' . $filter->q . '%')
                            ->orWhere('name', 'LIKE', '%' . $filter->q . '%');
                    });
            })
            ->where('status', '1')
            ->orderBy($filter->field, $filter->order)
            ->get();

        return view('custom_orders.waiting', compact('custom_orders', 'filter'));
    }
    
    public function my(Request $request) {
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];
        $custom_orders = CustomOrder::where('user_id', auth()->user()->id)
                        ->where('code', 'LIKE', '%'.$filter->q.'%')
                        ->orderBy($filter->field, $filter->order)
                        ->get();
        return view('custom_orders.my', compact('custom_orders', 'filter'));
    }

    public function add(){
        $customers = User::role('pelanggan')->get();
        $raw_materials = RawMaterial::where('cek_tersedia', 1)->orderBy('nama', 'asc')->get();
        $products = Product::orderBy('nama', 'asc')->get();
        $desains = Desain::orderBy('nama', 'asc')->get();
        return view('custom_orders.add', compact('raw_materials', 'products', 'desains', 'customers'));
    }

    public function store(Request $request){
        $request->validate([
            'raw_from_customer' => 'nullable|boolean',
            'raw_material_id' => 'nullable|string|exists:bahan_baku,id',
            'product_id' => 'required|string|exists:produk,id',
            'desain_id' => 'required|string|exists:desains,id',
            
            // Raw From Custome True
            'raw_name' => 'nullable|string',
            'raw_description' => 'nullable|string',
            'raw_attachment' => 'nullable|file|mimes:webp,png,jpeg,jpg,pdf,doc,ppt,pptx,jfif',

            // Default
            'qty' => 'required|numeric',
            'deadline' => 'required|date',
            'customer_id' => 'nullable|string|exists:users,id',
        ]);
        try {
            $custom = new CustomOrder();

            if($request->get('raw_from_customer')){
                if ($request->hasFile('raw_attachment')) {
                    $attachmentRawPath = $request->file('raw_attachment')->store('custom_orders/raw', 'public');
                    $custom->lampiran_bahan = $attachmentRawPath ?? null;
                }
                $custom->cek_bahan_dari_pelanggan = 1;
                $custom->nama_bahan = $request->get('raw_name');
                $custom->keterangan_bahan = $request->get('raw_description');
            }else{
                $custom->bahan_baku_id = $request->get('raw_material_id');
                $custom->cek_bahan_dari_pelanggan = 0;
            }

            $route = 'custom-order.index';
            if(auth()->user()->getRoleNames()[0] == 'pelanggan'){
                $custom->user_id = auth()->user()->id;
                $route = 'custom-order.my';
            }else{
                $custom->user_id = $request->get('customer_id') ?? auth()->user()->id;
            }

            $custom->produk_id = $request->get('product_id') ?? null;
            $custom->desain_id = $request->get('desain_id') ?? null;
            $custom->qty = $request->get('qty');
            $custom->deadline = $request->get('deadline');
            $custom->save();
            return redirect()->route($route)->with('success', 'Pesanan Khusus dibuat dengan kode '.$custom->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat Pesanan Khusus, Error: '.$e->getMessage());
        }
    }

    public function edit($id){
        $corder = CustomOrder::findOrFail($id);
        if($corder->status != '0'){
            return redirect()->back()->with('error', 'Pesanan Khusus tidak dapat diedit, hanya saat status menunggu harga untuk bisa diedit');
        }
        $customers = User::role('pelanggan')->get();
        $raw_materials = RawMaterial::where('cek_tersedia', 1)->orderBy('nama', 'asc')->get();
        $desains = Desain::orderBy('nama', 'asc')->get();
        $products = Product::orderBy('nama', 'asc')->get();
        return view('custom_orders.edit', compact('corder', 'raw_materials', 'products', 'desains', 'customers'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'raw_from_customer' => 'nullable|boolean',
            'raw_material_id' => 'nullable|string|exists:bahan_baku,id',
            'product_id' => 'nullable|string|exists:produk,id',
            'desain_id' => 'nullable|string|exists:desains,id',
            // Raw From Custome True
            'raw_name' => 'nullable|string',
            'raw_description' => 'nullable|string',
            'raw_attachment' => 'nullable|file|mimes:webp,png,jpeg,jpg,pdf,doc,ppt,pptx,jfif',

            // Default
            'qty' => 'required|numeric',
            'deadline' => 'required|date',
            'customer_id' => 'nullable|string|exists:users,id',
        ]);
        try {

            $custom = CustomOrder::findOrFail($id);
            if($custom->status != '0'){
                return redirect()->back()->with('error', 'Pesanan Khusus tidak dapat diedit, hanya saat status menunggu harga untuk bisa diedit');
            }
            // if ($request->hasFile('attachment')) {
            //     if ($custom->lampiran && file_exists(storage_path('app/public/' . $custom->lampiran))) {
            //         unlink(storage_path('app/public/' . $custom->lampiran));
            //     }
            //     $attachmentPath = $request->file('attachment')->store('custom_orders', 'public');
            //     $custom->lampiran = $attachmentPath ?? null;
            // }

            if($request->get('raw_from_customer')){
                if ($request->hasFile('raw_attachment')) {
                    if ($custom->lampiran_bahan && file_exists(storage_path('app/public/' . $custom->lampiran_bahan))) {
                        unlink(storage_path('app/public/' . $custom->lampiran_bahan));
                    }
                    $attachmentRawPath = $request->file('raw_attachment')->store('custom_orders/raw', 'public');
                    $custom->lampiran_bahan = $attachmentRawPath ?? null;
                }
                $custom->cek_bahan_dari_pelanggan = 1;
                $custom->nama_bahan = $request->get('raw_name');
                $custom->keterangan_bahan = $request->get('raw_description');
            }else{
                $custom->bahan_baku_id = $request->get('raw_material_id');
                $custom->cek_bahan_dari_pelanggan = 0;
            }

            $route = 'custom-order.index';
            if(auth()->user()->getRoleNames()[0] == 'pelanggan'){
                $custom->user_id = auth()->user()->id;
                $route = 'custom-order.my';
            }else{
                $custom->user_id = $request->get('customer_id') ?? auth()->user()->id;
            }

            $custom->produk_id = $request->get('product_id') ?? null;
            $custom->desain_id = $request->get('desain_id') ?? null;
            $custom->qty = $request->get('qty');
            $custom->deadline = $request->get('deadline');
            $custom->save();

            return redirect()->route($route)->with('success', 'Pesanan Khusus berhasil diperbaharui dengan kode '.$custom->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Pesanan Khusus, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $custom = CustomOrder::findOrFail($id);
            if($custom->status != '3' && $custom->status != '0'){
                return redirect()->back()->with('error', 'Pesanan Khusus tidak dapat dihapus, hanya pesanan yang menunggu harga atau ditolak yang dapat dihapus');
            }
            $custom->delete();
            return redirect()->back()->with('success', 'Pesanan Khusus '.$custom->code.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pesanan Khusus, Error: '.$e->getMessage());
        }
    }

    public function putPrice($id, Request $request){
        $request->merge([
            'harga' => parseRupiah($request->get('harga'))
        ]);
        $request->validate([
            'harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|min:4',
        ]);
        try {
            $custom = CustomOrder::findOrFail($id);
            if ( $custom->status != '0' && $custom->status != '2' && $custom->status != '3'){
                return redirect()->back()->withInput()->with('error', 'Status tidak valid, gagal menetapkan harga');
            }
            $custom->status = '1';
            $custom->total_harga = $request->get('harga');
            $custom->keterangan_konveksi = $request->get('keterangan');
            $custom->save();
            return redirect()->back()->with('success', 'Berhasil menetapkan harga untuk Pesanan Khusus '.$custom->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mentapkan harga Pesanan Khusus, Error: '.$e->getMessage());
        }
    }

    public function nego($id, Request $request){
        $request->validate([
            'keterangan' => 'nullable|string|min:4',
        ]);
        try {
            $custom = CustomOrder::findOrFail($id);
            if ( $custom->status != '1'){
                return redirect()->back()->withInput()->with('error', 'Status tidak valid, gagal negoisasi');
            }
            $custom->status = '2';
            $custom->keterangan_pelanggan = $request->get('keterangan');
            $custom->save();
            return redirect()->back()->with('success', 'Berhasil negoisasi harga untuk Pesanan Khusus '.$custom->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal negoisasi harga Pesanan Khusus, Error: '.$e->getMessage());
        }
    }

    public function rejected($id){
        try {
            $custom = CustomOrder::findOrFail($id);
            if ( $custom->status != '1'){
                return redirect()->back()->withInput()->with('error', 'Status tidak valid, hanya bisa saat Pesanan berstatus Menetapkan Harga');
            }
            $custom->status = '3';
            $custom->save();
            return redirect()->back()->with('success', 'Berhasil menolak harga untuk Pesanan Khusus '.$custom->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menolak harga Pesanan Khusus, Error: '.$e->getMessage());
        }
    }

    public function approve($id){
        try {
            $custom = CustomOrder::findOrFail($id);
            if ( $custom->status != '1'){
                return redirect()->back()->withInput()->with('error', 'Status tidak valid, hanya bisa saat Pesanan berstatus Menetapkan Harga');
            }
            $custom->status = '4';
            $custom->save();
            return redirect()->back()->with('success', 'Berhasil menyetujui harga untuk Pesanan Khusus '.$custom->code.'.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyetujui harga Pesanan Khusus, Error: '.$e->getMessage());
        }
    }

    public function retur($id, Request $request){
        $request->validate([
            'keterangan' => 'required|string|min:4',
        ]);
        $corder = CustomOrder::find($id);
        if($corder->status != '5' || $corder->retur){
            return redirect()->back()->withInput()->with('error', 'Pesanan '.$corder->code.' status tidak valid atau telah di retur dan ulas, pesanan tidak bisa di kembalikan');
        }
        try {
            $retur = new Retur();
            $retur->cek_pesanan_khusus = '1';
            $retur->pesanan_khusus_id = $id;
            $retur->user_id = auth()->user()->id;
            $retur->keterangan = $request->get('keterangan');
            $retur->save();

            $corder->status = '6'; //Pengembalian
            $corder->save();

            return redirect()->back()->with('success', 'Berhasil mengajukan Pengembalian pesanan dengan kode '.$corder->code);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengajukan Pengembalian pesanan, Error: '.$e->getMessage());
        }
    }
}
