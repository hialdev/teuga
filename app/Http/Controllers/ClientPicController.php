<?php

namespace App\Http\Controllers;

use App\Models\ClientPic;
use Illuminate\Http\Request;

class ClientPicController extends Controller
{
    public function store(Request $request, $id){
        $request->merge([
            'client_id' => (string) $id,
        ]);
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'client_id' => 'required|string|exists:osano.clients,id',
            'parent_pic_id' => 'nullable|string|exists:osano.client_pics,id',
            'name' => 'required|string|min:3|unique:osano.client_pics,name',
            'email' => 'nullable|email|min:4',
            'phone' => 'nullable|numeric|min:4',
            'description' => 'nullable|string|min:4',
        ]);
        try {
            $pic = new ClientPic();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('clients/pics', 'public');
                $pic->image = $imagePath;
            }
            $pic->client_id = $request->get('client_id');
            $pic->name = $request->get('name');
            $pic->email = $request->get('email');
            $pic->phone = $request->get('phone');
            $pic->description = $request->get('description');
            $pic->parent_pic_id = $request->get('parent_pic_id');
            $pic->save();

            return redirect()->route('client.setting', $id)->with('success', 'PIC client dengan nama '.$pic->name.' berhasil dibuat.')->with('redirect_hash', 'pic');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'pic')->with('error', 'Gagal menambahkan PIC atau Sales client, Error: '.$e->getMessage());
        }
    }

    public function update($id, $pic_id, Request $request){
        $request->merge([
            'client_id' => (string) $id,
        ]);
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'client_id' => 'required|string|exists:osano.clients,id',
            'parent_pic_id' => 'nullable|string|exists:osano.client_pics,id',
            'name' => 'required|string|min:3|unique:osano.client_pics,name,'.$pic_id,
            'email' => 'nullable|email|min:4',
            'phone' => 'nullable|numeric|min:4',
            'description' => 'nullable|string|min:4',
        ]);
        try {
            $pic = ClientPic::find($pic_id);
            if ($request->hasFile('image')) {
                if ($pic->image && file_exists(storage_path('app/public/' . $pic->image))) {
                    unlink(storage_path('app/public/' . $pic->image));
                }
                $imagePath = $request->file('image')->store('clients/pics', 'public');
                $pic->image = $imagePath;
            }
            $pic->client_id = $request->get('client_id');
            $pic->name = $request->get('name');
            $pic->email = $request->get('email');
            $pic->phone = $request->get('phone');
            $pic->description = $request->get('description');
            $pic->parent_pic_id = $request->get('parent_pic_id');
            $pic->save();

            return redirect()->route('client.setting', $id)->with('success', 'PIC client dengan nama '.$pic->name.' berhasil diperbarui.')->with('redirect_hash', 'pic');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'pic')->with('error', 'Gagal memperbarui PIC atau Sales client, Error: '.$e->getMessage());
        }
    }

    public function destroy($id, $pic_id){
        try {
            $pic = ClientPic::find($pic_id);
            $pic->delete();

            return redirect()->route('client.setting', $id)->with('success', 'PIC client dengan nama '.$pic->name.' berhasil dihapus.')->with('redirect_hash', 'pic');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'pic')->with('error', 'Gagal menghapus PIC atau Sales client, Error: '.$e->getMessage());
        }
    }
}
