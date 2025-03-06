<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Principal;
use App\Models\PrincipalPic;
use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'name',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $principals = Principal::where('name', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        return view('principals.index', compact('principals', 'filter'));
    }

    public function add(){
        $cities = City::orderBy('city_name', 'asc')->get();
        return view('principals.add', compact('cities'));
    }

    public function store(Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.principals,name',
            'npwp' => 'nullable|numeric|digits_between:15,16',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
            'fax' => 'nullable|numeric',
            'description' => 'nullable|string',

            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);
        try {
            $principal = new Principal();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('principals', 'public');
                $principal->image = $imagePath;
            }
            $principal->name = $request->get('name');
            $principal->npwp = $request->get('npwp');
            $principal->email = $request->get('email');
            $principal->phone = $request->get('phone');
            $principal->fax = $request->get('fax');
            $principal->description = $request->get('description');
            
            $principal->city = $request->get('city');
            $principal->postal_code = $request->get('postal_code');
            $principal->address = $request->get('address');
            $principal->save();

            return redirect()->route('principal.setting', $principal->id)->with('success', 'Principal '.$principal->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan principal, Error: '.$e->getMessage());
        }
    }

    public function setting($id){
        $principal = Principal::find($id);
        $cities = City::orderBy('city_name', 'asc')->get();
        $pics = PrincipalPic::where('principal_id', $id)->get();
        return view('principals.setting', compact('principal', 'cities', 'pics'));
    }
    
    public function update($id, Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.principals,name,'.$id,
            'npwp' => 'nullable|numeric|digits_between:15,16',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
            'fax' => 'nullable|numeric',
            'description' => 'nullable|string',

            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);

        try {
            $principal = Principal::find($id);
            if ($request->hasFile('image')) {
                if ($principal->image && file_exists(storage_path('app/public/' . $principal->image))) {
                    unlink(storage_path('app/public/' . $principal->image));
                }
                $imagePath = $request->file('image')->store('principals', 'public');
                $principal->image = $imagePath;
            }
            $principal->name = $request->get('name');
            $principal->npwp = $request->get('npwp');
            $principal->email = $request->get('email');
            $principal->phone = $request->get('phone');
            $principal->fax = $request->get('fax');
            $principal->description = $request->get('description');
            
            $principal->city = $request->get('city');
            $principal->postal_code = $request->get('postal_code');
            $principal->address = $request->get('address');
            $principal->save();


            return redirect()->route('principal.index')->with('success', 'Principal '.$principal->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui principal, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $principal = Principal::find($id);
            $principal->delete();

            return redirect()->route('principal.index')->with('success', 'Principal '.$principal->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus principal, Error: '.$e->getMessage());
        }
    }
}
