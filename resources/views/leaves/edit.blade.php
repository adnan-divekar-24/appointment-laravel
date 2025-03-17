@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Leave</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('leaves.update', $leave->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label for="staff_id" class="form-label">Select Staff</label>
            <select name="staff_id" class="form-control" required>
                @foreach($staff as $s)
                    <option value="{{ $s->id }}" {{ $leave->staff_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="leave_type" class="form-label">Leave Type</label>
            <select name="leave_type" class="form-control" required>
                <option value="annual" {{ $leave->leave_type == 'annual' ? 'selected' : '' }}>Annual</option>
                <option value="sick" {{ $leave->leave_type == 'sick' ? 'selected' : '' }}>Sick</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ $leave->start_date }}" required>
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ $leave->end_date }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Leave</button>
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
