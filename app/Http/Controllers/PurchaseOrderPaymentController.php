<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\POPaymentTransaction;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInvoice;
use App\Models\PurchaseOrderPayment;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseOrderPaymentController extends Controller
{
    public function store(Request $request, $id){
        $poInvoice = PurchaseOrderInvoice::find($id);
        $request->merge([
            'paid_total' => parseRupiah($request->get('paid_total'))
        ]);
        $request->validate([
            'file' => 'required|file|mimes:png,webp,jpg,jpeg,jfif,pdf,docx,doc,pptx|max:10240',
            'date' => 'required|date|before_or_equal:today',
            'paid_total' => 'required|numeric|min:0|max:'.$poInvoice->remaining_payment,
        ]);
        try {
            $pay = new PurchaseOrderPayment();
            if ($request->hasFile('file')) {
                if ($pay->file && file_exists(storage_path('app/public/' . $pay->file))) {
                    unlink(storage_path('app/public/' . $pay->file));
                }
                $filePath = $request->file('file')->store('purchase_orders/invoices/payment', 'public');
                $pay->file = $filePath;
            }
            $pay->purchase_order_invoice_id = $id;
            $pay->date = $request->get('date');
            $pay->paid_total = $request->get('paid_total');
            $pay->save();

            $this->syncStatus($pay->invoice);

            return redirect()->route('purchase-order.invoice.show', $id)->with('success', 'Pembayaran berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat pembayaran, Error: '.$e->getMessage());
        }
    }

    public function generateTrx($id, $pay_id, Request $request)
    {
        $request->validate(['account_id' => 'required|string|exists:accounting.accounts,id']);

        try {
            $payment = PurchaseOrderPayment::findOrFail($pay_id);
            $totalPaid = $payment->paid_total;
            $name = $payment->invoice->purchaseOrder->principal->name;

            $hutangParentAcc = Account::where('code', config('al.coa')['hutang'])
                ->whereHas('master', fn($q) => $q->where('type', 'liabilities'))
                ->first();

            $hutangAcc = $this->getOrAddChild($hutangParentAcc, $name, 'Akun Hutang untuk ');
            $payerAcc = Account::find($request->get('account_id'));

            // Buat transaksi (1 jurnal)
            $trx = Transaction::create([
                'date' => Carbon::now(),
                'transaction_period_id' => getPeriodByDate(Carbon::now())->id,
                'description' => 'Generated Debt Payment Transaction for ' . $payment->code . '.',
                'is_generated' => 1,
            ]);

            // Detail Debit (Hutang)
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $hutangAcc->id,
                'debit' => $totalPaid,
                'credit' => 0,
                'description' => '[Generated] Pembayaran Hutang Invoice ' . $payment->invoice->code . ' untuk ' . $name. ' ('.$payment->code.')',
            ]);

            // Detail Kredit (Pendapatan)
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $payerAcc->id,
                'debit' => 0,
                'credit' => $totalPaid,
                'description' => '[Generated] Pengeluaran Pembayaran Hutang Invoice ' . $payment->invoice->code . ' untuk ' . $name. ' ('.$payment->code.')',
            ]);

            // Pivot ke payment
            POPaymentTransaction::create([
                'po_payment_id' => $payment->id,
                'transaction_id' => $trx->id,
                'description' => 'Transaksi Pengeluaran Pembayaran Hutang ke ' . $name . ' dengan Kode Transaksi ' . $trx->code,
            ]);

            return redirect()->route('purchase-order.invoice.show', $id)->with('success', 'Berhasil mencatat transaksi Pembayaran Hutang dengan Kode Transaksi ' . $trx->code);
        } catch (\Exception $e) {
            return redirect()->route('purchase-order.invoice.show', $id)->withInput()->with('error', 'Gagal mencatat transaksi Pembayaran Invoice (Hutang - Pembelian Ke Principal), Error: ' . $e->getMessage());
        }
    }

    public function destroy($id, $pay_id){
        try {
            $pay = PurchaseOrderPayment::find($pay_id);
            $pay->delete();
            
            $this->syncStatus($pay->invoice);
            return redirect()->route('purchase-order.invoice.show', $id)->with('success', 'Pembayaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus pembayaran, Error: '.$e->getMessage());
        }
    }

    private function syncStatus($invoice){
        try {
            if($invoice->remaining_payment == $invoice->purchaseOrder->total_price_taxed){
                $invoice->payment_status = '0';
            }else if($invoice->remaining_payment < $invoice->purchaseOrder->total_price_taxed && $invoice->remaining_payment > 0){
                $invoice->payment_status = '1';
            }else if($invoice->remaining_payment == 0){
                $invoice->payment_status = '2';
            }
            return $invoice->save();
        } catch (\Exception $e) {
            return $e;
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
