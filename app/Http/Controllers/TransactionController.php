<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransactionPeriod;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $selectedPeriodId = getDataPeriod() ? getDataPeriod()->id : null;
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'code',
            'period' => $request->get('period') ?? $selectedPeriodId,
            'order' => $request->get('order') === 'newest' ? 'desc' : 'asc',
        ];

        // Ambil daftar periode transaksi
        $periods = TransactionPeriod::orderBy('start_date', 'asc')->get();

        // Ambil transaksi berdasarkan periode & filter
        $transactions = Transaction::where('transaction_period_id', $filter->period)
            ->where(function ($q) use ($filter) {
                $q->where('code', 'LIKE', '%' . $filter->q . '%')
                ->orWhere('description', 'LIKE', '%' . $filter->q . '%')
                ->orWhereHas('details', function ($q) use ($filter) {
                    $q->where('description', 'LIKE', '%' . $filter->q . '%');
                });
            })
            ->orderBy($filter->field, $filter->order)
            ->get();

        return view('transactions.index', compact('transactions', 'filter', 'periods'));
    }


    public function add(){
        $accounts = Account::all()->sortBy('full_code');
        $periods = TransactionPeriod::orderBy('start_date','asc')->where('is_closed', 0)->get();
        return view('transactions.add', compact('accounts', 'periods'));
    }

    public function edit($id){
        $transaction = Transaction::findOrFail($id);
        if($transaction->is_closed || $transaction->is_generated || $transaction->period->is_closed){
            return redirect()->route('transaction.index')->with('error', 'Transaksi '.$transaction->code.' tidak dapat diedit karena sudah Tutup Buku atau dibuat secara Otomatis.');
        }
        $periods = TransactionPeriod::orderBy('start_date','asc')->get();
        $accounts = Account::all()->sortBy('full_code');

        return view('transactions.edit', compact('accounts', 'transaction', 'periods'));
    }

    public function store(Request $request){
        $request->merge([
            'debit' => array_map(fn($value) => $value ? parseRupiah($value) : 0, $request->input('debit', [])),
            'credit' => array_map(fn($value) => $value ? parseRupiah($value) : 0, $request->input('credit', [])),
        ]);
        $request->validate([
            'date' => 'required|date',
            'description_primary' => 'nullable|string',
            'transaction_period_id' => 'required|string|exists:accounting.transaction_periods,id',
            'account_id' => 'required|array',
            'account_id.*' => 'required|exists:accounting.accounts,id',
            'debit' => 'nullable|array',
            'debit.*' => 'nullable|numeric',
            'credit' => 'nullable|array',
            'credit.*' => 'nullable|numeric',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
        ]);
        try {
                
            if($this->isValidTransaction($request->get('account_id'), $request->get('debit'), $request->get('credit'))){
                $transaction = new Transaction();
                $transaction->transaction_period_id = $request->get('transaction_period_id');
                $transaction->date = $request->get('date');
                $transaction->description = $request->get('description_primary');
                $transaction->save();

                if($transaction){
                    foreach ($request->get('account_id') as $i => $accountId) {
                        $transactionDetail = new TransactionDetail();
                        $transactionDetail->credit = $request->get('credit')[$i] ?? null;
                        $transactionDetail->debit = $request->get('debit')[$i] ?? null;
                        $transactionDetail->transaction_id = $transaction->id;
                        $transactionDetail->account_id = $accountId;
                        $transactionDetail->description = $request->get('description')[$i] ?? null;
                        $transactionDetail->save();
                    }
                }
            }else{
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Transaksi, Error: Setiap Akun transaksi detail harus mengisi debit atau credit (bukan 0).');
            }

            return redirect()->route('transaction.index')->with('success', 'Transaksi '.$transaction->code.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Account, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $request->merge([
            'debit' => array_map(fn($value) => $value ? parseRupiah($value) : 0, $request->input('debit', [])),
            'credit' => array_map(fn($value) => $value ? parseRupiah($value) : 0, $request->input('credit', [])),
        ]);
        $request->validate([
            'date' => 'required|date',
            'description_primary' => 'nullable|string',
            'transaction_period_id' => 'required|string|exists:accounting.transaction_periods,id',
            'account_id' => 'required|array',
            'account_id.*' => 'required|exists:accounting.accounts,id',
            'debit' => 'nullable|array',
            'debit.*' => 'nullable|numeric',
            'credit' => 'nullable|array',
            'credit.*' => 'nullable|numeric',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
        ]);
        try {
                
            $transaction = Transaction::find($id);
            if($transaction->is_closed || $transaction->is_generated || $transaction->period->is_closed){
                return redirect()->route('transaction.index')->with('error', 'Transaksi '.$transaction->code.' tidak dapat diedit karena sudah Tutup Buku atau dibuat secara Otomatis.');
            }

            if($this->isValidTransaction($request->get('account_id'), $request->get('debit'), $request->get('credit'))){
                $transaction->transaction_period_id = $request->get('transaction_period_id');
                $transaction->date = $request->get('date');
                $transaction->description = $request->get('description_primary');
                $transaction->save();

                if($transaction){
                    $transaction->details()->delete();
                    foreach ($request->get('account_id') as $i => $accountId) {
                        $transactionDetail = new TransactionDetail();
                        $transactionDetail->credit = $request->get('credit')[$i] ?? null;
                        $transactionDetail->debit = $request->get('debit')[$i] ?? null;
                        $transactionDetail->transaction_id = $transaction->id;
                        $transactionDetail->account_id = $accountId;
                        $transactionDetail->description = $request->get('description')[$i] ?? null;
                        $transactionDetail->save();
                    }
                }
            }else{
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Transaksi, Error: Setiap Akun transaksi detail harus mengisi debit atau credit (bukan 0).');
            }

            return redirect()->route('transaction.index')->with('success', 'Transaksi '.$transaction->code.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Account, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $transaction = Transaction::find($id);
            if($transaction->is_closed || $transaction->period->is_closed){
                return redirect()->back()->with('error', 'Transaksi '.$transaction->code.' tidak dapat dihapus karena sudah Tutup Buku.');
            }
            $transaction->details()->delete();
            $transaction->delete();

            return redirect()->back()->with('success', 'Transaksi '.$transaction->code.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Account, Error: '.$e->getMessage());
        }
    }

    // -------------------------------
    private function isValidTransaction($accountIds, $debits, $credits)
    {
        foreach ($accountIds as $index => $accountId) {
            $debit = isset($debits[$index]) ? (float) $debits[$index] : 0;
            $credit = isset($credits[$index]) ? (float) $credits[$index] : 0;

            // Jika debit & credit keduanya 0, transaksi tidak valid
            if ($debit == 0 && $credit == 0) {
                return false;
            }
        }
        return true;
    }

}
