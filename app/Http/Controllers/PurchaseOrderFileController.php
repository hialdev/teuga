<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderFile;
use Illuminate\Http\Request;

class PurchaseOrderFileController extends Controller
{
    public function store($id, Request $request){
        $request->merge(['purchase_order_id' => $id]);
        $request->validate([
            'purchase_order_id' => 'required|exists:osano.purchase_orders,id',
            'file' => 'nullable|file|mimes:webp,png,jpg,jpeg,jfif,pdf,doc,docx,pptx,ppt,xls,xlsx,zip,rar,csv,tiff,gif|max:10240',
            'name' => 'required|string',
        ]);
        try {
            $reqfile = new PurchaseOrderFile();
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('purchase-orders/files', 'public');
                $reqfile->file = $filePath;
            }
            $reqfile->purchase_order_id = $request->get('purchase_order_id');
            $reqfile->name = $request->get('name');
            
            $reqfile->save();

            return redirect()->route('purchase-order.setting', $id)->with('success', 'Lampiran '.$reqfile->name.' berhasil ditambahkan.')->with('redirect_hash', 'lampiran');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Lampiran, Error: '.$e->getMessage())->with('redirect_hash', 'lampiran');
        }
    }

    public function destroy($id, $file_id){
        try {
            $reqfile = PurchaseOrderFile::find($file_id);
            $reqfile->delete();

            return redirect()->route('purchase-order.setting', $id)->with('success', 'Lampiran '.$reqfile->name.' berhasil dihapus.')->with('redirect_hash', 'lampiran');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Lampiran, Error: '.$e->getMessage())->with('redirect_hash', 'lampiran');
        }
    }
}
