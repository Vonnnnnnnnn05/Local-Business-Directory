<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OwnerBusinessController extends Controller
{
    public function index(): View
    {
        $businesses = auth()->user()->businesses()->with('category')->latest()->paginate(10);

        return view('owner.businesses.index', compact('businesses'));
    }

    public function create(): View
    {
        return view('owner.businesses.create', [
            'business' => new Business(),
            'categories' => Category::orderBy('name')->get(),
            'days' => $this->days(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['owner_id'] = auth()->id();
        $data['slug'] = Str::slug($data['name']).'-'.Str::random(6);
        $data['status'] = 'pending';

        $business = Business::create($data);
        $this->syncDetails($request, $business);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'business.created',
            'description' => "Submitted {$business->name} for approval.",
        ]);

        return redirect()->route('owner.businesses.index')->with('status', 'Business submitted for admin approval.');
    }

    public function edit(Business $business): View
    {
        $this->authorizeOwner($business);

        return view('owner.businesses.edit', [
            'business' => $business->load(['services', 'hours', 'photos']),
            'categories' => Category::orderBy('name')->get(),
            'days' => $this->days(),
        ]);
    }

    public function update(Request $request, Business $business): RedirectResponse
    {
        $this->authorizeOwner($business);
        $business->update($this->validated($request));
        $this->syncDetails($request, $business);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'business.updated',
            'description' => "Updated {$business->name}.",
        ]);

        return redirect()->route('owner.businesses.index')->with('status', 'Business listing updated.');
    }

    public function destroy(Business $business): RedirectResponse
    {
        $this->authorizeOwner($business);
        $business->delete();

        return redirect()->route('owner.businesses.index')->with('status', 'Business listing deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'description' => ['required', 'string', 'min:20'],
            'services' => ['nullable', 'array'],
            'services.*.name' => ['nullable', 'string', 'max:120'],
            'services.*.description' => ['nullable', 'string', 'max:500'],
            'hours' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function syncDetails(Request $request, Business $business): void
    {
        $business->services()->delete();
        foreach ($request->input('services', []) as $service) {
            if (! empty($service['name'])) {
                $business->services()->create($service);
            }
        }

        $business->hours()->delete();
        foreach ($this->days() as $day) {
            $hour = $request->input("hours.{$day}", []);
            $business->hours()->create([
                'day' => $day,
                'opens_at' => empty($hour['is_closed']) ? ($hour['opens_at'] ?? null) : null,
                'closes_at' => empty($hour['is_closed']) ? ($hour['closes_at'] ?? null) : null,
                'is_closed' => ! empty($hour['is_closed']),
            ]);
        }

        foreach ($request->file('photos', []) as $photo) {
            $business->photos()->create(['path' => $photo->store('business-photos', 'public')]);
        }
    }

    private function authorizeOwner(Business $business): void
    {
        abort_unless($business->owner_id === auth()->id(), 403);
    }

    private function days(): array
    {
        return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    }
}
