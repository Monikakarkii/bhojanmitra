<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of the sales records with filter options.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Filter parameters
        $paymentMethod = $request->input('payment_method');
        $payStatus = 1; // Always filter for paid orders
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch sales where orders have pay_status = 1
        $sales = Sale::whereHas('order', function ($query) use ($payStatus) {
                $query->where('pay_status', $payStatus);
            })
            ->when($paymentMethod, function ($query) use ($paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('completed_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('completed_at', '<=', $endDate);
            })
            ->latest()
            ->paginate(10);

        return view('backend.sales.index', compact('sales', 'paymentMethod', 'startDate', 'endDate'));
    }


    /**
     * Download sales records as a CSV file with filter.
     */
    public function download(Request $request)
    {
        $paymentMethod = $request->input('payment_method');
        $payStatus = $request->input('pay_status'); // New filter for payment status
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Generate dynamic filename based on filters
        $fileName = 'sales_records_';
        if ($paymentMethod) {
            $fileName .= $paymentMethod . '_';
        }
        if (!is_null($payStatus)) {
            $fileName .= ($payStatus ? 'paid_' : 'unpaid_');
        }
        if ($startDate) {
            $fileName .= 'from_' . $startDate . '_';
        }
        if ($endDate) {
            $fileName .= 'to_' . $endDate . '_';
        }
        $fileName .= now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new SalesExport($paymentMethod, $payStatus, $startDate, $endDate), $fileName);
    }
}
