<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\RequestOrderInvoice;
use App\Models\ROInvoiceTransaction;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RequestOrderInvoiceController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'code',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $roInvoices = RequestOrderInvoice::query()
            ->where('code', 'LIKE', '%' . $filter->q . '%');
        $roInvoices->orderBy($filter->field, $filter->order);
        $roInvoices = $roInvoices->get();

        return view('request_orders.invoice.index', compact('filter', 'roInvoices'));
    }

    public function show($id){
        $roInvoice = RequestOrderInvoice::findOrFail($id);
        
        return view('request_orders.invoice.show', compact('roInvoice'));
    }

    public function generateTrx(Request $request)
    {
        $request->validate(['id' => 'required|string|exists:osano.request_order_invoices,id']);

        try {
            $invoice = RequestOrderInvoice::findOrFail($request->id);
            $totalPrice = $invoice->product_qty_price['total_price'];
            $totalPaid = $invoice->product_qty_price['total_price_taxed'];
            $clientName = $invoice->requestOrder->client->name;

            $piutangParentAcc = Account::where('code', config('al.coa')['piutang'])
                ->whereHas('master', fn($q) => $q->where('type', 'assets'))
                ->first();

            $revenueParentAcc = Account::where('code', config('al.coa')['pendapatan_penjualan'])
                ->whereHas('master', fn($q) => $q->where('type', 'revenue'))
                ->first();

            $piutangAcc = $this->getOrAddChild($piutangParentAcc, $clientName, 'Akun Piutang untuk ');
            $revenueAcc = $this->getOrAddChild($revenueParentAcc, $clientName, 'Akun Pendapatan untuk ');
            $ppnAcc = Account::where('code', config('al.coa')['ppn_keluaran'])
                               ->whereHas('master', fn($q) => $q->where('type', 'liabilities'))
                               ->first();

            // Buat transaksi (1 jurnal)
            $trx = Transaction::create([
                'date' => Carbon::now(),
                'transaction_period_id' => getPeriodByDate(Carbon::now())->id,
                'description' => 'Generated Transaction for ' . $invoice->code . '.',
                'is_generated' => 1,
            ]);

            // Detail Debit (Piutang) 
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $piutangAcc->id,
                'debit' => $totalPaid, // Total termasuk pajak
                'credit' => 0,
                'description' => '[Generated] Piutang Invoice ' . $invoice->code . ' untuk ' . $clientName,
            ]);

            // Detail Kredit (Pendapatan)
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $revenueAcc->id,
                'debit' => 0,
                'credit' => $totalPrice, // Total bersih
                'description' => '[Generated] Revenue Invoice ' . $invoice->code . ' untuk ' . $clientName,
            ]);

            // Detail Pajak PPN Keluaran
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $ppnAcc->id,
                'debit' => 0,
                'credit' => $totalPaid - $totalPrice,
                'description' => '[Generated] PPN Keluaran pada Invoice ' . $invoice->code . ' untuk ' . $clientName,
            ]);

            // Pivot ke Invoice
            ROInvoiceTransaction::create([
                'ro_invoice_id' => $invoice->id,
                'transaction_id' => $trx->id,
                'description' => 'Transaksi Piutang ke ' . $clientName . ' dengan Kode Transaksi ' . $trx->code,
            ]);

            return redirect()->back()->with('success', 'Berhasil mencatat transaksi Piutang dengan Kode Transaksi ' . $trx->code);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat transaksi Invoice, Error: ' . $e->getMessage());
        }
    }

    private function getOrAddChild($parentAccount, $childName, $descriptionPrefix)
    {
        return Account::firstOrCreate(
            [
                'parent_account_id' => $parentAccount->id,
                'account_name' => $childName,
            ],
            [
                'master_account_id' => $parentAccount->master_account_id,
                'description' => $descriptionPrefix . $childName,
            ]
        );
    }

}
