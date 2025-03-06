<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionPeriod;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function balanceSheet(Request $request)
    {
        $filter = (object) ['period' => $request->get('period', (getDataPeriod() ? getDataPeriod()->id : null))];
        $periods = TransactionPeriod::orderBy('start_date', 'asc')->get();
        $data = $this->reportService->getBalanceSheet($filter->period);

        return view('reports.balance_sheet', array_merge($data, compact('filter', 'periods')));
    }

    public function cashFlow(Request $request)
    {
        $filter = (object) ['period' => $request->get('period')];
        $periods = TransactionPeriod::orderBy('start_date', 'asc')->get();
        $data = $this->reportService->getCashFlow($filter->period);

        return view('reports.cash_flow', array_merge($data, compact('filter', 'periods')));
    }

    public function incomeStatement(Request $request)
    {
        $filter = (object) ['period' => $request->get('period')];
        $periods = TransactionPeriod::orderBy('start_date', 'asc')->get();
        $data = $this->reportService->getIncomeStatement($filter->period);

        return view('reports.income_statement', array_merge($data, compact('filter', 'periods')));
    }

    public function generalLedger(Request $request)
    {
        $filter = (object) ['period' => $request->get('period')];
        $periods = TransactionPeriod::orderBy('start_date', 'asc')->get();
        $data = $this->reportService->getGeneralLedger($filter->period);

        return view('reports.general_ledger', array_merge($data, compact('filter', 'periods')));
    }

    public function changesInEquity(Request $request)
    {
        $filter = (object) ['period' => $request->get('period')];
        $periods = TransactionPeriod::orderBy('start_date', 'asc')->get();
        $data = $this->reportService->getChangesInEquity($filter->period);

        return view('reports.changes_in_equity', array_merge($data, compact('filter', 'periods')));
    }

    public function receivableAndPayable(Request $request)
    {
        $filter = (object) ['period' => $request->get('period')];
        $periods = TransactionPeriod::orderBy('start_date', 'asc')->get();
        $data = $this->reportService->getReceivableAndPayable($filter->period);

        return view('reports.receivable_and_payable', array_merge($data, compact('filter', 'periods')));
    }
}
