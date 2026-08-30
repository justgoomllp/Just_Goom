<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\CategoryCatalogService;

class CategoryController extends Controller
{
    public function __construct(private CategoryCatalogService $categoryCatalogService)
    {
    }

    public function index()
    {
        $stats = $this->categoryCatalogService->stats();

        return view('front.pages.categories', [
            'categorySectors' => $this->categoryCatalogService->sectorsForFrontend(),
            'catalogStats' => $stats,
        ]);
    }
}
