@extends('layouts.app')

@section('content')
    <h1>Admin Dashboard</h1>
    <p class="muted">Monitor registered users, businesses, approvals, and category activity.</p>

    <section class="stats">
        <div class="stat"><strong>{{ $totalUsers }}</strong> Users</div>
        <div class="stat"><strong>{{ $totalBusinesses }}</strong> Businesses</div>
        <div class="stat"><strong>{{ $pendingBusinesses }}</strong> Pending</div>
        <div class="stat"><strong>{{ $approvedBusinesses }}</strong> Approved</div>
    </section>

    <div class="actions" style="margin-bottom:16px">
        <a class="button" href="{{ route('admin.categories.index') }}">Manage Categories</a>
        <a class="button secondary" href="{{ route('admin.users.index') }}">Manage Users</a>
    </div>

    <section class="grid">
        <div class="panel">
            <h2>Businesses by Category</h2>
            @foreach($categories as $category)
                <p><strong>{{ $category->name }}:</strong> {{ $category->businesses_count }}</p>
            @endforeach
        </div>
        <div class="panel">
            <h2>Recent Activity</h2>
            @forelse($activities as $activity)
                <p><strong>{{ $activity->action }}</strong><br><span class="muted">{{ $activity->description }}</span></p>
            @empty
                <p class="muted">No activity recorded yet.</p>
            @endforelse
        </div>
    </section>

    <section style="margin-top:18px">
        <h2>Recent Businesses</h2>
        <table>
            <thead><tr><th>Business</th><th>Owner</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @foreach($recentBusinesses as $business)
                <tr>
                    <td><a href="{{ route('businesses.show', $business) }}">{{ $business->name }}</a></td>
                    <td>{{ $business->owner->name }}</td>
                    <td>{{ $business->category->name }}</td>
                    <td><span class="badge {{ $business->status }}">{{ ucfirst($business->status) }}</span></td>
                    <td class="actions">
                        @foreach(['approved', 'pending', 'rejected'] as $status)
                            <form method="post" action="{{ route('admin.businesses.status', [$business, $status]) }}">@csrf @method('patch')<button class="{{ $status === 'rejected' ? 'danger' : ($status === 'pending' ? 'warning' : '') }}">{{ ucfirst($status) }}</button></form>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
@endsection
