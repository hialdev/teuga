<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderInvoice;
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

    public function destroy($id){
        try{
            $poInvoice = PurchaseOrderInvoice::findOrFail($id);
            if($poInvoice->trx)
                return redirect()->back()->with('error', 'Tidak dapat menghapus Invoice, Error: Invoice sudah di catat ke Jurnal Akutansi.');
            if($poInvoice->payments->count() > 0)
                return redirect()->back()->with('error', 'Tidak dapat menghapus Invoice, Error: Terdapat Pembayaran yang dilakukan didalamnya.');

            $poInvoice->delete();
        }catch (\Exception $e){
            return redirect()->back()->with('error', 'Gagal menghapus Invoice, Error: '.$e->getMessage());
        }
    }
}
