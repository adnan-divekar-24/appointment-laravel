<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Department;
use App\Models\SubDepartment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffController extends Controller
{
    // List all staff members
    public function index(Request $request)
    {
        $query = Staff::with(['department', 'subDepartment']);

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('department') && $request->department != '') {
            $query->where('department_id', $request->department);
        }

        $staff = $query->paginate(10);
        $departments = Department::all(); // Fetch all departments

        return view('staff.index', compact('staff', 'departments'));
    }

    // Show form to create new staff member
    public function create()
    {
        $departments = Department::all();
        return view('staff.create', compact('departments'));
    }

    // Store new staff member
    public function store(Request $request)
    {
        //dd($request->all());
        // check duplicate
        $existingStaff = Staff::where('email', $request->email)
        ->orWhere('phone', $request->phone)
        ->first();

        if ($existingStaff) {
        return redirect()->back()->withErrors(['error' => 'A staff member with this email or phone already exists.'])->withInput();
        }

        try{
        $validatedData = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:staff,email',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string',
            'gender'          => 'required|in:Male,Female,Other',
            'dob'             => 'required|date',
            'joining_date'    => 'required|date',
            'department_id'   => 'required|exists:departments,id',
            'sub_department_id' => 'nullable|exists:sub_departments,id',
            'shift_hours'     => 'required|integer|in:7,8,9',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors()); // validation failed
        }
    
        // Convert dates
        $joiningDate = Carbon::parse($validatedData['joining_date']);
        $currentDate = Carbon::now();

        // print_r('joiningDateYear'.$joiningDate->year);
        // print_r(' currentDateYear'.$currentDate->year);
        // print_r(' joiningDateMonth'.$joiningDate->month);
        // print_r(' currentDateMonth'.$currentDate->month);
        // print_r(' joiningDateDay'.$joiningDate->day);
        // print_r(' currentDateDay'.$currentDate->day);die;
        // Calculate months worked
        $monthsWorked = ($currentDate->year - $joiningDate->year) * 12;
        $monthsWorked += $currentDate->month - $joiningDate->month;
        if ($currentDate->day < $joiningDate->day) {
            $monthsWorked -= 1;
        }

        // Calculate earned annual leaves (Max 30 per year)
        $totalEarnedAnnualLeaves = min(30 * ($monthsWorked / 12), $monthsWorked * 2.5);
        //print_r('totalEarnedAnnualLeaves'.$totalEarnedAnnualLeaves);die;
        // Assign leave balances
        $validatedData['annual_leave_balance'] = max(0, $totalEarnedAnnualLeaves); // Ensure no negative balance
        $validatedData['sick_leave_balance'] = 15; // Always reset to 15 per year

        // Create staff record
        Staff::create($validatedData);

        return redirect()->route('staff.index')->with('success', 'Staff member added successfully.');
    }

    // Show edit form for a staff member
    public function edit(Staff $staff)
    {
        $departments = Department::all();
        $sub_departments = SubDepartment::all();
        return view('staff.edit', compact('staff', 'departments','sub_departments'));
    }

    // Update staff member details
    public function update(Request $request, Staff $staff)
    {
        // check duplicate
        $existingStaff = Staff::where(function ($query) use ($request, $staff) {
            $query->where('email', $request->email)
                  ->orWhere('phone', $request->phone);
        })
        ->where('id', '!=', $staff->id) // current staff
        ->first();

        if ($existingStaff) {
            return redirect()->back()->withErrors(['error' => 'A staff member with this email or phone already exists.'])->withInput();
        }
        //try{
            $validatedData = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:staff,email,'.$staff->id,
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string',
            'gender'          => 'required|in:Male,Female,Other',
            'dob'             => 'required|date',
            'joining_date'    => 'required|date',
            'department_id'   => 'required|exists:departments,id',
            'sub_department_id' => 'nullable|exists:sub_departments,id',
            'shift_hours'     => 'required|integer|in:7,8,9',
        ]);

        // Check if the joining date has changed
        if ($staff->joining_date !== $validatedData['joining_date']) {
            $joiningDate = Carbon::parse($validatedData['joining_date']);
            $currentDate = Carbon::now();

            // Calculate total months worked
            $monthsWorked = ($currentDate->year - $joiningDate->year) * 12;
            $monthsWorked += ($currentDate->month - $joiningDate->month);

            // Adjust if the joining date day hasn't passed in the current month
            if ($currentDate->day < $joiningDate->day) {
                $monthsWorked -= 1;
            }

            // Calculate new annual leave balance
            $totalEarnedAnnualLeaves = min(30 * ($monthsWorked / 12), $monthsWorked * 2.5);
            $validatedData['annual_leave_balance'] = $totalEarnedAnnualLeaves;
        }

        $staff->update($validatedData);

        return redirect()->route('staff.index')->with('success', 'Staff details updated successfully.');
    }


    // Soft delete staff member
    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff deleted successfully');
    }

    // Show list of deleted staff for possible restoration
    public function deleted()
    {
        $deletedStaff = Staff::onlyTrashed()->paginate(10);
        //dd($deletedStaff);
        return view('staff.deleted', compact('deletedStaff'));
    }

    // Restore a soft deleted staff member
    public function restore($id)
    {
        $staff = Staff::onlyTrashed()->findOrFail($id);
        $staff->restore();
        return redirect()->route('staff.index')->with('success', 'Staff restored successfully');
    }

    public function forceDelete($id)
    {
        // Find the staff record, including soft-deleted ones
        $staff = Staff::withTrashed()->findOrFail($id);

        // Ensure it is already soft deleted before force deleting
        if ($staff->trashed()) {
            $staff->forceDelete();
            return redirect()->route('staff.index')->with('success', 'Staff permanently deleted.');
        }

        return redirect()->route('staff.index')->with('error', 'Staff is not deleted yet.');
    }

}
