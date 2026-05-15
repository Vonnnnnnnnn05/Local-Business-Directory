<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $businesses = Business::with(['category', 'photos'])
            ->approved()
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search): void {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $request->category)))
            ->when($request->filled('location'), function ($query) use ($request): void {
                $location = $request->string('location')->toString();
                $query->where(function ($inner) use ($location): void {
                    $inner->where('address', 'like', "%{$location}%")
                        ->orWhere('city', 'like', "%{$location}%");
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('home', compact('businesses', 'categories'));
    }
}
