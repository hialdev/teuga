<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\MasterAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'full_code',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $accounts = Account::query()
            ->where('code', 'LIKE', '%' . $filter->q . '%')
            ->orWhere('account_name', 'LIKE', '%' . $filter->q . '%')
            ->get();

        if ($filter->field === 'full_code') {
            $accounts = $filter->order === 'asc'
                ? $accounts->sortBy('full_code')
                : $accounts->sortByDesc('full_code');
        } else {
            $accounts = $accounts->sortBy($filter->field, SORT_REGULAR, $filter->order === 'desc');
        }

        $masters = MasterAccount::orderBy('code', 'asc')->get();
        $parents = Account::whereNull('parent_account_id')->get()->sortBy('full_code');

        return view('coa.index', compact('accounts', 'filter', 'masters', 'parents'));
    }


    public function store(Request $request){
        $request->validate([
            'master_account_id' => 'required|exists:accounting.master_accounts,id',
            'parent_account_id' => 'nullable|exists:accounting.accounts,id',
            'account_name' => 'required|string',
            'description' => 'nullable|string',
            'is_logical' => 'nullable|boolean',
        ]);
        try {
            $master = MasterAccount::find($request->get('master_account_id'));
            $parent = Account::find($request->get('parent_account_id'));
            if($parent && $master->code != $parent->master->code){
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Account, Error: Parent Account tidak sesuai dengan Master Account');
            }

            $account = new Account();
            $account->master_account_id = $request->get('master_account_id');
            $account->parent_account_id = $request->get('parent_account_id');
            $account->account_name = $request->get('account_name');
            $account->description = $request->get('description');
            $account->is_logical = (int) $request->get('is_logical', 0);
            $account->save();

            return redirect()->back()->with('success', 'Account '.$account->account_name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Account, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $account = Account::find($id);
        $request->validate([
            'master_account_id' => 'required|exists:accounting.master_accounts,id',
            'parent_account_id' => 'nullable|exists:accounting.accounts,id',
            'account_name' => 'required|string',
            'description' => 'nullable|string',
            'is_logical' => 'nullable|boolean',
        ]);
        try {
            $master = MasterAccount::find($request->get('master_account_id'));
            $parent = Account::find($request->get('parent_account_id'));
            if($parent && $master->code != $parent->master->code){
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Account, Error: Parent Account tidak sesuai dengan Master Account');
            }

            if(($request->get('master_account_id') != $account->master_account_id || $request->get('parent_account_id') != $account->parent_account_id) && $account->is_logical){
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Account, Error: Tidak dapat Mengubah Master dan Parent karena Account ini adalah Account Logical Sistem');
            }
            
            $checkGenerated = $account->transactions()
                ->whereHas('transaction', function ($query) {
                    $query->where('is_generated', 1);
                })
                ->first();
            if ($checkGenerated) {
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Account, Error: Tidak dapat mengubah account, Akun ini dibuat dari transaksi otomatis');
            }


            if(!$account->is_logical){
                if($request->get('master_account_id')){
                    if($request->get('parent_account_id') && $request->get('parent_account_id') != $account->parent_account_id){
                        $account->code = $account->getChildCode($request->get('master_account_id'), $request->get('parent_account_id'));
                    }
                }else{
                    return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Account, Error: Master Account harus diisi');
                }

                $account->master_account_id = $request->get('master_account_id', $account->master_account_id);
                $account->parent_account_id = $request->get('parent_account_id', $account->parent_account_id);
                $account->is_logical = $request->get('is_logical', 0);
            }
            
            if(Auth::user()->getRoleNames()[0] == 'developer'){
                $account->account_name = $request->get('account_name', $account->account_name);
            }
            
            $account->description = $request->get('description', $account->description);
            $account->save();

            return redirect()->back()->with('success', 'Account '.$account->account_name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Account, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $account = Account::find($id);
            if($account->is_logical || $account->transactions->count() > 0){
                return redirect()->back()->withInput()->with('error', 'Gagal menghapus Account, Error: Account diperlukan untuk sistem atau digunakan dalam Jurnal Transaksi');
            }
            $account->delete();

            return redirect()->back()->with('success', 'Account '.$account->account_name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Account, Error: '.$e->getMessage());
        }
    }
}
