<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\View\View;

class BusinessController extends Controller
{
    public function show(Business $business): View
    {
        abort_unless($business->status === 'approved' || auth()->user()?->isAdmin() || auth()->id() === $business->owner_id, 404);

        $business->load(['category', 'owner', 'photos', 'services', 'hours']);

        return view('businesses.show', compact('business'));
    }
}
