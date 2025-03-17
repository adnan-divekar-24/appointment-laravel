@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Leave Management</h2>

    <!-- Filters -->
    <form action="{{ route('leaves.index') }}" method="GET" class="mb-4 d-flex gap-2">
        <select name="staff_id" class="form-control">
            <option value="">All Staff</option>
            @foreach($staff as $s)
                <option value="{{ $s->id }}" {{ request('staff_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
        </select>

        <select name="month" class="form-control">
            <option value="">All Months</option>
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::createFromFormat('m', $m)->format('F') }}
                </option>
            @endfor
        </select>

        <select name="leave_type" class="form-control">
            <option value="">All Leave Types</option>
            <option value="annual" {{ request('leave_type') == 'annual' ? 'selected' : '' }}>Annual</option>
            <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>Sick</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Reset</a>
    </form>

    <!-- Add Leave Button -->
    <a href="{{ route('leaves.create') }}" class="btn btn-success mb-3">Apply Leave</a>

    <!-- Leave List -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Staff</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Days</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaves as $leave)
                <tr>
                    <td>{{ $leave->staff ? $leave->staff->name : 'N/A' }}</td>
                    <td>{{ ucfirst($leave->leave_type) }}</td>
                    <td>{{ $leave->start_date }}</td>
                    <td>{{ $leave->end_date }}</td>
                    <td>{{ $leave->total_days }}</td>
                    <td>
                        <a href="{{ route('leaves.edit', $leave->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Are you sure you want to delete this leave?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No leave records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $leaves->links() }}
    </div>
</div>
@endsection
