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
}
