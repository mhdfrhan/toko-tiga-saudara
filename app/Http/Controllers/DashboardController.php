<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::query()
        ->with('user') 
        ->latest('created_at') 
        ->limit(5)
        ->get();

    return view('dashboard', compact('penjualans'));
    }

    public function monitoring(Request $request)
    {
        $period = in_array($request->get('period'), ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'])
            ? $request->get('period')
            : 'daily';
        $year = $request->get('year', now()->year);

        $dateRange = $this->getDateRange($period, $request->get('start_date'), $request->get('end_date'), $year);

        $salesAndProfitTrend = DB::table('penjualan as p')
            ->join('penjualan_detail as pd', 'p.id', '=', 'pd.penjualan_id')
            ->join('produk as pr', 'pr.id', '=', 'pd.produk_id')
            ->select([
                DB::raw($this->getPeriodGrouping($period, 'p.tanggal')),
                DB::raw($this->getPeriodLabel($period, 'p.tanggal')),
                DB::raw('COUNT(DISTINCT p.id) as total_transactions'),
                DB::raw('SUM(p.total) as total_sales'),
                DB::raw('SUM((pd.harga - pr.harga_beli) * pd.jumlah) as total_profit')
            ])
            ->whereBetween('p.tanggal', [$dateRange['start'], $dateRange['end']])
            ->groupByRaw($this->getPeriodGroupBy($period, 'p.tanggal'))
            ->orderByRaw('group_key ASC')
            ->get();

        $topProducts = DB::table('penjualan_detail as pd')
            ->join('produk as pr', 'pr.id', '=', 'pd.produk_id')
            ->join('penjualan as p', 'p.id', '=', 'pd.penjualan_id')
            ->select([
                'pr.id',
                'pr.nama',
                DB::raw('SUM(pd.jumlah) as total_qty'),
                DB::raw('SUM(pd.subtotal) as total_sales'),
                DB::raw('SUM((pd.harga - pr.harga_beli) * pd.jumlah) as total_profit'),
                DB::raw('CASE WHEN SUM(pd.subtotal) > 0 THEN (SUM((pd.harga - pr.harga_beli) * pd.jumlah) / SUM(pd.subtotal)) * 100 ELSE 0 END as profit_margin')
            ])
            ->whereBetween('p.tanggal', [$dateRange['start'], $dateRange['end']])
            ->groupBy('pr.id', 'pr.nama')
            ->orderByDesc('total_profit')
            ->limit(10)
            ->get();

        $supplierAnalysis = DB::table('barang_masuk as bm')
            ->join('suppliers as s', 's.id', '=', 'bm.supplier_id')
            ->select([
                's.id',
                's.nama',
                DB::raw('COUNT(DISTINCT bm.id) as total_transactions'),
                DB::raw('SUM(bm.total) as total_amount')
            ])
            ->whereBetween('bm.tanggal', [$dateRange['start'], $dateRange['end']])
            ->groupBy('s.id', 's.nama')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        $lowStockProducts = DB::table('produk')
            ->select([
                'id',
                'nama',
                'stok',
                DB::raw('COALESCE(min_stok, 10) as min_stok'),
                DB::raw('stok - COALESCE(min_stok, 10) as stock_diff')
            ])
            ->whereRaw('stok <= COALESCE(min_stok, 10)')
            ->orderBy('stock_diff')
            ->limit(10)
            ->get();

            $topCategories = DB::table('penjualan_detail as pd')
            ->join('produk as pr', 'pr.id', '=', 'pd.produk_id')
            ->join('kategori as k', 'k.id', '=', 'pr.kategori_id')
            ->join('penjualan as p', 'p.id', '=', 'pd.penjualan_id')
            ->select([
                'k.id',
                'k.nama',
                DB::raw('COUNT(pd.id) as total_items'),
                DB::raw('SUM(pd.subtotal) as total_sales')
            ])
            ->whereBetween('p.tanggal', [$dateRange['start'], $dateRange['end']])
            ->groupBy('k.id', 'k.nama')
            ->orderByDesc('total_items')
            ->limit(5) 
            ->get();

        $summary = [
            'total_sales' => $salesAndProfitTrend->sum('total_sales'),
            'total_profit' => $salesAndProfitTrend->sum('total_profit'),
            'total_transactions' => $salesAndProfitTrend->sum('total_transactions'),
            'average_profit_margin' => $salesAndProfitTrend->sum('total_sales') > 0
                ? ($salesAndProfitTrend->sum('total_profit') / $salesAndProfitTrend->sum('total_sales')) * 100
                : 0,
        ];

        return view('monitoring', compact(
            'salesAndProfitTrend',
            'topProducts',
            'supplierAnalysis',
            'topCategories', 
            'lowStockProducts',
            'summary',
            'period',
            'dateRange',
            'year'
        ));
    }

    private function getDateRange($period, $startDate = null, $endDate = null, $year = null)
    {
        $start = $startDate ? Carbon::parse($startDate) : now();
        $end = $endDate ? Carbon::parse($endDate) : now();

        switch ($period) {
            case 'daily':
                return [
                    'start' => $start->copy()->startOfDay(),
                    'end' => $end->copy()->endOfDay()
                ];
            case 'weekly':
                return [
                    'start' => $start->copy()->startOfWeek(),
                    'end' => $end->copy()->endOfWeek()
                ];
            case 'monthly':
            case 'quarterly':
            case 'yearly':
                return [
                    'start' => Carbon::create($year)->startOfYear(),
                    'end' => Carbon::create($year)->endOfYear()
                ];
            default:
                return [
                    'start' => now()->startOfDay(),
                    'end' => now()->endOfDay()
                ];
        }
    }

    private function getPeriodGrouping($period, $column)
    {
        switch ($period) {
            case 'daily':
                return "HOUR($column) as group_key";
            case 'weekly':
                return "DATE($column) as group_key";
            case 'monthly':
                return "MONTH($column) as group_key";
            case 'quarterly':
                return "CONCAT(YEAR($column), '-', QUARTER($column)) as group_key";
            default: // yearly
                return "YEAR($column) as group_key";
        }
    }

    private function getPeriodLabel($period, $column)
    {
        switch ($period) {
            case 'daily':
                return "DATE_FORMAT($column, '%H:00') as period";
            case 'weekly':
                return "DATE_FORMAT($column, '%d %M %Y') as period";
            case 'monthly':
                return "DATE_FORMAT($column, '%M %Y') as period";
            case 'quarterly':
                return "CONCAT('Q', QUARTER($column), ' ', YEAR($column)) as period";
            default:
                return "YEAR($column) as period";
        }
    }

    private function getPeriodGroupBy($period, $column)
    {
        switch ($period) {
            case 'daily':
                return "HOUR($column), DATE_FORMAT($column, '%H:00')";
            case 'weekly':
                return "DATE($column), DATE_FORMAT($column, '%d %M %Y')";
            case 'monthly':
                return "MONTH($column), DATE_FORMAT($column, '%M %Y')";
            case 'quarterly':
                return "CONCAT(YEAR($column), '-', QUARTER($column)), CONCAT('Q', QUARTER($column), ' ', YEAR($column))";
            default:
                return "YEAR($column), YEAR($column)";
        }
    }
}
