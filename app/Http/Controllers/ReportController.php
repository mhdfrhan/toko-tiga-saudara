<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\LaporanSupplier;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function suppliers()
    {
        return view('reports.suppliers');
    }

    public function suppliersShow($report)
    {
        $report = LaporanSupplier::with(['supplier', 'user'])->findOrFail($report);

        return view('reports.suppliers-show', [
            'report' => $report
        ]);
    }

    public function suppliersPrint(LaporanSupplier $report)
    {
        $details = BarangMasuk::query()
            ->with(['detail.produk', 'user'])
            ->where('supplier_id', $report->supplier_id)
            ->whereBetween('tanggal', [
                $report->tanggal_awal,
                $report->tanggal_akhir
            ])
            ->latest()
            ->get();

        return view('reports.print.supplier', [
            'report' => $report->load(['supplier', 'user']),
            'details' => $details
        ]);
    }
}
