<?php

namespace App\Http\Controllers\Dashboard;

use App\Constants\Abilities;
use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Models\Invoice;
use App\ViewModels\Dashboard\DashboardViewModel;
use App\ViewModels\Invoice\InvoiceMicrositeIndexViewModel;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request, InvoiceMicrositeIndexViewModel $viewModelMicrosite): Response
    {
        $this->authorize(Abilities::VIEW_ANY->value, Invoice::class);

        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->subMonth()->startOfDay()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfDay()))->endOfDay();
        $micrositeIdArray = $request->input('microsite_id');
        $microsite_id = $micrositeIdArray['id'] ?? null;

        $viewModel = new DashboardViewModel($startDate, $endDate, $microsite_id);

        return Inertia::render('Dashboard', [
            'totalPaid' => $viewModel->getTotalPaid(),
            'totalPending' => $viewModel->getTotalPending(),
            'totalOverdue' => $viewModel->getTotalOverdue(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'microsites' => $viewModelMicrosite->getMicrosites(),
            'selectedMicrosite' => $micrositeIdArray,
        ]);
    }

}
