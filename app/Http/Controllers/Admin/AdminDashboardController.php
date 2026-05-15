<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Business;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalBusinesses' => Business::count(),
            'pendingBusinesses' => Business::where('status', 'pending')->count(),
            'approvedBusinesses' => Business::where('status', 'approved')->count(),
            'categories' => Category::withCount('businesses')->orderBy('name')->get(),
            'recentBusinesses' => Business::with(['owner', 'category'])->latest()->take(8)->get(),
            'activities' => ActivityLog::with('user')->latest()->take(8)->get(),
        ]);
    }

    public function updateStatus(Business $business, string $status): RedirectResponse
    {
        abort_unless(in_array($status, ['pending', 'approved', 'rejected'], true), 404);

        $business->update(['status' => $status]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'business.status',
            'description' => "Marked {$business->name} as {$status}.",
        ]);

        return back()->with('status', 'Business status updated.');
    }
}
