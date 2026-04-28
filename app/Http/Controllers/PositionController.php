<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('department')->orderBy('title')->paginate(10);
        return view('positions.index', compact('positions'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('positions.create', compact('departments'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'title' => [
            'required',
            'string',
            'max:255',
            Rule::unique('positions')->where(function ($query) use ($request) {
                return $query->where('department_id', $request->department_id);
            })
        ],
        'department_id' => 'required|exists:departments,id',
        'min_salary' => 'nullable|numeric|min:0',
        'max_salary' => 'nullable|numeric|min:0|gte:min_salary',
    ]);

    Position::create($validated);

    return redirect()->route('departments.show', $request->department_id)
        ->with('success', 'Position added successfully.');
}

    public function show(Position $position)
    {
        $position->load('department');
        $employees = $position->employees()->paginate(10);
        return view('positions.show', compact('position', 'employees'));
    }

    public function edit(Position $position)
    {
        $departments = Department::orderBy('name')->get();
        return view('positions.edit', compact('position', 'departments'));
    }

    public function update(Request $request, Position $position)
{
    $validated = $request->validate([
        'title' => [
            'required',
            'string',
            'max:255',
            Rule::unique('positions')->where(function ($query) use ($request) {
                return $query->where('department_id', $request->department_id);
            })->ignore($position->id)
        ],
        'department_id' => 'required|exists:departments,id',
        'min_salary' => 'nullable|numeric|min:0',
        'max_salary' => 'nullable|numeric|min:0|gte:min_salary',
    ]);

    $position->update($validated);

    return redirect()->route('departments.show', $position->department_id)
        ->with('success', 'Position updated successfully.');
}

public function destroy(Position $position)
{
    $departmentId = $position->department_id;
    $position->delete();
    
    return redirect()->route('departments.show', $departmentId)
        ->with('success', 'Position deleted successfully.');
}
}