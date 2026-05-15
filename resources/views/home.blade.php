@extends('layouts.app')

@section('content')
    <section class="stack">
        <div>
            <h1>Find trusted local businesses</h1>
            <p class="muted">Search restaurants, salons, stores, hotels, clinics, and services in your community.</p>
        </div>

        <form class="toolbar form-grid" method="get" action="{{ route('home') }}">
            <label>Business or service
                <input name="search" value="{{ request('search') }}" placeholder="Coffee shop, dental clinic, repair">
            </label>
            <label>Category
                <select name="category">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Location
                <input name="location" value="{{ request('location') }}" placeholder="City or address">
            </label>
            <div style="align-self:end" class="actions">
                <button>Search</button>
                <a class="button secondary" href="{{ route('home') }}">Reset</a>
            </div>
        </form>
    </section>

    <section class="cards">
        @forelse($businesses as $business)
            <article class="card stack">
                @if($business->photos->first())
                    <img class="thumb" src="{{ asset('storage/'.$business->photos->first()->path) }}" alt="{{ $business->name }}">
                @endif
                <div>
                    <span class="badge">{{ $business->category->name }}</span>
                    <h2>{{ $business->name }}</h2>
                    <p class="muted">{{ $business->city ?: $business->address }}</p>
                    <p>{{ str($business->description)->limit(130) }}</p>
                </div>
                <a class="button" href="{{ route('businesses.show', $business) }}">View Details</a>
            </article>
        @empty
            <div class="panel">No approved businesses matched your search.</div>
        @endforelse
    </section>

    <div class="pagination">{{ $businesses->links() }}</div>
@endsection
