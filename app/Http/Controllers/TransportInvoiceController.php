<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\TransportInvoice;
use App\Models\TransportInvoiceTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransportInvoiceController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'code',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $tpInvoices = TransportInvoice::query()
            ->where('code', 'LIKE', '%' . $filter->q . '%');
        $tpInvoices->orderBy($filter->field, $filter->order);
        $tpInvoices = $tpInvoices->get();

        return view('transports.invoice.index', compact('filter', 'tpInvoices'));
    }

    public function show($id){
        $tpInvoice = TransportInvoice::findOrFail($id);
        
        return view('transports.invoice.show', compact('tpInvoice'));
    }

    public function generateTrx(Request $request)
    {
        $request->validate(['id' => 'required|string|exists:osano.transport_invoices,id']);

        try {
            $invoice = TransportInvoice::findOrFail($request->id);
            $totalPrice = $invoice->transport->total_price;
            $totalPaid = $invoice->transport->total_price_taxed;
            $name = $invoice->transport->logistic->name;

            $logParentAcc = Account::where('code', config('al.coa')['beban_logistik'])
                ->whereHas('master', fn($q) => $q->where('type', 'expenses'))
                ->first();

            $payableParentAcc = Account::where('code', config('al.coa')['hutang_logistik'])
                ->whereHas('master', fn($q) => $q->where('type', 'liabilities'))
                ->first();

            $logAcc = $this->getOrAddChild($logParentAcc, $name, 'Akun Beban Logistik untuk ');
            $payableAcc = $this->getOrAddChild($payableParentAcc, $name, 'Akun Hutang Logistik untuk ');
            $ppnAcc = Account::where('code', config('al.coa')['pph23'])
                               ->whereHas('master', fn($q) => $q->where('type', 'liabilities'))
                               ->first();

            // Buat transaksi (1 jurnal)
            $trx = Transaction::create([
                'date' => Carbon::now(),
                'transaction_period_id' => getPeriodByDate(Carbon::now())->id,
                'description' => 'Generated Transaction Invoice Pengangkutan ' . $invoice->code . '.',
                'is_generated' => 1,
            ]);

            // Detail Debit (Logistik)
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $logAcc->id,
                'debit' => $totalPrice,
                'credit' => 0,
                'description' => '[Generated] Beban Logistik LOG Invoice ' . $invoice->code . ' untuk ' . $name,
            ]);

            // Detail Kredit (Hutang)
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $payableAcc->id,
                'debit' => 0,
                'credit' => $totalPaid,
                'description' => '[Generated] Hutang Logistik LOG Invoice ' . $invoice->code . ' untuk ' . $name,
            ]);

            // Detail Kredit (Hutang)
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $ppnAcc->id,
                'debit' => 0,
                'credit' => $totalPaid - $totalPrice,
                'description' => '[Generated] PPh Terutang Logistik LOG Invoice ' . $invoice->code . ' untuk ' . $name,
            ]);

            // Pivot ke Invoice
            TransportInvoiceTransaction::create([
                'tp_invoice_id' => $invoice->id,
                'transaction_id' => $trx->id,
                'description' => 'Transaksi Pengangkutan ke ' . $name . ' dengan Kode Transaksi ' . $trx->code,
            ]);

            return redirect()->back()->with('success', 'Berhasil mencatat transaksi Hutang (Pengangkutan) dengan Kode Transaksi ' . $trx->code);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat transaksi LOG Invoice, Error: ' . $e->getMessage());
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
