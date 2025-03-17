@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Add Staff</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('staff.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Joining Date</label>
                    <input type="date" name="joining_date"  id="joining_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Annual Leave Balance</label>
                    <input type="text" id="annual_leave_balance" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sick Leave Balance</label>
                    <input type="text" id="sick_leave_balance" class="form-control" value="15" readonly>
                </div>
                

                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" id="department" class="form-control" required>
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sub-Department</label>
                    <select name="sub_department_id" id="sub_department" class="form-control" required>
                        <option value="">Select Sub-Department</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Shift Hours</label>
                    <select name="shift_hours" class="form-control">
                        <option value="9">9 Hours</option>
                        <option value="8">8 Hours</option>
                        <option value="7">7 Hours</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Add Staff</button>
                <a href="{{ route('staff.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('department').addEventListener('change', function() {
    let departmentId = this.value;
    let subDeptDropdown = document.getElementById('sub_department');

    subDeptDropdown.innerHTML = '<option value="">Loading...</option>';

    fetch(`/get-subdepartments/${departmentId}`)
        .then(response => response.json())
        .then(data => {
            subDeptDropdown.innerHTML = '<option value="">Select Sub-Department</option>';
            data.forEach(subDept => {
                let option = document.createElement('option');
                option.value = subDept.id;
                option.textContent = subDept.name;
                subDeptDropdown.appendChild(option);
            });
        })
        .catch(error => {
            subDeptDropdown.innerHTML = '<option value="">Error loading sub-departments</option>';
            console.error(error);
        });
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const joiningDateInput = document.getElementById("joining_date");
    const annualLeaveInput = document.getElementById("annual_leave_balance");

    function calculateAnnualLeave() {
        const joiningDate = new Date(joiningDateInput.value);
        const currentDate = new Date();
        
        if (isNaN(joiningDate)) {
            annualLeaveInput.value = "";
            return;
        }

        let monthsWorked = (currentDate.getFullYear() - joiningDate.getFullYear()) * 12;
        monthsWorked += currentDate.getMonth() - joiningDate.getMonth();
        if (currentDate.getDate() < joiningDate.getDate()) {
            monthsWorked -= 1;
        }
        console.log('monthsWorked '+monthsWorked);
        let totalEarnedLeave = Math.min(30 * (monthsWorked / 12), monthsWorked * 2.5); // Max 30 per year
        annualLeaveInput.value = totalEarnedLeave;
    }

    joiningDateInput.addEventListener("change", calculateAnnualLeave);
    
    // Auto calculate if editing existing staff
    if (joiningDateInput.value) {
        calculateAnnualLeave();
    }
});
</script>

@endsection
