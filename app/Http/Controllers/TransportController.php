<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    public function index(Request $request){
        $filter = (object) [
            'q' => $request->get('search') ?? '',
            'field' => $request->get('field') ?? 'code',
            'order' => $request->get('order') ? ($request->get('order') == 'newest' ? 'desc' : 'asc') : 'desc',
        ];

        $transports = Transport::query()
            ->where('code', 'LIKE', '%' . $filter->q . '%');
            $transports->orderBy($filter->field, $filter->order);

        $transports = $transports->get();
        return view('transports.index', compact('transports', 'filter'));
    }

    public function store(Request $request){
        $request->merge(['total_price' => parseRupiah($request->get('total_price'))]);
        $request->validate([
            'date' => 'nullable|date',
            'tax' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:1',
            'logistic_id' => 'required|string|exists:osano.logistics,id',
        ]);
        try {

            $transport = new Transport();
            $transport->date = $request->get('date');
            $transport->tax = $request->get('tax');
            $transport->total_price = $request->get('total_price');
            $transport->total_price_taxed = (int) $request->get('total_price') + ((int) $request->get('total_price') * ( (int) $request->get('tax') / 100 ));
            $transport->logistic_id = $request->get('logistic_id');
            $transport->save();

            return redirect()->back()->with('success', 'Pengangkutan '.$transport->code.' berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan Pengangkutan, Error: '.$e->getMessage());
        }
    }

    public function update($id, Request $request){
        $transport = Transport::find($id);
        if($transport->status != '0') return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pengangkutan, Error: Pembelian dan Pengangkutan telah diproses.');
        
        $request->merge(['total_price' => parseRupiah($request->get('total_price'))]);
        $request->validate([
            'date' => 'nullable|date',
            'tax' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:1',
            'logistic_id' => 'required|string|exists:osano.logistics,id',
        ]);
        try {
            $transport->date = $request->get('date');
            $transport->tax = $request->get('tax');
            $transport->total_price = $request->get('total_price');
            $transport->total_price_taxed = (int) $request->get('total_price') + ((int) $request->get('total_price') * ( (int) $request->get('tax') / 100 ));
            $transport->logistic_id = $request->get('logistic_id');
            $transport->save();

            return redirect()->route('transport.index')->with('success', 'Pengangkutan '.$transport->name.' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui Pengangkutan, Error: '.$e->getMessage());
        }
    }

    public function destroy($id){
        try {
            $transport = Transport::find($id);
            if($transport->status != '0') return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pengangkutan, Error: Pembelian dan Pengangkutan telah diproses.');
            $transport->delete();

            return redirect()->route('pack.index')->with('success', 'Pengangkutan '.$transport->name.' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus Pengangkutan, Error: '.$e->getMessage());
        }
    }
}
