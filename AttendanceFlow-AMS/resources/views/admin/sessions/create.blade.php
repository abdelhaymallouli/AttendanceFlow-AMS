                    @error('start_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                
                <!-- End Time -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="time" x-model="endTime" name="end_time" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none">
                    </div>
                    @error('end_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <!-- Computed Duration -->
            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700">Calculated Duration:</span>
                <span class="font-bold text-blue-700" x-text="durationText"></span>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.sessions.index') }}" class="px-6 py-3 rounded-xl text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 hover:shadow-lg active:scale-95 transition-all flex items-center">
                    <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                    Create Session
                </button>
            </div>
        </form>
    </div>
</div>