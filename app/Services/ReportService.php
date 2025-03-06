<?php

namespace App\Services;

use App\Models\TransactionDetail;
use App\Models\Account;
use App\Models\TransactionPeriod;
use Carbon\Carbon;

class ReportService
{
    public function getBalanceSheet($periodId = null)
    {
        $period = $periodId ? TransactionPeriod::find($periodId) : getDataPeriod();
        $start_date = $period->start_date;
        $end_date = $period->end_date;

        $assets = $this->getAccountBalance('assets', $periodId)->sortBy('full_code');
        $liabilities = $this->getAccountBalance('liabilities', $periodId)->sortBy('full_code');
        $equity = $this->getAccountBalance('equity', $periodId)->sortBy('full_code');
        $title = 'Laporan Keuangan - Balance Sheet (dicetak pada '. Carbon::now()->format('d M Y H:i').')';

        $labaTahunBerjalan = (object) [
            'id' => null,
            'account_name' => 'Laba Tahun Berjalan',
            'full_code' => '[AUTO]',
            'balance_amount' => $this->getIncomeStatement($period->id)['netIncome'], // Nilai laba tahun berjalan
        ];
        $equity->push($labaTahunBerjalan);

        $totalAssets = $assets->sum('balance_amount');
        $totalLiabilities = $liabilities->sum('balance_amount');
        $totalEquity = $equity->sum('balance_amount');
        $totalLiabilitiesEquity = $totalLiabilities + $totalEquity;

        return compact(
            'title',
            'assets',
            'liabilities',
            'equity',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'totalLiabilitiesEquity',
            'start_date',
            'end_date'
        );
    }

    public function getCashFlow($periodId = null)
    {
        $period = $periodId ? TransactionPeriod::find($periodId) : null;
        $start_date = $period ? $period->start_date : null;
        $end_date = $period ? $period->end_date : null;

        $title = 'Laporan Keuangan - Alur Kas (Cash Flow) (dicetak pada ' . Carbon::now()->format('d M Y H:i') . ')';

        $accList = [config('al.coa')['kas'], config('al.coa')['bank']];
        $cashAccs = Account::whereIn('code', $accList)
            ->whereNull('parent_account_id')
            ->whereHas('master', fn ($q) => $q->where('type', 'assets'))
            ->pluck('id')
            ->toArray();

        $cashFlowsQuery = TransactionDetail::query()
            ->whereHas('account', function ($query) use ($cashAccs) {
                $query->whereIn('id', $cashAccs)
                    ->orWhereIn('parent_account_id', $cashAccs);
            })
            ->whereHas('account.master', fn ($query) => $query->where('type', 'assets'));

        // Jika ada periode, filter berdasarkan tanggal transaksi
        if ($period) {
            $cashFlowsQuery->whereHas('transaction', fn ($query) => 
                $query->whereBetween('date', [$start_date, $end_date])
            );
        }

        $cashFlows = $cashFlowsQuery->with(['transaction:id,date', 'account'])
            ->orderByRaw('(SELECT date FROM transactions WHERE transactions.id = transaction_details.transaction_id) ASC')
            ->get();

        return compact('cashFlows', 'title', 'start_date', 'end_date');
    }


    public function getIncomeStatement($periodId = null)
    {
        $period = $periodId ? TransactionPeriod::find($periodId) : null;
        $start_date = $period ? $period->start_date : null;
        $end_date = $period ? $period->end_date : null;

        $revenueQuery = TransactionDetail::query()
            ->whereHas('account.master', function ($query) {
                $query->where('type', 'revenue');
            });

        if($period){
            $revenueQuery->whereHas('transaction', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            });
        }

        $revenues = $revenueQuery->with(['transaction:id,date', 'account'])
            ->orderByRaw('(SELECT date FROM transactions WHERE transactions.id = transaction_details.transaction_id) ASC')
            ->get();

        $expenseQuery = TransactionDetail::query()
            ->whereHas('account.master', function ($query) {
                $query->where('type', 'expenses');
            });

