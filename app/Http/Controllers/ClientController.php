<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Client;
use App\Models\ClientPic;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'name',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $clients = Client::where('name', 'LIKE', '%'.$filter->q.'%')->orderBy($filter->field, $filter->order)->get();
        return view('clients.index', compact('clients', 'filter'));
    }

    public function add(){
        $cities = City::orderBy('city_name', 'asc')->get();
        return view('clients.add', compact('cities'));
    }

    public function store(Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.clients,name',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
            'fax' => 'nullable|numeric',
            'description' => 'nullable|string',

            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);
        try {
            $client = new Client();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('clients', 'public');
                $client->image = $imagePath;
            }
            $client->name = $request->get('name');
            $client->email = $request->get('email');
            $client->phone = $request->get('phone');
            $client->fax = $request->get('fax');
            $client->description = $request->get('description');
            
            $client->city = $request->get('city');
            $client->postal_code = $request->get('postal_code');
            $client->address = $request->get('address');
            $client->save();

            return redirect()->route('client.setting', $client->id)->with('success', 'Client '.$client->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan client, Error: '.$e->getMessage());
        }
    }

    public function setting($id){
        $client = Client::find($id);
        $cities = City::orderBy('city_name', 'asc')->get();
        $pics = ClientPic::where('client_id', $id)->get();
        return view('clients.setting', compact('client', 'cities', 'pics'));
    }
    
    public function update($id, Request $request){
        $request->validate([
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg,jfif,svg|max:2048',
            'name' => 'required|string|min:3|unique:osano.clients,name,'.$id,
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric',
            'fax' => 'nullable|numeric',
            'description' => 'nullable|string',

            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);

        try {
            $client = Client::find($id);
            if ($request->hasFile('image')) {
                if ($client->image && file_exists(storage_path('app/public/' . $client->image))) {
                    unlink(storage_path('app/public/' . $client->image));
                }
                $imagePath = $request->file('image')->store('clients', 'public');
                $client->image = $imagePath;
            }
            $client->name = $request->get('name');
            $client->email = $request->get('email');
            $client->phone = $request->get('phone');
            $client->fax = $request->get('fax');
            $client->description = $request->get('description');
            
            $client->city = $request->get('city');
            $client->postal_code = $request->get('postal_code');
            $client->address = $request->get('address');
            $client->save();


            return redirect()->route('client.index')->with('success', 'Client '.$client->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui client, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $client = Client::find($id);
            $client->delete();

            return redirect()->route('client.index')->with('success', 'Client '.$client->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus client, Error: '.$e->getMessage());
        }
    }
}
