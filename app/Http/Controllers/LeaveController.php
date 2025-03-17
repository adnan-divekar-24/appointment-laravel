<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Staff;
use Carbon\Carbon;

class LeaveController extends Controller
{
    // Show all leaves with filters
    public function index(Request $request)
    {
        $query = Leave::with('staff');

        // Apply filters
        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }
        if ($request->filled('month')) {
            $query->whereMonth('start_date', $request->month);
        }
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaves = $query->paginate(10);
        $staff = Staff::all(); // For staff filter dropdown

        return view('leaves.index', compact('leaves', 'staff'));
    }

    // Show form to apply for leave
    public function create()
    {
        $staff = Staff::all();
        return view('leaves.create', compact('staff'));
    }

    // Store a new leave request
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'leave_type' => 'required|in:annual,sick',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $staff = Staff::findOrFail($validatedData['staff_id']);
        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = Carbon::parse($validatedData['end_date']);
        $leaveDays = $startDate->diffInDays($endDate) + 1;
    
        if ($validatedData['leave_type'] == 'annual') {

            if ($staff->annual_leave_balance < $leaveDays) {
                return back()->withErrors('Not enough annual leave balance.');
            }
            $staff->annual_leave_balance -= $leaveDays;
        } else {
            // Reset sick leave if it's a new year
            if ($startDate->year > Carbon::now()->year) {
                $staff->sick_leave_balance = 15;
            }
            
            if ($staff->sick_leave_balance < $leaveDays) {
                return back()->withErrors('Not enough sick leave balance.');
            }
            $staff->sick_leave_balance -= $leaveDays;
        }
        
        $staff->save();
        $validatedData['total_days'] = $leaveDays;
        Leave::create($validatedData);

    
        return redirect()->route('leaves.index')->with('success', 'Leave applied successfully!');
    }
    

    // Show edit form (Admin Only)
    public function edit($id)
    {
        $leave = Leave::findOrFail($id);
        $staff = Staff::all();
        return view('leaves.edit', compact('leave', 'staff'));
    }

    // Update leave request (Admin Only)
    public function update(Request $request, $id)
    {
        $request->validate([
            'staff_id'    => 'required|exists:staff,id',
            'leave_type'  => 'required|in:annual,sick',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $leave = Leave::findOrFail($id);
        $old_days = $leave->total_days;
        $staff = Staff::findOrFail($request->staff_id);

        // Revert old leave balance
        if ($leave->leave_type == 'annual') {
            $staff->annual_leave_balance += $old_days;
        } elseif ($leave->leave_type == 'sick') {
            $staff->sick_leave_balance += $old_days;
        }

        // Calculate new leave days
        $new_days = \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1;

        // Check new leave balance
        if ($request->leave_type == 'annual' && $staff->annual_leave_balance < $new_days) {
            return redirect()->back()->with('error', 'Not enough annual leaves.');
        }
        if ($request->leave_type == 'sick' && $staff->sick_leave_balance < $new_days) {
            return redirect()->back()->with('error', 'Not enough sick leaves.');
        }

        // Update new leave balance
        if ($request->leave_type == 'annual') {
            $staff->annual_leave_balance -= $new_days;
        } elseif ($request->leave_type == 'sick') {
            $staff->sick_leave_balance -= $new_days;
        }

        // Update leave record
        $leave->update([
            'staff_id'   => $request->staff_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'total_days' => $new_days,
        ]);

        $staff->save();

        return redirect()->route('leaves.index')->with('success', 'Leave updated successfully.');
    }

    // Delete leave request (Admin Only)
    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        $staff = Staff::findOrFail($leave->staff_id);

        // Revert leave balance
        if ($leave->leave_type == 'annual') {
            $staff->annual_leave_balance += $leave->total_days;
        } elseif ($leave->leave_type == 'sick') {
            $staff->sick_leave_balance += $leave->total_days;
        }

        $staff->save();
        $leave->delete();

        return redirect()->route('leaves.index')->with('success', 'Leave deleted successfully.');
    }
}

