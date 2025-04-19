<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    protected $paymentMethod, $payStatus, $startDate, $endDate;

    public function __construct($paymentMethod, $payStatus, $startDate, $endDate)
    {
        $this->paymentMethod = $paymentMethod;
        $this->payStatus = $payStatus;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Sale::with('order') // Ensure order relationship is loaded
            ->when($this->paymentMethod, function ($query) {
                return $query->where('payment_method', $this->paymentMethod);
            })
            ->when(!is_null($this->payStatus), function ($query) {
                return $query->whereHas('order', function ($query) {
                    return $query->where('pay_status', $this->payStatus);
                });
            })
            ->when($this->startDate, function ($query) {
                return $query->whereDate('completed_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                return $query->whereDate('completed_at', '<=', $this->endDate);
            })
            ->latest()
            ->get(['id', 'order_id', 'payment_method', 'total_amount', 'completed_at']); // Removed `pay_status`
    }

    public function headings(): array
    {
        return [
            'ID',
            'Order ID',
            'Payment Method',
            'Total Amount',
            'Completed At',
        ];
    }
}
