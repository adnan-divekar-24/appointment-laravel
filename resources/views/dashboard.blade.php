@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <a href="{{ route('staff.index') }}" class="text-decoration-none">
                <div class="card text-center shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title">Staff Management</h3>
                        <p class="card-text">Manage staff details, leaves, and departments.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('appointments.index') }}" class="text-decoration-none">
                <div class="card text-center shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title">Appointments</h3>
                        <p class="card-text">View and manage client appointments.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
