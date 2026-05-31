@props([
    'name' => 'teacher_profile_id',
    'value' => null,
    'onChange' => 'this.form.submit()',
    'label' => 'Teacher:',
    'includeBlank' => true,
    'blankText' => 'All Teachers'
])

<div class="flex items-center gap-3 bg-gray-50 p-2 rounded-lg border border-gray-100">
    <label class="text-sm font-medium text-gray-700 whitespace-nowrap ml-1">
        <i data-lucide="user" class="w-4 h-4 inline-block mr-1 text-blue-600"></i> {{ $label }}
    </label>
    <select 
        name="{{ $name }}"
        @change="{{ $onChange }}"
        class="text-sm border border-gray-300 rounded-md px-3 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none bg-white shadow-sm"
    >
        @if($includeBlank)
            <option value=""> {{ $blankText }} </option>
        @endif
        @foreach(\App\Models\TeacherProfile::with('user')->orderBy('user->name')->get() as $teacher)
            <option 
                value="{{ $teacher->id }}" 
                {{ $value == $teacher->id ? 'selected' : '' }}
            >
                {{ $teacher->user->name }}
            </option>
        @endforeach
    </select>
</div>