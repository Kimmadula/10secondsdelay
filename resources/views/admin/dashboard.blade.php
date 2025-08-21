
@extends('layouts.app')

@section('content')
    <h1>Admin Dashboard</h1>
    <h2>Pending Users</h2>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border-bottom: 1px solid #ccc; text-align:left; padding:8px;">Name</th>
                <th style="border-bottom: 1px solid #ccc; text-align:left; padding:8px;">Email</th>
                <th style="border-bottom: 1px solid #ccc; text-align:left; padding:8px;">Photo</th>
                <th style="border-bottom: 1px solid #ccc; text-align:left; padding:8px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingUsers as $user)
                <tr>
                    <td style="padding:8px;">{{ $user->name }}</td>
                    <td style="padding:8px;">{{ $user->email }}</td>
                    <td style="padding:8px;">
                        @if($user->photo)
                            <a href="{{ asset('storage/' . $user->photo) }}" target="_blank">
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                            </a>
                        @else
                            <span>No photo</span>
                        @endif
                    </td>
                    <td style="padding:8px;">
                        <a href="{{ route('admin.user.show', $user->id) }}" style="padding:4px 12px; background:#4299e1; color:#fff; border:none; border-radius:4px; text-decoration:none; margin-right:4px;">
                            View Details
                        </a>
                        @if($user->status === 'pending')
                            <form action="{{ route('admin.approve', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="padding:4px 12px; background:#38b2ac; color:#fff; border:none; border-radius:4px; cursor:pointer;">
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.reject', $user->id) }}" method="POST" style="display:inline; margin-left:4px;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="padding:4px 12px; background:#e53e3e; color:#fff; border:none; border-radius:4px; cursor:pointer;">
                                    Reject
                                </button>
                            </form>
                        @else
                            <span style="color:green;">Approved</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding:8px; text-align:center;">No pending users.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection