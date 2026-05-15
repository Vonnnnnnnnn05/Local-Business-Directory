@extends('layouts.app')

@section('content')
    <h1>User Management</h1>
    <table>
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Businesses</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <form id="role-{{ $user->id }}" method="post" action="{{ route('admin.users.role', $user) }}">
                        @csrf @method('patch')
                        <select name="role">
                            @foreach(['user', 'owner', 'admin'] as $role)
                                <option value="{{ $role }}" @selected($user->role === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td>{{ $user->businesses_count }}</td>
                <td><button form="role-{{ $user->id }}">Update Role</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $users->links() }}</div>
@endsection
