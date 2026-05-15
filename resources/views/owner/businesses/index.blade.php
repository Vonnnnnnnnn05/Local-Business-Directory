@extends('layouts.app')

@section('content')
    <div class="actions" style="justify-content:space-between;margin-bottom:16px">
        <div>
            <h1>Owner Dashboard</h1>
            <p class="muted">Manage your local business listings.</p>
        </div>
        <a class="button" href="{{ route('owner.businesses.create') }}">Add Business</a>
    </div>

    <table>
        <thead><tr><th>Business</th><th>Category</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($businesses as $business)
            <tr>
                <td>{{ $business->name }}<br><span class="muted">{{ $business->address }}</span></td>
                <td>{{ $business->category->name }}</td>
                <td><span class="badge {{ $business->status }}">{{ ucfirst($business->status) }}</span></td>
                <td>{{ $business->updated_at->format('M d, Y') }}</td>
                <td class="actions">
                    <a class="button secondary" href="{{ route('businesses.show', $business) }}">View</a>
                    <a class="button" href="{{ route('owner.businesses.edit', $business) }}">Edit</a>
                    <form method="post" action="{{ route('owner.businesses.destroy', $business) }}">@csrf @method('delete')<button class="danger" onclick="return confirm('Delete this listing?')">Delete</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No businesses yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $businesses->links() }}</div>
@endsection
