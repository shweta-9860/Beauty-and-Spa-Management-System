<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\PrinterSetting;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    /**
     * Show thermal receipt
     */
    public function index($id)
    {
        // Fetch bill with related data
        $bill = Bill::with([
            'salon',
            'branch',
            'customer',
            'items.staff',
            'payments',
        ])->findOrFail($id);

        // Get salon details linked to this bill
        $salon = $bill->salon;

        // Get printer settings for this salon
        $printerSetting = null;

        if ($salon) {
            $printerSetting = PrinterSetting::where(
                'salon_id',
                $salon->id
            )->first();
        }

        // Send data to print view
        return view('print', compact(
            'bill',
            'salon',
            'printerSetting'
        ));
    }
}