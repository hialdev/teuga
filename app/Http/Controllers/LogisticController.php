<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Logistic;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'name',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $logistics = Logistic::where('name', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        return view('logistics.index', compact('logistics', 'filter'));
    }

    public function add(){
        $cities = City::orderBy('city_name', 'asc')->get();
        return view('logistics.add', compact('cities'));
    }

    public function store(Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.logistics,name',
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
            $logistic = new Logistic();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('logistics', 'public');
                $logistic->image = $imagePath;
            }
            $logistic->name = $request->get('name');
            $logistic->npwp = $request->get('npwp');
            $logistic->email = $request->get('email');
            $logistic->phone = $request->get('phone');
            $logistic->fax = $request->get('fax');
            $logistic->description = $request->get('description');
            
            $logistic->city = $request->get('city');
            $logistic->postal_code = $request->get('postal_code');
            $logistic->address = $request->get('address');
            $logistic->save();

            return redirect()->route('logistic.setting', $logistic->id)->with('success', 'Logistik '.$logistic->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan logistic, Error: '.$e->getMessage());
        }
    }

    public function setting($id){
        $logistic = Logistic::find($id);
        $cities = City::orderBy('city_name', 'asc')->get();
        return view('logistics.setting', compact('logistic', 'cities'));
    }
    
    public function update($id, Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.logistics,name',
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
            $logistic = Logistic::find($id);
            if ($request->hasFile('image')) {
                if ($logistic->image && file_exists(storage_path('app/public/' . $logistic->image))) {
                    unlink(storage_path('app/public/' . $logistic->image));
                }
                $imagePath = $request->file('image')->store('logistics', 'public');
                $logistic->image = $imagePath;
            }
            $logistic->name = $request->get('name');
            $logistic->email = $request->get('email');
            $logistic->npwp = $request->get('npwp');
            $logistic->phone = $request->get('phone');
            $logistic->fax = $request->get('fax');
            $logistic->description = $request->get('description');
            
            $logistic->city = $request->get('city');
            $logistic->postal_code = $request->get('postal_code');
            $logistic->address = $request->get('address');
            $logistic->save();


            return redirect()->route('logistic.index')->with('success', 'Logistik '.$logistic->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui logistic, Error: '.$e->getMessage());
        }
    }

    public function cp($id, Request $request){
        $request->merge([
            'logistic_id' => $id,
        ]);
        $request->validate([
            'logistic_id' => 'required|string|exists:osano.logistics,id',
            'cp_name' => 'required|string|min:3',
            'cp_email' => 'nullable|email',
            'cp_phone' => 'nullable|numeric',
        ]);
        try {
            $logistic = Logistic::find($id);
            $logistic->cp_name = $request->get('cp_name');
            $logistic->cp_email = $request->get('cp_email');
            $logistic->cp_phone = $request->get('cp_phone');
            $logistic->save();

            return redirect()->route('logistic.setting', $logistic->id)->with('success', 'Narahubung Logistik '.$logistic->name.' berhasil diperbarui.')->with('redirect_hash', 'narahubung');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan logistic, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $logistic = Logistic::find($id);
            $logistic->delete();

            return redirect()->route('logistic.index')->with('success', 'Logistik '.$logistic->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus logistic, Error: '.$e->getMessage());
        }
    }
}
