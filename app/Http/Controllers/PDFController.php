<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function download(Request $request, Car $car)
    {
        abort_unless($car->user_id === $request->user()->id, 403);

        $pdf = Pdf::loadView('pdf.car', [
            'car' => $car,
            'user' => $request->user(),
        ])->setPaper('a4', 'portrait');

        $filename = 'wheely-good-cars-' . ($car->license_plate ?: $car->id) . '-' . $car->make . '-' . ($car->model ?: '') . '.pdf';

        return $pdf->download($filename);
    }
}
