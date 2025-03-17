@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Deleted Staff</h2>
    <a href="{{ route('staff.index') }}" class="btn btn-secondary mb-3">Back to Staff List</a>

    <div class="card">
        <div class="card-body">
            @if($deletedStaff->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deletedStaff as $staff)
                            <tr>
                                <td>{{ $staff->name }}</td>
                                <td>{{ $staff->email }}</td>
                                <td>{{ $staff->phone }}</td>
                                <td>{{ $staff->department->name ?? 'N/A' }}</td>
                                <td>{{ $staff->deleted_at->format('d-m-Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('staff.restore', $staff->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                    </form>

                                    <form action="{{ route('staff.forceDelete', $staff->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure? This action is irreversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete Permanently</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $deletedStaff->links() }} <!-- Pagination -->
            @else
                <p class="text-center">No deleted staff found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
