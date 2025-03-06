<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'created_at',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $banks = Bank::where('bank_name', 'LIKE', '%'.$filter->q.'%')
                                ->orWhere('account_name', 'LIKE', '%'.$filter->q.'%')
                                ->orWhere('account_number', 'LIKE', '%'.$filter->q.'%')
                                ->orderBy($filter->field, $filter->order)->get();
        return view('banks.index', compact('banks', 'filter'));
    }

    public function add(){
        return view('banks.add');
    }

    public function store(Request $request){
        $request->validate([
            'logo' => 'nullable|image|mimes:webp,png,jpg,jpeg,svg|max:2048',
            'account_id' => 'nullable|string|exists:accounting.accounts,id',
            'nama_bank' => 'required|string|min:3',
            'nama_rekening' => 'required|string|min:3',
            'keterangan' => 'nullable|string',
        ]);
        try {
            $bank = new Bank();
            if ($request->hasFile('logo')) {
                $imagePath = $request->file('logo')->store('banks', 'public');
                $bank->logo = $imagePath;
            }

            $bank->bank_name = $request->get('nama_bank');
            $bank->account_id = $request->get('account_id');
            $bank->account_name = $request->get('nama_rekening');
            $bank->account_number = $request->get('no_rekening');
            $bank->description = $request->get('keterangan');
            $bank->save();

            return redirect()->route('bank.index')->with('success', 'Bank '.$bank->bank_name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Bank, Error: '.$e->getMessage());
        }
    }

    public function edit($id){
        $bank = Bank::find($id);
        return view('banks.edit', compact('bank'));
    }

    public function update($id, Request $request){
        $request->validate([
            'logo' => 'nullable|image|mimes:webp,png,jpg,jpeg,svg|max:2048',
            'account_id' => 'nullable|string|exists:accounting.accounts,id',
            'nama_bank' => 'required|string|min:3',
            'nama_rekening' => 'required|string|min:3',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $bank = Bank::find($id);
            if ($request->hasFile('logo')) {
                if ($bank->logo && file_exists(storage_path('app/public/' . $bank->logo))) {
                    unlink(storage_path('app/public/' . $bank->logo));
                }
                $imagePath = $request->file('logo')->store('banks', 'public');
                $bank->logo = $imagePath;
            }

            $bank->bank_name = $request->get('nama_bank');
            $bank->account_id = $request->get('account_id');
            $bank->account_name = $request->get('nama_rekening');
            $bank->account_number = $request->get('no_rekening');
            $bank->description = $request->get('keterangan');
            $bank->save();

            return redirect()->route('bank.index')->with('success', 'Bank '.$bank->bank_name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Bank, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $bank = Bank::find($id);
            $bank->delete();

            return redirect()->route('bank.index')->with('success', 'Bank '.$bank->bank_name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Bank, Error: '.$e->getMessage());
        }
    }
}