        if($period){
            $expenseQuery->whereHas('transaction', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            });
        }

        $expenses = $expenseQuery->with(['transaction:id,date', 'account'])
            ->orderByRaw('(SELECT date FROM transactions WHERE transactions.id = transaction_details.transaction_id) ASC')
            ->get();

        $title = 'Laporan Keuangan - Income Statement (Laba Rugi) (dicetak pada '. Carbon::now()->format('d M Y H:i').')';

        $totalRevenue = $revenues->sum('credit');
        $totalExpense = $expenses->sum('debit');
        $netIncome = $totalRevenue - $totalExpense;

        return compact('revenues', 'expenses', 'totalRevenue', 'totalExpense', 'netIncome', 'title', 'start_date', 'end_date');
    }

    public function getGeneralLedger($periodId = null)
    {
        $period = $periodId ? TransactionPeriod::find($periodId) : null;
        $start_date = $period ? $period->start_date : null;
        $end_date = $period ? $period->end_date : null;

        $title = 'Laporan Keuangan - Buku Besar (General Ledger) (dicetak pada '. Carbon::now()->format('d M Y H:i').')';

        $ledgerQuery = TransactionDetail::query()
            ->with(['transaction:id,date', 'account']);
        
        if($period){
            $ledgerQuery->whereHas('transaction', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            });
        }
        
        $ledgerEntries = $ledgerQuery->orderByRaw('(SELECT date FROM transactions WHERE transactions.id = transaction_details.transaction_id) ASC')
                                    ->get();

        $groupedLedger = $ledgerEntries->sortBy('account.full_code')->groupBy('account.full_code');

        return compact('title', 'groupedLedger', 'start_date', 'end_date');
    }

    public function getChangesInEquity($periodId = null)
    {   
        $period = $periodId ? TransactionPeriod::find($periodId) : null;
        $start_date = $period ? $period->start_date : null;
        $end_date = $period ? $period->end_date : null;

        $title = 'Laporan Keuangan - Perubahan Ekuitas (Changes in Equity) (dicetak pada '. Carbon::now()->format('d M Y H:i').')';
        
        $exceptEquityCodes = ['103']; 
        $exceptEquityAccounts = Account::whereIn('code', $exceptEquityCodes)->get()->pluck('id')->toArray();
        $exceptChildAccountIds = Account::whereIn('parent_account_id', $exceptEquityAccounts)->pluck('id')->toArray();
        $exceptAccountIds = array_merge($exceptEquityAccounts, $exceptChildAccountIds);
        
        $equityQuery = TransactionDetail::query()
            ->whereHas('account', function ($query) use ($exceptAccountIds) {
                $query->whereNotIn('id', $exceptAccountIds);
            })
            ->whereHas('account.master', function ($query) {
                $query->where('type', 'equity');
            });

        if($period){
            $equityQuery->whereHas('transaction', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('date', [$start_date, $end_date]);
            });
        }
        
        $equityChanges = $equityQuery->with(['transaction:id,date', 'account'])
            ->orderByRaw('(SELECT date FROM transactions WHERE transactions.id = transaction_details.transaction_id) ASC')
            ->get();

        return compact('equityChanges', 'title', 'start_date', 'end_date');
    }

    public function getReceivableAndPayable($periodId = null)
    {
        $period = $periodId ? TransactionPeriod::find($periodId) : null;
        $start_date = $period ? $period->start_date : null;
        $end_date = $period ? $period->end_date : null;

        $title = 'Laporan Keuangan - Hutang Piutang (dicetak pada '. Carbon::now()->format('d M Y H:i').')';

        $piutangCode = config('al.coa')['piutang'];
        $piutangAcc = Account::where('code', $piutangCode)
            ->whereHas('master', fn($q) => $q->where('type', 'assets'))
            ->first();

        $receivableQuery = TransactionDetail::query()
            ->whereHas('account', function ($query) use ($piutangAcc) {
            $query->where('id', $piutangAcc->id)
                ->orWhere('parent_account_id', $piutangAcc->id);
            })
            ->whereHas('account.master', fn($query) => $query->where('type', 'assets'));
        
        if($period){
            $receivableQuery->whereHas('transaction', fn($query) => $query->whereBetween('date', [$start_date, $end_date]));
        }

        $transactionReceivables = $receivableQuery->with(['transaction:id,date', 'account'])
            ->orderByRaw('(SELECT date FROM transactions WHERE transactions.id = transaction_details.transaction_id) ASC')
            ->get();

        $payableCode = [config('al.coa')['hutang'], config('al.coa')['hutang_logistik']]; 
        $payableAcc = Account::whereIn('code', $payableCode)
            ->whereHas('master', fn($q) => $q->where('type', 'liabilities'))
            ->pluck('id')
            ->toArray();

        $payableQuery = TransactionDetail::query()
            ->whereHas('account', function ($query) use ($payableAcc) {
                $query->whereIn('id', $payableAcc)
                    ->orWhereIn('parent_account_id', $payableAcc);
            })
            ->whereHas('account.master', fn($query) => $query->where('type', 'liabilities'));
        
        if($period){
            $payableQuery->whereHas('transaction', fn($query) => $query->whereBetween('date', [$start_date, $end_date]));
        }
        
        $transactionPayables = $payableQuery->with(['transaction:id,date', 'account'])
            ->orderByRaw('(SELECT date FROM transactions WHERE transactions.id = transaction_details.transaction_id) ASC')
            ->get();

        return compact('transactionReceivables', 'transactionPayables', 'title', 'start_date', 'end_date');
    }

    private function getAccountBalance($type, $periodId)
    {
        return Account::whereHas('master', function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($type === 'assets', function ($query) {
                $query->where('code', '!=', config('al.coa.ppn_masukan'));
            })
            ->when($type === 'liabilities', function ($query) {
                $query->where('code', '!=', config('al.coa.ppn_keluaran'));
            })
            ->where(function ($query) {
                $query->whereHas('transactions.transaction');
            })
            ->withSum(['transactions as transactions_sum_debit' => function ($query){
                $query->whereHas('transaction');
            }], 'debit')
            ->withSum(['transactions as transactions_sum_credit' => function ($query){
                $query->whereHas('transaction');
            }], 'credit')
            ->get()
            ->map(function ($account) use ($type) {
                $credit = (float)($account->transactions_sum_credit ?? 0);
                $debit = (float)($account->transactions_sum_debit ?? 0);

                if ($type === 'assets') {
                    $account->balance_amount = $debit - $credit;
                } else {
                    $account->balance_amount = $credit - $debit;
                }

                return $account;
            });
    }

}
