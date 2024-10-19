<?php

namespace App\Http\Controllers\Dashboard;

use App\Constants\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\Microsite;
use App\ViewModels\Invoice\InvoiceMicrositeIndexViewModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $totalPaid = Invoice::where('status', PaymentStatus::APPROVED->value)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalPending = Invoice::where('status', PaymentStatus::PENDING->value)
            ->where('expiration_date', '>=', Carbon::now())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalOverdue = Invoice::where('status', PaymentStatus::PENDING->value)
            ->where('expiration_date', '<', Carbon::now())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return Inertia::render('Dashboard', [
            'totalPaid' => $totalPaid,
            'totalPending' => $totalPending,
            'totalOverdue' => $totalOverdue,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }


}
