<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\RequestOrderInvoice;
use App\Models\RequestOrderPayment;
use App\Models\ROPaymentTransaction;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RequestOrderPaymentController extends Controller
{
    public function store(Request $request, $id){
        $roInvoice = RequestOrderInvoice::find($id);
        $request->merge([
            'paid_total' => parseRupiah($request->get('paid_total'))
        ]);
        $request->validate([
            'file' => 'required|file|mimes:png,webp,jpg,jpeg,jfif,pdf,docx,doc,pptx|max:10240',
            'date' => 'required|date|before_or_equal:today',
            'paid_total' => 'required|numeric|min:0|max:'.$roInvoice->remaining_payment,
        ]);
        try {
            $pay = new RequestOrderPayment();
            if ($request->hasFile('file')) {
                if ($pay->file && file_exists(storage_path('app/public/' . $pay->file))) {
                    unlink(storage_path('app/public/' . $pay->file));
                }
                $filePath = $request->file('file')->store('request_orders/invoices/payment', 'public');
                $pay->file = $filePath;
            }
            $pay->request_order_invoice_id = $id;
            $pay->date = $request->get('date');
            $pay->paid_total = $request->get('paid_total');
            $pay->save();

            $this->syncStatus($pay->invoice);

            return redirect()->route('request-order.invoice.show', $id)->with('success', 'Pembayaran berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat pembayaran, Error: '.$e->getMessage());
        }
    }

    public function generateTrx($id, $pay_id, Request $request)
    {
        $request->validate(['account_id' => 'required|string|exists:accounting.accounts,id']);

        try {
            $payment = RequestOrderPayment::findOrFail($pay_id);
            $totalPaid = $payment->paid_total;
            $clientName = $payment->invoice->requestOrder->client->name;

            $piutangParentAcc = Account::where('code', config('al.coa')['piutang'])
                ->whereHas('master', fn($q) => $q->where('type', 'assets'))
                ->first();

            $piutangAcc = $this->getOrAddChild($piutangParentAcc, $clientName, 'Akun Piutang untuk ');
            $receiverAcc = Account::find($request->get('account_id'));

            // Buat transaksi (1 jurnal)
            $trx = Transaction::create([
                'date' => Carbon::now(),
                'transaction_period_id' => getPeriodByDate(Carbon::now())->id,
                'description' => 'Generated Payment Transaction for ' . $payment->code . '.',
                'is_generated' => 1,
            ]);

            // Detail Kredit (Piutang)
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $piutangAcc->id,
                'debit' => 0,
                'credit' => $totalPaid,
                'description' => '[Generated] Pembayaran Piutang Invoice ' . $payment->invoice->code . ' untuk ' . $clientName. ' ('.$payment->code.')',
            ]);

            // Detail Debit (Penerimaan)
            TransactionDetail::create([
                'transaction_id' => $trx->id,
                'account_id' => $receiverAcc->id,
                'debit' => $totalPaid,
                'credit' => 0,
                'description' => '[Generated] Penerimaan Pembayaran Invoice ' . $payment->invoice->code . ' untuk ' . $clientName. ' ('.$payment->code.')',
            ]);

            // Pivot ke payment
            ROPaymentTransaction::create([
                'ro_payment_id' => $payment->id,
                'transaction_id' => $trx->id,
                'description' => 'Transaksi Penerimaan Pembayaran Piutang dari ' . $clientName . ' dengan Kode Transaksi ' . $trx->code,
            ]);

            return redirect()->route('request-order.invoice.show', $id)->with('success', 'Berhasil mencatat transaksi Pembayaran Piutang dengan Kode Transaksi ' . $trx->code);
        } catch (\Exception $e) {
            return redirect()->route('request-order.invoice.show', $id)->withInput()->with('error', 'Gagal mencatat transaksi Pembayaran Invoice (Piutang - Permintaan Client), Error: ' . $e->getMessage());
        }
    }

    public function destroy($id, $pay_id){
        try {
            $pay = RequestOrderPayment::find($pay_id);
            $pay->delete();
            
            $this->syncStatus($pay->invoice);
            return redirect()->route('request-order.invoice.show', $id)->with('success', 'Pembayaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus pembayaran, Error: '.$e->getMessage());
        }
    }

    private function syncStatus($payment){
        try {
            if($payment->remaining_payment == $payment->product_qty_price['total_price_taxed']){
                $payment->payment_status = '0';
            }else if($payment->remaining_payment < $payment->product_qty_price['total_price_taxed'] && $payment->remaining_payment > 0){
                $payment->payment_status = '1';
            }else if($payment->remaining_payment == 0){
                $payment->payment_status = '2';
            }
            return $payment->save();
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
