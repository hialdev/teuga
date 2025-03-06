<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Asset;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransactionPeriod;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionPeriodController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'name',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'asc',
        ];

        $periods = TransactionPeriod::query()
            ->where('name', 'LIKE', '%' . $filter->q . '%')
            ->orderBy($filter->field, $filter->order)->get();

        return view('periods.index', compact('periods', 'filter'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'start_date'=> 'required|date',
            'end_date'=> 'required|date|after:start_date',
        ]);
        try {

            $period = new TransactionPeriod();
            $period->name = $request->get('name');
            $period->start_date = $request->get('start_date');
            $period->end_date = $request->get('end_date');
            $period->save();

            return redirect()->back()->with('success', 'Periode '.$period->name.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Periode, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $request->validate([
            'name' => 'required|string',
            'start_date'=> 'required|date',
            'end_date'=> 'required|date|after:start_date',
        ]);
        try {

            $period = TransactionPeriod::find($id);
            $period->name = $request->get('name');
            $period->start_date = $request->get('start_date');
            $period->end_date = $request->get('end_date');
            $period->save();

            return redirect()->back()->with('success', 'Periode '.$period->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Periode, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $period = TransactionPeriod::find($id);
            if($period->transactions->count() > 0){
                return redirect()->back()->with('error', 'Gagal menghapus Periode, Periode '.$period->name.' memiliki data Transaksi, Hapus terlebih dahulu atau perbarui ke Periode yang lain agar dapat menghapus Periode.');
            }
            $period->delete();

            return redirect()->route('period.index')->with('success', 'Periode '.$period->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Periode, Error: '.$e->getMessage());
        }
    }

    public function close($id) {
        try {
            $period = TransactionPeriod::findOrFail($id);

            if($period->is_closed){
                return redirect()->back()->with('error', 'Gagal menutup Buku Periode ' . $period->name . '. Priode ini sudah ditutup');
            }

            // Validasi hanya bisa menutup periode yang sudah berakhir
            if (now() <= $period->end_date) {
                return redirect()->back()->with('error', 'Gagal menutup Buku Periode ' . $period->name . '. Penutupan hanya bisa dilakukan setelah atau pada ' . Carbon::parse($period->end_date)->format('d M Y'));
            }

            // Ambil akun-akun yang diperlukan
            $depreciateAcc = Account::where('code', config('al.coa')['akumulasi_penyusutan'])->whereHas('master', fn($q) => $q->where('type', 'assets'))->first();
            $prepaidTaxAcc = Account::where('code', config('al.coa')['ppn_aset'])->whereHas('master', fn($q) => $q->where('type', 'assets'))->first(); // PPN Dibayar di Muka
            $expenseDepreciationAcc = Account::where('code', config('al.coa')['beban_penyusutan'])->whereHas('master', fn($q) => $q->where('type', 'expenses'))->first();
            $appreciateAcc = Account::where('code', config('al.coa')['surplus_revaluasi'])->whereHas('master', fn($q) => $q->where('type', 'equity'))->first();
            $retainedEarningsAcc = Account::where('code', config('al.coa')['laba_ditahan'])->whereHas('master', fn($q) => $q->where('type', 'equity'))->first();
            $taxPayableAcc = Account::where('code', config('al.coa')['hutang_pajak'])->whereHas('master', fn($q) => $q->where('type', 'liabilities'))->first();
            
            // Ambil semua aset yang aktif dalam periode ini
            $assets = Asset::whereYear('date', Carbon::parse($period->start_date)->format('Y'))->get();
            foreach ($assets as $asset) {
                $months_passed = Carbon::parse($asset->date)->diffInMonths($period->end_date);
                $years_passed = $months_passed / 12; // Konversi ke tahun dalam desimal

                if ($asset->is_appreciating) {
                    // **Hitung apresiasi aset**
                    $new_value = $asset->calculateAppreciation($asset->purchase_price, (float) $asset->appreciation_rate, $years_passed);
                    $appreciation_amount = $new_value - $asset->purchase_price;
                    
                    if ($appreciation_amount > 0) {
                        // **Buat transaksi jurnal apresiasi aset**
                        $trx = Transaction::create([
                            'date' => $period->end_date,
                            'transaction_period_id' => $period->id,
                            'description' => '[Generated Close Period] Apresiasi nilai aset: ' . $asset->name,
                            'is_generated' => 1,
                        ]);

                        // **Debit ke akun Aset**
                        TransactionDetail::create([
                            'transaction_id' => $trx->id,
                            'account_id' => $appreciateAcc->id,
                            'debit' => 0,
                            'credit' => $appreciation_amount,
                            'description' => '[Generated Close Period] Kenaikan nilai aset: ' . $asset->name,
                        ]);

                        // **Kredit ke akun Surplus Revaluasi**
                        TransactionDetail::create([
                            'transaction_id' => $trx->id,
                            'account_id' => $asset->account_id,
                            'debit' => $appreciation_amount,
                            'credit' => 0,
                            'description' => '[Generated Close Period] Surplus revaluasi aset: ' . $asset->name,
                        ]);
                    }
                } else {
                    // **Hitung penyusutan aset**
                    $depreciation = $asset->calculateDepreciation($asset->purchase_price, $asset->residu_price, $asset->useful_life, $years_passed);
                    
                    if ($depreciation['annual_depreciation'] > 0) {
                        // **Buat transaksi jurnal penyusutan**
                        $trx = Transaction::create([
                            'date' => $period->end_date,
                            'transaction_period_id' => $period->id,
                            'description' => '[Generated Close Period] Penyusutan aset: ' . $asset->name,
                            'is_generated' => 1,
                        ]);

                        // **Debit ke akun Beban Penyusutan**
                        TransactionDetail::create([
                            'transaction_id' => $trx->id,
                            'account_id' => $expenseDepreciationAcc->id,
                            'debit' => $depreciation['annual_depreciation'],
                            'credit' => 0,
                            'description' => '[Generated Close Period] Beban penyusutan aset: ' . $asset->name,
                        ]);

                        // **Kredit ke akun Akumulasi Penyusutan**
                        TransactionDetail::create([
                            'transaction_id' => $trx->id,
                            'account_id' => $depreciateAcc->id,
                            'debit' => 0,
                            'credit' => $depreciation['annual_depreciation'],
                            'description' => '[Generated Close Period] Akumulasi penyusutan aset: ' . $asset->name,
                        ]);
                    }
                }
            }

            // **Hitung Pendapatan & Beban**
            $incomeStatement = $this->reportService->getIncomeStatement($period->id);
            $netIncome = $incomeStatement['netIncome'];

            if ($netIncome != 0) {
                $trx = Transaction::create([
                    'date' => $period->end_date,
                    'transaction_period_id' => $period->id,
                    'description' => '[Generated Close Period] Penutupan Pendapatan dan Beban ke Laba Ditahan',
                    'is_generated' => 1,
                ]);

                // **Transfer laba bersih ke laba ditahan**
                if ($netIncome > 0) {
                    TransactionDetail::create([
                        'transaction_id' => $trx->id,
                        'account_id' => $retainedEarningsAcc->id,
                        'debit' => 0,
                        'credit' => $netIncome,
                        'description' => '[Generated Close Period] Laba ditahan dari Income Statement',
                    ]);
                } else {
                    TransactionDetail::create([
                        'transaction_id' => $trx->id,
                        'account_id' => $retainedEarningsAcc->id,
                        'debit' => abs($netIncome),
                        'credit' => 0,
                        'description' => '[Generated Close Period] Rugi ditahan dari Income Statement',
                    ]);
                }
            }
        
            // **Hitung Pajak**
            $ppnMasukanTotal = TransactionDetail::whereHas('account', fn($q) => $q->where('code', config('al.coa')['ppn_masukan'])
                                                        ->whereHas('master', fn($q) => $q->where('type', 'assets'))
                                                    )
                                                    ->whereHas('transaction', fn($q) => $q->where('transaction_period_id', $period->id))
                                                    ->sum('debit');
            $ppnKeluaranTotal = TransactionDetail::whereHas('account', fn($q) => $q->where('code', config('al.coa')['ppn_keluaran'])
                                                        ->whereHas('master', fn($q) => $q->where('type', 'liabilities'))
                                                    )
                                                    ->whereHas('transaction', fn($q) => $q->where('transaction_period_id', $period->id))
                                                    ->sum('credit');
            $pph23Total = TransactionDetail::whereHas('account', fn($q) => $q->where('code', config('al.coa')['pph23'])
                                                        ->whereHas('master', fn($q) => $q->where('type', 'liabilities'))
                                                    )
                                                    ->whereHas('transaction', fn($q) => $q->where('transaction_period_id', $period->id))
                                                    ->sum('credit');

            $ppn_masukan = $ppnMasukanTotal;
            $ppn_keluaran = $ppnKeluaranTotal;
            $pph23 = $pph23Total; // Contoh PPh 23

            $total_pajak = ($ppn_keluaran - $ppn_masukan) + $pph23;

            if ($total_pajak > 0) {
                // Pajak harus dibayar
                $trx = Transaction::create([
                    'date' => $period->end_date,
                    'transaction_period_id' => $period->id,
                    'description' => '[Generated Close Period] Pajak yang harus dibayar',
                    'is_generated' => 1,
                ]);

                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'account_id' => $taxPayableAcc->id,
                    'debit' => 0,
                    'credit' => $total_pajak,
                    'description' => '[Generated Close Period] Utang Pajak',
                ]);
            } elseif ($total_pajak < 0) {
                // Ada kelebihan bayar pajak (aset pajak)
                $trx = Transaction::create([
                    'date' => $period->end_date,
                    'transaction_period_id' => $period->id,
                    'description' => '[Generated Close Period] Kelebihan bayar pajak',
                    'is_generated' => 1,
                ]);

                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'account_id' => $prepaidTaxAcc->id,
                    'debit' => abs($total_pajak),
                    'credit' => 0,
                    'description' => '[Generated Close Period] Aset Pajak Dibayar di Muka',
                ]);
            }

            $period->is_closed = 1;
            $period->save();

            return redirect()->back()->with('success', 'Periode ' . $period->name . ' berhasil ditutup.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menutup Buku Periode ini. Error: ' . $e->getMessage());
        }
    }

}
