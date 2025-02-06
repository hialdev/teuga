<?php

namespace App\Http\Controllers;

use App\Models\RequestOrderFile;
use Illuminate\Http\Request;

class RequestOrderFileController extends Controller
{
    public function store($id, Request $request){
        $request->merge(['request_order_id' => $id]);
        $request->validate([
            'request_order_id' => 'required|exists:osano.request_orders,id',
            'file' => 'nullable|file|mimes:webp,png,jpg,jpeg,jfif,pdf,doc,docx,pptx,ppt,xls,xlsx,zip,rar,csv,tiff,gif|max:10240',
            'name' => 'required|string',
        ]);
        try {
            $reqfile = new RequestOrderFile();
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('request-orders/files', 'public');
                $reqfile->file = $filePath;
            }
            $reqfile->request_order_id = $request->get('request_order_id');
            $reqfile->name = $request->get('name');
            
            $reqfile->save();

            return redirect()->route('request-order.setting', $id)->with('success', 'Lampiran '.$reqfile->name.' berhasil ditambahkan.')->with('redirect_hash', 'lampiran');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Lampiran, Error: '.$e->getMessage())->with('redirect_hash', 'lampiran');
        }
    }

    public function destroy($id, $file_id){
        try {
            $reqfile = RequestOrderFile::find($file_id);
            $reqfile->delete();

            return redirect()->route('request-order.setting', $id)->with('success', 'Lampiran '.$reqfile->name.' berhasil dihapus.')->with('redirect_hash', 'lampiran');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Lampiran, Error: '.$e->getMessage())->with('redirect_hash', 'lampiran');
        }
    }
}
