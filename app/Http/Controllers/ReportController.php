<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\LaporanPenjualan;
use App\Models\LaporanSupplier;
use App\Models\Penjualan;
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

    public function sales()
    {
        return view('reports.sales');
    }

    public function salesShow($report)
    {
        $report = LaporanPenjualan::with(['user'])->findOrFail($report);
        return view('reports.sales-show', [
            'report' => $report
        ]);
    }

    public function salesPrint(LaporanPenjualan $report)
    {
        $details = Penjualan::query()
            ->with(['user', 'detail.produk'])
            ->whereBetween('tanggal', [
                $report->tanggal_awal,
                $report->tanggal_akhir
            ])
            ->latest()
            ->get()
            ->map(function ($penjualan) {
                $penjualan->laba_kotor = $penjualan->detail->sum(function ($detail) {
                    return ($detail->harga - $detail->produk->harga_beli) * $detail->jumlah;
                });
                return $penjualan;
            });

        return view('reports.print.penjualan', [
            'report' => $report,
            'details' => $details
        ]);
    }
}
