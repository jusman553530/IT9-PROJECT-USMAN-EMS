@extends('layouts.app')

@section('title', $department->name)

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $department->name }}</h1>
            <p class="text-gray-600">Department Code: {{ $department->code }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('departments.edit', $department) }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Edit Department
            </a>
            <a href="{{ route('departments.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Back to List
            </a>
        </div>
    </div>

    <!-- Department Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                <p class="text-gray-600">{{ $department->description ?? 'No description provided.' }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Total Employees</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $employees->total() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Positions</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $department->positions->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Created</p>
                    <p class="text-gray-900">{{ $department->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Positions in this Department -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Positions in this Department</h2>
            <button onclick="document.getElementById('addPositionModal').classList.remove('hidden')" 
                    class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition flex items-center gap-2"
                    style="background-color: #0C521C;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Position
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($department->positions as $position)
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-semibold text-gray-900">{{ $position->title }}</h3>
                        <div class="flex gap-1">
                            <button onclick="editPosition({{ $position->id }}, '{{ $position->title }}', {{ $position->min_salary ?? 'null' }}, {{ $position->max_salary ?? 'null' }})"
                                    class="text-green-600 hover:text-green-800 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <form action="{{ route('positions.destroy', $position) }}" method="POST" 
                                  onsubmit="return confirm('Delete this position?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        @if($position->min_salary || $position->max_salary)
                            <p class="text-gray-600">
                                Salary: 
                                @if($position->min_salary && $position->max_salary)
                                    ${{ number_format($position->min_salary) }} - ${{ number_format($position->max_salary) }}
                                @elseif($position->min_salary)
                                    From ${{ number_format($position->min_salary) }}
                                @elseif($position->max_salary)
                                    Up to ${{ number_format($position->max_salary) }}
                                @endif
                            </p>
                        @endif
                        <p class="text-gray-600">
                            <span class="font-medium">{{ $position->employees->count() }}</span> employees
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-8 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500">No positions added yet.</p>
                        <button onclick="document.getElementById('addPositionModal').classList.remove('hidden')" 
                                class="mt-3 text-green-600 hover:text-green-700 font-medium">
                            + Add your first position
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Employees in this Department -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900">Employees in this Department</h2>
        <button onclick="document.getElementById('addEmployeeModal').classList.remove('hidden')" 
                class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition flex items-center gap-2"
                style="background-color: #0C521C;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Employee
        </button>
    </div>
    
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->employee_id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $employee->position->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $employee->status === 'active' ? 'bg-green-100 text-green-700' : 
                                       ($employee->status === 'on_leave' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                No employees in this department yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($employees->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Position Modal -->
<div id="addPositionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Add Position</h3>
            <button onclick="document.getElementById('addPositionModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('positions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="department_id" value="{{ $department->id }}">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position Title *</label>
                    <input type="text" name="title" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Salary</label>
                    <input type="number" name="min_salary" step="0.01" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Salary</label>
                    <input type="number" name="max_salary" step="0.01" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600">
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('addPositionModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-white rounded-lg hover:opacity-90" style="background-color: #0C521C;">
                    Add Position
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Position Modal -->
<div id="editPositionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Edit Position</h3>
            <button onclick="document.getElementById('editPositionModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="editPositionForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position Title *</label>
                    <input type="text" name="title" id="edit_title" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Salary</label>
                    <input type="number" name="min_salary" id="edit_min_salary" step="0.01" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Salary</label>
                    <input type="number" name="max_salary" id="edit_max_salary" step="0.01" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600">
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editPositionModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-white rounded-lg hover:opacity-90" style="background-color: #0C521C;">
                    Update Position
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editPosition(id, title, minSalary, maxSalary) {
    document.getElementById('editPositionForm').action = '/positions/' + id;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_min_salary').value = minSalary || '';
    document.getElementById('edit_max_salary').value = maxSalary || '';
    document.getElementById('editPositionModal').classList.remove('hidden');
}
</script>
<!-- Add Employee Modal -->
<div id="addEmployeeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto my-8">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Add Employee to {{ $department->name }}</h3>
            <button onclick="document.getElementById('addEmployeeModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('employees.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="department_id" value="{{ $department->id }}">
            
            <div class="space-y-6">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-md font-semibold text-gray-900 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth *</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                            <select name="gender" required class="w-full px-3 py-2 border rounded-lg">
                                <option value="">Select</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                            <textarea name="address" rows="2" required class="w-full px-3 py-2 border rounded-lg">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Employment Information -->
                <div>
                    <h3 class="text-md font-semibold text-gray-900 mb-4">Employment Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                            <select name="position_id" required class="w-full px-3 py-2 border rounded-lg">
                                <option value="">Select Position</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hire Date *</label>
                            <input type="date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Salary *</label>
                            <input type="number" name="salary" value="{{ old('salary') }}" step="0.01" required class="w-full px-3 py-2 border rounded-lg">
                        </div>
                    </div>
                </div>
                
                <!-- Account Information -->
                <div>
                    <h3 class="text-md font-semibold text-gray-900 mb-4">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                            <select name="role" required class="w-full px-3 py-2 border rounded-lg">
                                <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                                <option value="team_leader" {{ old('role') == 'team_leader' ? 'selected' : '' }}>Team Leader</option>
                                <option value="head_department" {{ old('role') == 'head_department' ? 'selected' : '' }}>Head of Department</option>
                                <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>Accountant</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg">
                            <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="document.getElementById('addEmployeeModal').classList.add('hidden')"
                        class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-white rounded-lg hover:opacity-90" style="background-color: #0C521C;">
                    Add Employee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection