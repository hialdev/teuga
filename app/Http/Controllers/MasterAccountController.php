<?php

namespace App\Http\Controllers;

use App\Models\MasterAccount;
use Illuminate\Http\Request;

class MasterAccountController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'code',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $masters = MasterAccount::query()
            ->where('code', 'LIKE', '%' . $filter->q . '%');
            $masters->orderBy($filter->field, $filter->order);

        $masters = $masters->get();
        return view('master_accounts.index', compact('masters', 'filter'));
    }

    public function store(Request $request){
        $request->validate([
            'type' => 'required|in:'.implode(',', config('al.account_types', [])).'|unique:accounting.master_accounts,type',
            'code' => 'required|string|min:1|unique:accounting.master_accounts,code',
        ]);
        try {

            $master = new MasterAccount();
            $master->type = $request->get('type');
            $master->code = $request->get('code');
            $master->save();

            return redirect()->back()->with('success', 'Master Account '.$master->code.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Master Account, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $master = MasterAccount::find($id);
        $request->validate([
            'type' => 'required|in:'.implode(',', config('al.account_types', [])).'|unique:accounting.master_accounts,type,'.$id,
            'code' => 'required|string|min:1|unique:accounting.master_accounts,code,'.$id,
        ]);
        try {
            $master->type = $request->get('type');
            $master->code = $request->get('code');
            $master->save();

            return redirect()->route('master-account.index')->with('success', 'Master Account '.$master->code.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Master Account, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $master = MasterAccount::find($id);
            if($master->status != '0') return redirect()->back()->withInput()->with('error', 'Gagal menghapus Master Account, Error: Pembelian dan Master Account telah diproses.');
            $master->delete();

            return redirect()->back()->with('success', 'Master Account '.$master->code.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Master Account, Error: '.$e->getMessage());
        }
    }
}
