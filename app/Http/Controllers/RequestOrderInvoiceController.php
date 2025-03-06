<?php

namespace App\Http\Controllers;

use App\Models\RequestOrderInvoice;
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

    public function destroy($id){
        try{
            $roInvoice = RequestOrderInvoice::findOrFail($id);
            if($roInvoice->trx)
                return redirect()->back()->with('error', 'Tidak dapat menghapus Invoice, Error: Invoice sudah di catat ke Jurnal Akutansi.');
            if($roInvoice->payments->count() > 0)
                return redirect()->back()->with('error', 'Tidak dapat menghapus Invoice, Error: Terdapat Pembayaran yang dilakukan didalamnya.');

            $roInvoice->delete();
        }catch (\Exception $e){
            return redirect()->back()->with('error', 'Gagal menghapus Invoice, Error: '.$e->getMessage());
        }
    }
}
