<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $activeOffers = Offer::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with('store')
            ->latest()
            ->get();

        $upcomingOffers = Offer::where('start_date', '>', now())
            ->with('store')
            ->orderBy('start_date')
            ->take(6)
            ->get();

        return view('offers.index', compact('activeOffers', 'upcomingOffers'));
    }
}