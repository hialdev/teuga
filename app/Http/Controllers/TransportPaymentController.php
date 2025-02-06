<?php

namespace App\Http\Controllers;

use App\Models\TransportInvoice;
use App\Models\TransportPayment;
use Illuminate\Http\Request;

class TransportPaymentController extends Controller
{
    public function store(Request $request, $id){
        $tpInvoice = TransportInvoice::find($id);
        $request->merge([
            'paid_total' => parseRupiah($request->get('paid_total'))
        ]);
        $request->validate([
            'file' => 'required|file|mimes:png,webp,jpg,jpeg,jfif,pdf,docx,doc,pptx|max:10240',
            'date' => 'required|date|before_or_equal:today',
            'paid_total' => 'required|numeric|min:0|max:'.$tpInvoice->remaining_payment,
        ]);
        try {
            $pay = new TransportPayment();
            if ($request->hasFile('file')) {
                if ($pay->file && file_exists(storage_path('app/public/' . $pay->file))) {
                    unlink(storage_path('app/public/' . $pay->file));
                }
                $filePath = $request->file('file')->store('transports/invoices/payment', 'public');
                $pay->file = $filePath;
            }
            $pay->transport_invoice_id = $id;
            $pay->date = $request->get('date');
            $pay->paid_total = $request->get('paid_total');
            $pay->save();

            $this->syncStatus($pay->invoice);

            return redirect()->route('transport.invoice.show', $id)->with('success', 'Pembayaran berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat pembayaran, Error: '.$e->getMessage());
        }
    }

    public function destroy($id, $pay_id){
        try {
            $pay = TransportPayment::find($pay_id);
            $pay->delete();
            
            $this->syncStatus($pay->invoice);
            return redirect()->route('transport.invoice.show', $id)->with('success', 'Pembayaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus pembayaran, Error: '.$e->getMessage());
        }
    }

    private function syncStatus($invoice){
        try {
            if($invoice->remaining_payment == $invoice->transport->total_price_taxed){
                $invoice->payment_status = '0';
            }else if($invoice->remaining_payment < $invoice->transport->total_price_taxed && $invoice->remaining_payment > 0){
                $invoice->payment_status = '1';
            }else if($invoice->remaining_payment == 0){
                $invoice->payment_status = '2';
            }
            return $invoice->save();
        } catch (\Exception $e) {
            return $e;
        }
    }
}
