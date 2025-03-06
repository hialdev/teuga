<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\POInvoiceTransaction;
use App\Models\PurchaseOrderInvoice;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseOrderInvoiceController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'code',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $poInvoices = PurchaseOrderInvoice::query()
            ->where('code', 'LIKE', '%' . $filter->q . '%');
        $poInvoices->orderBy($filter->field, $filter->order);
        $poInvoices = $poInvoices->get();

        return view('purchase_orders.invoice.index', compact('filter', 'poInvoices'));
    }

    public function show($id){
        $poInvoice = PurchaseOrderInvoice::findOrFail($id);
        
        return view('purchase_orders.invoice.show', compact('poInvoice'));
    }

    public function generateTrx(Request $request)
    {
        $request->validate(['id' => 'required|string|exists:osano.purchase_order_invoices,id']);

        try {
            $invoice = PurchaseOrderInvoice::findOrFail($request->id);
            $totalPrice = $invoice->product_qty_price['total_price'];
            $totalPaid = $invoice->product_qty_price['total_price_taxed'];
            $name = $invoice->purchaseOrder->principal->name;

            $cogsParentAcc = Account::where('code', config('al.coa')['cogs'])
                ->whereHas('master', fn($q) => $q->where('type', 'expenses'))
                ->first();

            $payableParentAcc = Account::where('code', config('al.coa')['hutang'])
                ->whereHas('master', fn($q) => $q->where('type', 'liabilities'))
                ->first();

            $cogsAcc = $this->getOrAddChild($cogsParentAcc, $name, 'Akun COGS/HPP untuk ');
            $payableAcc = $this->getOrAddChild($payableParentAcc, $name, 'Akun Hutang untuk ');
            $ppnAcc = Account::where('code', config('al.coa')['ppn_masukan'])
                               ->whereHas('master', fn($q) => $q->where('type', 'assets'))
                               ->first();

            // Buat transaksi (1 jurnal)
            $trx = Transaction::create([
                'date' => Carbon::now(),
                'transaction_period_id' => getPeriodByDate(Carbon::now())->id,
                'description' => 'Generated Transaction Invoice Pembelian ke Principal ' . $invoice->code . '.',
                'is_generated' => 1,
            ]);

            // Detail Debit (COGS)
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $cogsAcc->id,
                'debit' => $totalPrice,
                'credit' => 0,
                'description' => '[Generated] COGS/HPP PO Invoice ' . $invoice->code . ' untuk ' . $name,
            ]);

            // Detail Kredit (Hutang)
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $payableAcc->id,
                'debit' => 0,
                'credit' => $totalPaid,
                'description' => '[Generated] Hutang PO Invoice ' . $invoice->code . ' untuk ' . $name,
            ]);

            // Detail Pajak PPN Masukan
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $ppnAcc->id,
                'debit' => $totalPaid - $totalPrice,
                'credit' => 0,
                'description' => '[Generated] PPN Masukan pada Invoice ' . $invoice->code . ' untuk ' . $name,
            ]);

            // Pivot ke Invoice
            POInvoiceTransaction::create([
                'po_invoice_id' => $invoice->id,
                'transaction_id' => $trx->id,
                'description' => 'Transaksi Pembelian Principal (PO) ke ' . $name . ' dengan Kode Transaksi ' . $trx->code,
            ]);

            return redirect()->back()->with('success', 'Berhasil mencatat transaksi Hutang (Pembelian ke Principal) dengan Kode Transaksi ' . $trx->code);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat transaksi PO Invoice, Error: ' . $e->getMessage());
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
