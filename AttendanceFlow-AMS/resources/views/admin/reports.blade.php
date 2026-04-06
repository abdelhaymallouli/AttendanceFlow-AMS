@extends('layouts.dashboard')

@section('title', 'Global Analytics')
@section('page_title', 'Attendance Reports')

@section('header_actions')
<button class="bg-blue-600 hover:bg-blue-700 text-white font-black py-2.5 px-6 rounded-2xl transition-all shadow-xl shadow-blue-500/20 active:scale-95 flex items-center gap-2 text-xs uppercase tracking-widest leading-none">
    <i data-lucide="download" class="w-4 h-4"></i> Export PDF
</button>
@endsection

@section('content')
<div class="space-y-8">
    
    <!-- Analytics High-Level Grid (Mockup Style) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-100 rounded-[2rem] p-8 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 opacity-70">General Rate</p>
            <div class="flex items-center gap-6">
                <div class="text-5xl font-black text-gray-800 italic">94.2<span class="text-2xl text-blue-600">%</span></div>
                <div class="flex-1 h-3 bg-gray-50 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full" style="width: 94%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-[2rem] p-8 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 opacity-70">Total Absences</p>
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center font-black shadow-sm">
                    <i data-lucide="trending-up" class="w-8 h-8"></i>
                </div>
                <div>
                    <p class="text-3xl font-black text-gray-800">142h</p>
                    <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest">+12% vs last month</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-[2rem] p-8 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 opacity-70">At-Risk Students</p>
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center font-black shadow-sm">
                    <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                </div>
                <div>
                    <p class="text-3xl font-black text-gray-800">08</p>
                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest">Attendance < 85%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Table Area (Mockup Style) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Large Chart Block -->
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-[2.5rem] p-8 lg:p-10 shadow-sm relative">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black text-gray-800 uppercase tracking-widest">Attendance Trends</h3>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-gray-50 text-[10px] font-black uppercase rounded-xl border border-gray-100 text-gray-400">Weekly</button>
                    <button class="px-4 py-2 bg-blue-600 text-[10px] font-black uppercase rounded-xl border border-blue-600 text-white">Monthly</button>
                </div>
            </div>
            
            <!-- Chart Placeholder (Mockup Style) -->
            <div class="h-80 bg-gray-50/50 rounded-[2rem] border border-dashed border-gray-200 flex flex-col items-center justify-center relative overflow-hidden group">
                <div class="absolute inset-x-0 bottom-0 flex items-end justify-around h-full px-10 pb-8 space-x-1">
                    @php $heights = [45, 62, 85, 74, 91, 55, 88, 92, 40, 68, 77, 83]; @endphp
                    @foreach($heights as $h)
                    <div class="w-full max-w-[20px] bg-blue-100 group-hover:bg-blue-600 transition-all rounded-t-lg shadow-blue-500/10" style="height: {{ $h }}%"></div>
                    @endforeach
                </div>
                <div class="relative z-10 text-center pointer-events-none">
                    <i data-lucide="bar-chart-2" class="w-12 h-12 text-blue-600/20 mb-4 mx-auto"></i>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Live Analytics Feed</p>
                </div>
            </div>
        </div>

        <!-- At-Risk Preview (Mockup Style) -->
        <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
            <h3 class="text-lg font-black text-gray-800 mb-8 uppercase tracking-widest">At-Risk Students</h3>
            <div class="space-y-6">
                @foreach(\App\Models\StudentProfile::with('user')->take(5)->get() as $student)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center font-black group-hover:bg-red-50 group-hover:text-red-600 transition-colors">
                            {{ substr($student->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-gray-800">{{ $student->user->name }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $student->group->name ?? 'G1' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-black text-red-600 italic">82%</p>
                        <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest">Critical</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <button class="w-full mt-10 p-5 bg-gray-50 hover:bg-gray-100 text-gray-400 hover:text-red-600 font-black rounded-2xl text-[10px] uppercase tracking-[0.2em] transition-all border border-transparent hover:border-red-100">
                View Critical List
            </button>
        </div>
    </div>

</div>
@endsection
