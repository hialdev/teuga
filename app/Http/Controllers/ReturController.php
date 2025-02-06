<?php

namespace App\Http\Controllers;

use App\Models\CustomOrder;
use App\Models\Order;
use App\Models\Retur;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReturController extends Controller
{
    public function approve($id, Request $request){
        $request->validate([
            'lampiran' => 'required|file|mimes:png,jpeg,jpg,jfif,webp,docx,doc,pptx,ppt,pdf|max:10240',
            'keterangan' => 'nullable|string',
        ]);
        try {
            $retur = Retur::find($id);
            if ($request->hasFile('lampiran')) {
                if ($retur->lampiran_aksi && file_exists(storage_path('app/public/' . $retur->lampiran_aksi))) {
                    unlink(storage_path('app/public/' . $retur->lampiran_aksi));
                }
                $lampiranPath = $request->file('lampiran')->store('orders/returs', 'public');
                $retur->lampiran_aksi = $lampiranPath;
            }
            $retur->keterangan_aksi = $request->get('keterangan');
            $retur->aksi_timestamp = Carbon::now();
            $retur->status = '2';
            $retur->save();
            $code = null;
            if($retur->cek_pesanan_khusus){
                $custom = CustomOrder::find($retur->pesanan_khusus_id);
                $custom->status = '5';
                $code = $custom->code;
                $custom->save();
            }else{
                $order = Order::find($retur->pesanan_id);
                $order->status = '4';
                $code = $order->code;
                $order->save();
            }

            return redirect()->back()->with('success','Berhasil mengkonfirmasi penyelesaian untuk Retur pada Pesanan dengan Kode '.$code);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal konfirmasi pengembalian, Error: '.$e->getMessage());
        }
    }

    public function rejected($id, Request $request){
        $request->validate([
            'lampiran' => 'required|file|mimes:png,jpeg,jpg,jfif,webp,docx,doc,pptx,ppt,pdf|max:10240',
            'keterangan' => 'nullable|string',
        ]);
        try {
            $retur = Retur::find($id);
            if ($request->hasFile('lampiran')) {
                if ($retur->lampiran_aksi && file_exists(storage_path('app/public/' . $retur->lampiran_aksi))) {
                    unlink(storage_path('app/public/' . $retur->lampiran_aksi));
                }
                $lampiranPath = $request->file('lampiran')->store('orders/returs', 'public');
                $retur->lampiran_aksi = $lampiranPath;
            }
            $retur->keterangan_aksi = $request->get('keterangan');
            $retur->aksi_timestamp = Carbon::now();
            $retur->status = '1';
            $retur->save();
            $code = null;
            if($retur->cek_pesanan_khusus){
                $custom = CustomOrder::find($retur->pesanan_khusus_id);
                $custom->status = '5';
                $code = $custom->code;
                $custom->save();
            }else{
                $order = Order::find($retur->pesanan_id);
                $order->status = '4';
                $code = $order->code;
                $order->save();
            }

            return redirect()->back()->with('success','Pengembalian pada Pesanan '.$code.' ditolak');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak pengembalian, Error: '.$e->getMessage());
        }
    }
}
