<?php

namespace App\Http\Controllers;

use App\Models\CustomOrder;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function preview(Request $request)
    {
        $request->validate([
            'bladePath' => 'required|string',      // Pastikan path view valid
            'id' => 'required|string',             // ID sebagai identifikasi data
            'data' => 'nullable|array',            // Data yang akan dikirim ke view
        ]);

        $bladePath = $request->get('bladePath');
        $data = $request->get('data', []);        // Default data adalah array kosong jika tidak ada
        $id = $request->get('id');

        $kop_image = filePath(setting('letter.background'));

        $pdf = Pdf::setOption(['defaultFont' => 'serif', 'isRemoteEnabled'=> true])->loadView($bladePath, [
            'id' => $id,
            'data' => $data,
            'kop_image' => $kop_image,
        ])->setPaper('a4');

        return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf');                   // Mengembalikan file PDF sebagai stream
    }


    public function download($bladePath, $type, $id)
    {
        if (!view()->exists($bladePath)) {
            abort(404, 'View not found');
        }
        $data = $this->getDataByType($type, $id);
        if (!$data) {
            abort(404, 'Data not found for the given type and ID.');
        }
        $fileName = str_replace('.', '_', $bladePath) . '.pdf';
        $pdf = Pdf::loadView($bladePath, ['data' => $data]);
        return $pdf->download($fileName);
    }

    public function debug($id){
        $kop_image = filePath(setting('letter.background'));
        return view('pdf.spk', compact('id', 'kop_image'));
    }

    protected function getDataByType($type, $id)
    {
        switch ($type) {
            case 'order':
                return Order::find($id);
            case 'custom':
                return CustomOrder::find($id);
            default:
                return null; // Jika tipe tidak valid
        }
    }

}
