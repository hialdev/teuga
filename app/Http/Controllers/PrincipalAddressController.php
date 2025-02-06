<?php

namespace App\Http\Controllers;

use App\Models\PrincipalAddress;
use Illuminate\Http\Request;

class PrincipalAddressController extends Controller
{
    public function store(Request $request, $id){
        $request->merge([
            'principal_id' => (string) $id,
        ]);
        $request->validate([
            'name' => 'required|string|min:3|unique:osano.principal_addresses,name',
            'principal_id' => 'required|string|exists:osano.principals,id',
            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);
        try {
            $address = new PrincipalAddress();
            $address->principal_id = $request->get('principal_id');
            $address->name = $request->get('name');
            $address->city = $request->get('city');
            $address->postal_code = $request->get('postal_code');
            $address->address = $request->get('address');
            $address->save();

            return redirect()->route('principal.setting', $id)->with('success', 'Alamat Principal Pabrik / Lainnya dengan nama '.$address->name.' berhasil dibuat.')->with('redirect_hash', 'alamat-pabrik');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'alamat-pabrik')->with('error', 'Gagal menambahkan alamat principal, Error: '.$e->getMessage());
        }
    }

    public function update($id, $address_id, Request $request){
        $request->merge([
            'principal_id' => (string) $id,
        ]);
        $request->validate([
            'name' => 'required|string|min:3|unique:osano.principal_addresses,name,'.$address_id,
            'principal_id' => 'required|string|exists:osano.principals,id',
            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);
        try {
            $address = PrincipalAddress::find($address_id);
            $address->principal_id = $request->get('principal_id');
            $address->name = $request->get('name');
            $address->city = $request->get('city');
            $address->postal_code = $request->get('postal_code');
            $address->address = $request->get('address');
            $address->save();

            return redirect()->route('principal.setting', $id)->with('success', 'Alamat Principal Pabrik / Lainnya dengan nama '.$address->name.' berhasil diperbarui.')->with('redirect_hash', 'alamat-pabrik');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'alamat-pabrik')->with('error', 'Gagal memperbarui alamat principal, Error: '.$e->getMessage());
        }
    }

    public function destroy($id, $address_id){
        try {
            $address = PrincipalAddress::find($address_id);
            $address->delete();

            return redirect()->route('principal.setting', $id)->with('success', 'Alamat Principal Pabrik / Lainnya dengan nama '.$address->name.' berhasil dihapus.')->with('redirect_hash', 'alamat-pabrik');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'alamat-pabrik')->with('error', 'Gagal menghapus alamat principal, Error: '.$e->getMessage());
        }
    }
}
