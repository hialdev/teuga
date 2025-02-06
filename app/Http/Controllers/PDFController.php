<?php

namespace App\Http\Controllers;

use App\Models\CustomOrder;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function preview($bladePath, $type, $id)
    {
        if (!view()->exists($bladePath)) {
            abort(404, 'View not found');
        }

        $data = $this->getDataByType($type, $id);
        if (!$data) {
            abort(404, 'Data not found for the given type and ID.');
        }
        $pdf = Pdf::loadView($bladePath, ['data' => $data]);
        return $pdf->stream();
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
