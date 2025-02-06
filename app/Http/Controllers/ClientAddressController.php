<?php

namespace App\Http\Controllers;

use App\Models\ClientAddress;
use Illuminate\Http\Request;

class ClientAddressController extends Controller
{
    public function store(Request $request, $id){
        $request->merge([
            'client_id' => (string) $id,
        ]);
        $request->validate([
            'name' => 'required|string|min:3|unique:osano.client_addresses,name',
            'client_id' => 'required|string|exists:osano.clients,id',
            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);
        try {
            $address = new ClientAddress();
            $address->client_id = $request->get('client_id');
            $address->name = $request->get('name');
            $address->city = $request->get('city');
            $address->postal_code = $request->get('postal_code');
            $address->address = $request->get('address');
            $address->save();

            return redirect()->route('client.setting', $id)->with('success', 'Alamat Client Proyek / Lainnya dengan nama '.$address->name.' berhasil dibuat.')->with('redirect_hash', 'alamat-proyek');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'alamat-proyek')->with('error', 'Gagal menambahkan alamat client, Error: '.$e->getMessage());
        }
    }

    public function update($id, $address_id, Request $request){
        $request->merge([
            'client_id' => (string) $id,
        ]);
        $request->validate([
            'name' => 'required|string|min:3|unique:osano.client_addresses,name,'.$address_id,
            'client_id' => 'required|string|exists:osano.clients,id',
            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);
        try {
            $address = ClientAddress::find($address_id);
            $address->client_id = $request->get('client_id');
            $address->name = $request->get('name');
            $address->city = $request->get('city');
            $address->postal_code = $request->get('postal_code');
            $address->address = $request->get('address');
            $address->save();

            return redirect()->route('client.setting', $id)->with('success', 'Alamat Client Proyek / Lainnya dengan nama '.$address->name.' berhasil diperbarui.')->with('redirect_hash', 'alamat-proyek');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'alamat-proyek')->with('error', 'Gagal memperbarui alamat client, Error: '.$e->getMessage());
        }
    }

    public function destroy($id, $address_id){
        try {
            $address = ClientAddress::find($address_id);
            $address->delete();

            return redirect()->route('client.setting', $id)->with('success', 'Alamat Client Proyek / Lainnya dengan nama '.$address->name.' berhasil dihapus.')->with('redirect_hash', 'alamat-proyek');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'alamat-proyek')->with('error', 'Gagal menghapus alamat client, Error: '.$e->getMessage());
        }
    }
}
