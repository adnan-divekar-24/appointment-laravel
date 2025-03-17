{{-- resources/views/staff/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Staff Management</h2>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('staff.create') }}" class="btn btn-primary">Add Staff</a>
            <a href="{{ route('staff.deleted') }}" class="btn btn-secondary">Deleted Staff</a>
            <a href="{{ route('leaves.index') }}" class="btn btn-success">Manage Leaves</a>
        </div>
    </div>


    <form method="GET" action="{{ route('staff.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="department" class="form-control">
                    <option value="">All Departments</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-info">Filter</button>
                <a href="{{ route('staff.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Sub-Department</th>
                <th>Leaves (Sick/Annual)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->phone }}</td>
                    <td>{{ $member->department->name ?? 'Unknown' }}</td>
                    <td>{{ $member->subDepartment->name ?? 'Unknown' }}</td>
                    <td>{{ $member->sick_leave_balance }}/{{ $member->annual_leave_balance }}</td>
                    <td>
                        <a href="{{ route('staff.edit', $member->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('staff.destroy', $member->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="d-flex justify-content-center">
        {{ $staff->links() }}
    </div>
</div>
@endsection
