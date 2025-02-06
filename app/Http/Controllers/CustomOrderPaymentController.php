<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\CustomOrder;
use App\Models\CustomOrderPayment;
use Illuminate\Http\Request;

class CustomOrderPaymentController extends Controller
{

    public function index($id){
        $custom = CustomOrder::find($id);
        $banks = Bank::orderBy('nama_bank', 'asc')->get();
        return view('custom_orders.payment.index', compact('custom', 'banks'));
    }

    public function store(Request $request, $id){
        $custom = CustomOrder::find($id);
        $request->merge([
            'total_dibayar' => parseRupiah($request->get('total_dibayar'))
        ]);
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:png,webp,jpg,jpeg,jfif,pdf,docx,doc,pptx|max:10240',
            'total_dibayar' => 'required|numeric|min:0|max:'.$custom->remaining_payment,
        ]);
        try {
            $pay = new CustomOrderPayment();
            if ($request->hasFile('bukti_pembayaran')) {
                if ($pay->bukti_pembayaran && file_exists(storage_path('app/public/' . $pay->bukti_pembayaran))) {
                    unlink(storage_path('app/public/' . $pay->bukti_pembayaran));
                }
                $bukti_pembayaranPath = $request->file('bukti_pembayaran')->store('custom_orders/pay', 'public');
                $pay->bukti_pembayaran = $bukti_pembayaranPath;
            }
            $pay->pesanan_khusus_id = $id;
            $pay->total_dibayar = $request->get('total_dibayar');
            $pay->save();

            return redirect()->route('custom-order.payment.index', $id)->with('success', 'Pembayaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus pembayaran, Error: '.$e->getMessage());
        }
    }

    public function update(Request $request, $id, $pay_id){
        $custom = CustomOrder::find($id);
        $request->merge([
            'total_dibayar' => parseRupiah($request->get('total_dibayar'))
        ]);
        $request->validate([
            'bukti_pembayaran' => 'nullable|file|mimes:png,webp,jpg,jpeg,jfif,pdf,docx,doc,pptx|max:10240',
            'total_dibayar' => 'required|numeric|min:0|max:'.$custom->remaining_payment,
        ]);
        try {
            $pay = CustomOrderPayment::find($pay_id);
            if($pay->status == 2){
                return redirect()->back()->withInput()->with('error', 'Pembayaran telah diverifikasi, tidak dapat diubah');
            }
            if ($request->hasFile('bukti_pembayaran')) {
                if ($pay->bukti_pembayaran && file_exists(storage_path('app/public/' . $pay->bukti_pembayaran))) {
                    unlink(storage_path('app/public/' . $pay->bukti_pembayaran));
                }
                $bukti_pembayaranPath = $request->file('bukti_pembayaran')->store('custom_orders/pay', 'public');
                $pay->bukti_pembayaran = $bukti_pembayaranPath;
            }
            $pay->status = '0';
            $pay->total_dibayar = $request->get('total_dibayar');
            $pay->save();
            return redirect()->route('custom-order.payment.index', $id)->with('success', 'Pembayaran berhasil diperbaharui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pembayaran, Error: '.$e->getMessage());
        }
    }

    public function destroy($id, $pay_id){
        try {
            $pay = CustomOrderPayment::find($pay_id);
            $pay->delete();
            return redirect()->route('custom-order.payment.index', $id)->with('success', 'Pembayaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus pembayaran, Error: '.$e->getMessage());
        }
    }

    public function approve($id, $pay_id){
        $custom = CustomOrder::findOrFail($id);
        try {
            $pay = CustomOrderPayment::find($pay_id);
            if($pay->status != '0' || $pay->total_dibayar > $custom->remaining_payment){
                return redirect()->back()->withInput()->with('error', 'Gagal memverifikasi pembayaran, Nilai total pembayaran melebihi sisa pembayaran dan hanya Pembayaran berstatus menunggu konfirmasi (0) yang dapat diproses');
            }
            $pay->status = '2';
            $pay->save();

            if ($custom->remaining_payment == 0 && $custom->payments->count() > 0 ){
                $custom->status = '5';
                $custom->save();
            }

            return redirect()->route('custom-order.payment.index', $id)->with('success', 'Pembayaran berhasil diverifikasi');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memverifikasi pembayaran, Error: '.$e->getMessage());
        }
    }

    public function rejected($id, $pay_id){
        try {
            $pay = CustomOrderPayment::find($pay_id);
            if($pay->status != '0'){
                return redirect()->back()->withInput()->with('error', 'Gagal menetapkan tidak sah pembayaran, hanya Pembayaran berstatus menunggu konfirmasi (0) yang dapat diproses');
            }
            $pay->status = '1';
            $pay->save();
            return redirect()->route('custom-order.payment.index', $id)->with('success', 'Pembayaran berhasil ditetapkan sebagai tidak sah');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menetapkan tidak sah pembayaran, Error: '.$e->getMessage());
        }
    }
}
