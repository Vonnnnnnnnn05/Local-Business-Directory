@extends('layouts.app')

@section('content')
    <h1>Category Management</h1>
    <div class="panel" style="margin-bottom:18px">
        <form class="form-grid" method="post" action="{{ route('admin.categories.store') }}">
            @csrf
            <label>Name <input name="name" required></label>
            <label>Description <input name="description"></label>
            <div style="align-self:end"><button>Add Category</button></div>
        </form>
    </div>

    <table>
        <thead><tr><th>Name</th><th>Description</th><th>Businesses</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>
                    <form id="category-{{ $category->id }}" method="post" action="{{ route('admin.categories.update', $category) }}">
                        @csrf @method('put')
                        <input name="name" value="{{ $category->name }}" required>
                    </form>
                </td>
                <td><input form="category-{{ $category->id }}" name="description" value="{{ $category->description }}"></td>
                <td>{{ $category->businesses_count }}</td>
                <td class="actions">
                    <button form="category-{{ $category->id }}">Save</button>
                    <form method="post" action="{{ route('admin.categories.destroy', $category) }}">@csrf @method('delete')<button class="danger" onclick="return confirm('Delete this category?')">Delete</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $categories->links() }}</div>
@endsection
