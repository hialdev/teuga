<?php

namespace App\Http\Controllers;

use App\Models\TransactionPeriod;
use Illuminate\Http\Request;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PDFController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function preview(Request $request)
    {
        $request->validate([
            'bladePath' => 'required|string',
            'start_date' => 'nullable|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Ambil parameter
        $bladePath = $request->get('bladePath');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $kop_image = filePath(setting('letter.background'));

        // Tentukan data berdasarkan laporan
        $data = $this->getReportData($bladePath, $startDate, $endDate);

        // Render PDF
        $pdf = Pdf::setOption(['defaultFont' => 'serif', 'isRemoteEnabled'=> true, 'isHtml5ParserEnabled' => true, 'chroot' => public_path(),])
                  ->loadView($bladePath, array_merge($data, [
                      'kop_image' => $kop_image,
                      'date' => ['start' => $startDate ? Carbon::parse($startDate)->translatedFormat('d F Y') : null, 
                                 'end' => $endDate ? Carbon::parse($endDate)->translatedFormat('d F Y') : null]
                  ]))
                  ->setPaper('a4');

        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    }

    public function debug($bladePath, Request $request){
        $bladePath = 'pdf.reports.'.$bladePath;
        $kop_image = filePath(setting('letter.background'));
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $data = $this->getReportData($bladePath, $startDate, $endDate);
        $title = 'Laporan Keuangan - Balance Sheet '.Carbon::now()->startOfYear()->toDateString();
        return view($bladePath, array_merge($data, ['kop_image' => $kop_image, 'title' => $title, 'date' => ['start' => $startDate ? Carbon::parse($startDate)->translatedFormat('d F Y') : null, 
                                 'end' => $endDate ? Carbon::parse($endDate)->translatedFormat('d F Y') : null]]
                    ));
    }

    /**
     * Ambil data berdasarkan jenis laporan yang diminta
     */
    private function getReportData($bladePath, $startDate, $endDate)
    {
        $periodId = ($startDate && $endDate) ? TransactionPeriod::where('start_date', $startDate)->where('end_date', $endDate)->first()->id : null;
        switch ($bladePath) {
            case 'pdf.reports.balance_sheet':
                return $this->reportService->getBalanceSheet($periodId);

            case 'pdf.reports.cash_flow':
                return $this->reportService->getCashFlow($periodId);

            case 'pdf.reports.income_statement':
                return $this->reportService->getIncomeStatement($periodId);

            case 'pdf.reports.general_ledger':
                return $this->reportService->getGeneralLedger($periodId);

            case 'pdf.reports.changes_in_equity':
                return $this->reportService->getChangesInEquity($periodId);

            case 'pdf.reports.receivable_and_payable':
                return $this->reportService->getReceivableAndPayable($periodId);

            default:
                abort(404, 'Laporan tidak ditemukan');
        }
    }
}
