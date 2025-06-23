<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

trait ExportableTrait
{
    public function handleExcelExport(Request $request, $exportClass, $filename)
    {
        $allData = $request->get('scope', 'current') === 'all';
        
        $export = new $exportClass($request, $allData);
        
        $scope = $allData ? 'Complet' : 'Page-Actuelle';
        $timestamp = now()->format('Y-m-d_H-i-s');
        $finalFilename = "{$filename}_{$scope}_{$timestamp}.xlsx";
        
        return Excel::download($export, $finalFilename);
    }

    public function handlePdfExport(Request $request, $viewName, $filename, $dataCallback)
    {
        $allData = $request->get('scope', 'current') === 'all';
        
        // Get data using the callback
        $data = $dataCallback($request, $allData);
        
        $scope = $allData ? 'Complet' : 'Page-Actuelle';
        $timestamp = now()->format('Y-m-d_H-i-s');
        $finalFilename = "{$filename}_{$scope}_{$timestamp}.pdf";
        
        $pdf = Pdf::loadView($viewName, $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download($finalFilename);
    }
}
