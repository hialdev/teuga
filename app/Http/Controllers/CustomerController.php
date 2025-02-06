<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
   public function index(Request $request)
    {
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'email',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $customers = Customer::query()
            ->where('nama', 'LIKE', '%' . $filter->q . '%')
            ->orWhere('email', 'LIKE', '%' . $filter->q . '%');

        if ($filter->field === 'orders') {
            $customers->withCount('orders')->orderBy('orders_count', $filter->order);
        } elseif ($filter->field === 'custom_orders') {
            $customers->withCount('customOrders')->orderBy('custom_orders_count', $filter->order);
        } else {
            $customers->orderBy($filter->field, $filter->order);
        }

        $customers = $customers->get();
        return view('customers.index', compact('customers', 'filter'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'nullable|string',
            'email' => 'required|string|email',
        ]);
        try {
            $user = new User();
            $user->name = $request->get('name') ?? explode('@', $request->get('email'))[0];
            $user->email = $request->get('email');
            $user->password = Hash::make($request->get('email'));
            $user->save();
            $user->assignRole('pelanggan');

            $customer = new Customer();
            $customer->nama = $request->get('name') ?? explode('@', $request->get('email'))[0];
            $customer->email = $request->get('email');
            $customer->user_id = $user->id;
            $customer->save();

            return redirect()->back()->with('success', 'Customer '.$customer->nama.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Customer, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $request->validate([
            'name' => 'nullable|string',
            'email' => 'required|string|email',
        ]);
        try {
            $customer = Customer::find($id);
            $customer->nama = $request->get('name');
            $customer->email = $request->get('email');
            $customer->save();
            
            $user = User::find($customer->id);
            $user->name = $customer->nama;
            $user->email = $customer->email;
            $user->save();

            return redirect()->route('customer.index')->with('success', 'Customer '.$customer->nama.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Customer, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $customer = Customer::find($id);
            if($customer->orders->count() > 0 || $customer->customOrders->count() > 0){
                return redirect()->back()->with('error', 'Gagal menghapus Customer, Customer '.$customer->nama.' memiliki Pesanan atau Pesanan Khusus, Hapus terlebih dahulu Semua pesanannya agar dapat menghapus Customer');
            }
            $customer->delete();

            return redirect()->route('customer.index')->with('success', 'Customer '.$customer->nama.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Customer, Error: '.$e->getMessage());
        }
    }
}
