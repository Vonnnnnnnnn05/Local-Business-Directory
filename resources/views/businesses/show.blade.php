@extends('layouts.app')

@section('content')
    <section class="stack">
        <div>
            <span class="badge {{ $business->status }}">{{ ucfirst($business->status) }}</span>
            <span class="badge">{{ $business->category->name }}</span>
            <h1>{{ $business->name }}</h1>
            <p class="muted">{{ $business->address }} {{ $business->city ? ', '.$business->city : '' }}</p>
        </div>

        @if($business->photos->isNotEmpty())
            <div class="photo-grid">
                @foreach($business->photos as $photo)
                    <img src="{{ asset('storage/'.$photo->path) }}" alt="{{ $photo->caption ?: $business->name }}">
                @endforeach
            </div>
        @endif

        <div class="grid">
            <section class="panel">
                <h2>Business Information</h2>
                <p>{{ $business->description }}</p>
                <p><strong>Contact:</strong> {{ $business->contact_number }}</p>
                @if($business->email)<p><strong>Email:</strong> {{ $business->email }}</p>@endif
            </section>
            <section class="panel">
                <h2>Business Hours</h2>
                @forelse($business->hours as $hour)
                    <p><strong>{{ $hour->day }}:</strong> {{ $hour->is_closed ? 'Closed' : (($hour->opens_at ?: 'Open').' - '.($hour->closes_at ?: 'Close')) }}</p>
                @empty
                    <p class="muted">No business hours listed.</p>
                @endforelse
            </section>
        </div>

        <section class="panel">
            <h2>Services Offered</h2>
            <div class="grid">
                @forelse($business->services as $service)
                    <div>
                        <h3>{{ $service->name }}</h3>
                        <p class="muted">{{ $service->description }}</p>
                    </div>
                @empty
                    <p class="muted">No services listed.</p>
                @endforelse
            </div>
        </section>
    </section>
@endsection
