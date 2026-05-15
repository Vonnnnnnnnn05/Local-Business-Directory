<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('businesses')->orderBy('name')->paginate(12),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        Category::create($data);

        return back()->with('status', 'Category created.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:categories,name,'.$category->id],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);

        return back()->with('status', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->businesses()->exists()) {
            return back()->withErrors(['category' => 'Categories with businesses cannot be deleted.']);
        }

        $category->delete();

        return back()->with('status', 'Category deleted.');
    }
}
