<?php

namespace App\Http\Controllers;

use App\Models\Desain;
use Illuminate\Http\Request;

class DesainController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];
        $desains = Desain::where('nama', 'LIKE', '%'.$filter->q.'%')->orWhere('keterangan', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        if(auth()->user()->getRoleNames()[0] == 'pelanggan'){
            $desains = Desain::where('user_id', auth()->user()->id)->where('nama', 'LIKE', '%'.$filter->q.'%')->orWhere('keterangan', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        }

        return view('desain.index', compact('desains', 'filter'));
    }

    public function store(Request $request){
        $request->validate([
            'lampiran' => 'required|file|mimes:webp,png,jpg,jpeg,svg,docx,doc,pdf,ppt,pptx|max:2048',
            'nama' => 'required|string|min:3',
            'keterangan' => 'required|string',
            'customer_id' => 'nullable|string|exists:users,id',
        ]);
        try {
            $desain = new Desain();
            if ($request->hasFile('lampiran')){
                $lampiranPath = $request->file('lampiran')->store('desains', 'public');
                $desain->lampiran = $lampiranPath;
            }
            $desain->nama = $request->get('nama');
            $desain->user_id = auth()->user()->id;
            $desain->keterangan = $request->get('keterangan');
            $desain->save();

            return redirect()->back()->with('success', 'Desain '.$desain->nama.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Desain, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $request->validate([
            'lampiran' => 'nullable|file|mimes:webp,png,jpg,jpeg,svg,docx,doc,pdf,ppt,pptx|max:2048',
            'nama' => 'required|string|min:3',
            'keterangan' => 'required|string',
        ]);
        try {
            $desain = Desain::find($id);
            if ($request->hasFile('lampiran')) {
                if ($desain->lampiran && file_exists(storage_path('app/public/' . $desain->lampiran))) {
                    unlink(storage_path('app/public/' . $desain->lampiran));
                }
                $lampiranPath = $request->file('lampiran')->store('desains', 'public');
                $desain->lampiran = $lampiranPath;
            }
            $desain->nama = $request->get('nama');
            $desain->keterangan = $request->get('keterangan');
            $desain->save();

            return redirect()->route('desain.index')->with('success', 'Desain '.$desain->nama.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Desain, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $desain = Desain::find($id);
            $desain->delete();

            return redirect()->route('desain.index')->with('success', 'Desain '.$desain->nama.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui desain, Error: '.$e->getMessage());
        }
    }
}
