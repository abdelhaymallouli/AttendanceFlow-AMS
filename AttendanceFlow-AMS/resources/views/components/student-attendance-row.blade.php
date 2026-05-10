@props(['student', 'currentStatus' => null])

<div class="p-4 student-row border-b border-gray-100 hover:bg-gray-50 transition-colors flex flex-col md:grid md:grid-cols-12 gap-4 md:items-center">
    
    <!-- Mobile Info -->
    <div class="md:hidden flex items-center justify-between mb-1">
        <div>
            <p class="text-sm font-medium text-gray-800">{{ $student->user->name }}</p>
            <p class="text-xs text-gray-500">{{ $student->matricule }}</p>
        </div>
    </div>

    <!-- Desktop Info -->
    <div class="hidden md:block col-span-1 text-sm text-gray-500">{{ $loop->iteration ?? 1 }}</div>
    <div class="hidden md:block col-span-2 text-sm font-medium text-gray-800">{{ $student->matricule }}</div>
    <div class="hidden md:block col-span-4 text-sm font-medium text-gray-800">{{ $student->user->name }}</div>
    
    <!-- Shared Buttons -->
    <div class="col-span-5 flex justify-center space-x-2 w-full">
        <!-- Present -->
        <label class="flex-1 cursor-pointer">
            <input type="radio" name="attendance[{{ $student->id }}]" value="present" class="peer hidden" {{ $currentStatus == 'present' ? 'checked' : '' }} @change="updateStats()">
            <div class="status-btn py-2.5 md:py-2 px-3 rounded-lg font-medium text-sm transition-all flex items-center justify-center gap-1 bg-white text-gray-700 border border-gray-300 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 peer-checked:ring-2 peer-checked:ring-green-600 peer-checked:ring-offset-2">
                <i data-lucide="check" class="w-4 h-4"></i><span class="md:inline">P</span>
            </div>
        </label>
        
        <!-- Absent -->
        <label class="flex-1 cursor-pointer">
            <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="peer hidden" {{ $currentStatus == 'absent' ? 'checked' : '' }} @change="updateStats()">
            <div class="status-btn py-2.5 md:py-2 px-3 rounded-lg font-medium text-sm transition-all flex items-center justify-center gap-1 bg-white text-gray-700 border border-gray-300 peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 peer-checked:ring-2 peer-checked:ring-red-600 peer-checked:ring-offset-2">
                <i data-lucide="x" class="w-4 h-4"></i><span class="md:inline">A</span>
            </div>
        </label>
        
        <!-- Late -->
        <label class="flex-1 cursor-pointer">
            <input type="radio" name="attendance[{{ $student->id }}]" value="late" class="peer hidden" {{ $currentStatus == 'late' ? 'checked' : '' }} @change="updateStats()">
            <div class="status-btn py-2.5 md:py-2 px-3 rounded-lg font-medium text-sm transition-all flex items-center justify-center gap-1 bg-white text-gray-700 border border-gray-300 peer-checked:bg-amber-600 peer-checked:text-white peer-checked:border-amber-600 peer-checked:ring-2 peer-checked:ring-amber-600 peer-checked:ring-offset-2">
                <i data-lucide="clock" class="w-4 h-4"></i><span class="md:inline">L</span>
            </div>
        </label>
    </div>
</div>
