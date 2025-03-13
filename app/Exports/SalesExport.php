<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;

class SalesExport implements FromCollection
{
    public function __construct($paymentMethod = null, $startDate = null, $endDate = null)
{
    $this->paymentMethod = $paymentMethod;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->paymentMethod || $this->startDate || $this->endDate) {
            return Sale::when($this->paymentMethod, function ($query) {
                return $query->where('payment_method', $this->paymentMethod);
            })
            ->when($this->startDate, function ($query) {
                return $query->whereDate('completed_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                return $query->whereDate('completed_at', '<=', $this->endDate);
            })
            ->get(['order_id', 'total_amount', 'payment_method', 'completed_at']);
        } else {
            return Sale::all(['order_id', 'total_amount', 'payment_method', 'completed_at']);
        }
    }
}
