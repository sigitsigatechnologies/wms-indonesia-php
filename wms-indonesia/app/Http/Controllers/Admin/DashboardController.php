<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SaleService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(): View
    {
        $stats = $this->saleService->getStats();

        return view('admin.dashboard', compact('stats'));
    }
}
