<?php

namespace App\Http\Controllers;

use App\Models\LogisticAddress;
use Illuminate\Http\Request;

class LogisticAddressController extends Controller
{
    public function store(Request $request, $id){
        $request->merge([
            'logistic_id' => (string) $id,
        ]);
        $request->validate([
            'name' => 'required|string|min:3|unique:osano.logistic_addresses,name',
            'logistic_id' => 'required|string|exists:osano.logistics,id',
            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);
        try {
            $address = new LogisticAddress();
            $address->logistic_id = $request->get('logistic_id');
            $address->name = $request->get('name');
            $address->city = $request->get('city');
            $address->postal_code = $request->get('postal_code');
            $address->address = $request->get('address');
            $address->save();

            return redirect()->route('logistic.setting', $id)->with('success', 'Alamat logistic Pabrik / Lainnya dengan nama '.$address->name.' berhasil dibuat.')->with('redirect_hash', 'alamat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'alamat')->with('error', 'Gagal menambahkan alamat logistic, Error: '.$e->getMessage());
        }
    }

    public function update($id, $address_id, Request $request){
        $request->merge([
            'logistic_id' => (string) $id,
        ]);
        $request->validate([
            'name' => 'required|string|min:3|unique:osano.logistic_addresses,name,'.$address_id,
            'logistic_id' => 'required|string|exists:osano.logistics,id',
            'city' => 'required|string|exists:cities,city_name',
            'postal_code' => 'required|numeric|min:4',
            'address' => 'required|string|min:4',
        ]);
        try {
            $address = LogisticAddress::find($address_id);
            $address->logistic_id = $request->get('logistic_id');
            $address->name = $request->get('name');
            $address->city = $request->get('city');
            $address->postal_code = $request->get('postal_code');
            $address->address = $request->get('address');
            $address->save();

            return redirect()->route('logistic.setting', $id)->with('success', 'Alamat logistic Pabrik / Lainnya dengan nama '.$address->name.' berhasil diperbarui.')->with('redirect_hash', 'alamat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'alamat')->with('error', 'Gagal memperbarui alamat logistic, Error: '.$e->getMessage());
        }
    }

    public function destroy($id, $address_id){
        try {
            $address = LogisticAddress::find($address_id);
            $address->delete();

            return redirect()->route('logistic.setting', $id)->with('success', 'Alamat logistic Pabrik / Lainnya dengan nama '.$address->name.' berhasil dihapus.')->with('redirect_hash', 'alamat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('redirect_hash', 'alamat')->with('error', 'Gagal menghapus alamat logistic, Error: '.$e->getMessage());
        }
    }
}
