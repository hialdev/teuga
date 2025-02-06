<?php

namespace App\Http\Controllers;

use App\Models\TransportInvoice;
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
}
