<?php

namespace App\Http\Controllers;

use App\Models\Flyer;
use App\Models\FlyerView;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $role = auth()->user() !== null ? auth()->user()->getRoleNames()[0] : '';
        
        // $isAdmin = in_array($role, ['admin', 'developer']);
        $reportService = new ReportService();

        $start_date = TransactionDetail::join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->min('transactions.date');
        $end_date = TransactionDetail::join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->max('transactions.date');

        $netIncomeData = $this->getNetIncomeOverTime('monthly', $start_date, $end_date);

        return view('dashboard.admin', compact('reportService', 'netIncomeData'));
    }

    private function getNetIncomeOverTime($timeframe = 'daily', $start_date = null, $end_date = null)
    {
        $query = TransactionDetail::query();

        if ($start_date && $end_date) {
            $query->whereHas('transaction', function ($q) use ($start_date, $end_date) {
                $q->whereBetween('date', [$start_date, $end_date]);
            });
        }

        // Pilih format grup berdasarkan timeframe
        switch ($timeframe) {
            case 'yearly':
                $dateFormat = '%Y';
                break;
            case 'monthly':
                $dateFormat = '%Y-%m';
                break;
            default: // Daily (default)
                $dateFormat = '%Y-%m-%d';
                break;
        }

        $netIncomeData = $query->selectRaw("
                DATE_FORMAT(transactions.date, '{$dateFormat}') as period,
                SUM(CASE WHEN master_accounts.type = 'revenue' THEN transaction_details.credit ELSE 0 END) as total_revenue,
                SUM(CASE WHEN master_accounts.type = 'expenses' THEN transaction_details.debit ELSE 0 END) as total_expense,
                SUM(CASE WHEN master_accounts.type = 'revenue' THEN transaction_details.credit ELSE 0 END) -
                SUM(CASE WHEN master_accounts.type = 'expenses' THEN transaction_details.debit ELSE 0 END) as net_income
            ")
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('accounts', 'accounts.id', '=', 'transaction_details.account_id')
            ->join('master_accounts', 'master_accounts.id', '=', 'accounts.master_account_id')
            ->groupBy('period')
            ->orderBy('period', 'ASC')
            ->get();

        // **Hitung Net Income Kumulatif**
        $cumulativeNetIncome = 0;
        foreach ($netIncomeData as $data) {
            $cumulativeNetIncome += $data->net_income;
            $data->net_income = $cumulativeNetIncome;
        }

        return $netIncomeData;
    }


}
