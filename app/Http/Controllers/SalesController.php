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
        // Filter sales records based on payment_method, pay_status, and date range
        $paymentMethod = $request->input('payment_method');
        $payStatus = $request->input('pay_status'); // New filter for payment status
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $sales = Sale::when($paymentMethod, function ($query) use ($paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->when(!is_null($payStatus), function ($query) use ($payStatus) { // Filter for paid/unpaid orders
                return $query->where('pay_status', $payStatus);
            })
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('completed_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('completed_at', '<=', $endDate);
            })
            ->latest()
            ->paginate(10);

        return view('backend.sales.index', compact('sales', 'paymentMethod', 'payStatus', 'startDate', 'endDate'));
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
