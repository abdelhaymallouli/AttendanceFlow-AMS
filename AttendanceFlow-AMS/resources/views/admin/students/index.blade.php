@extends('layouts.dashboard')

@section('title', 'Student Management')
@section('page_title', 'Student Directory')

@section('header_actions')
<button class="bg-blue-600 hover:bg-blue-700 text-white font-black py-2.5 px-6 rounded-2xl transition-all shadow-xl shadow-blue-500/20 active:scale-95 flex items-center gap-2 text-xs uppercase tracking-widest leading-none">
    <i data-lucide="user-plus" class="w-4 h-4"></i> Add New Student
</button>
@endsection

@section('content')
<div class="space-y-8">
    
    <!-- Student Statistics (Mockup Style) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-100 rounded-[2rem] p-8 lg:p-10 shadow-sm relative group overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-125 transition-transform duration-700 opacity-50"></div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 opacity-70">Total Registered</p>
            <p class="text-4xl font-black text-gray-800 italic">{{ \App\Models\StudentProfile::count() }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 lg:p-10 shadow-sm relative group overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-125 transition-transform duration-700 opacity-50"></div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 opacity-70">Present Today</p>
            <p class="text-4xl font-black text-emerald-600 italic">42</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 lg:p-10 shadow-sm relative group overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full group-hover:scale-125 transition-transform duration-700 opacity-50"></div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 opacity-70">Critical Absences</p>
            <p class="text-4xl font-black text-red-600 italic">05</p>
        </div>
    </div>

    <!-- Student Table (Mockup Style) -->
    <div class="bg-white border border-gray-100 rounded-[3rem] p-8 lg:p-12 shadow-sm relative overflow-hidden">
        <div class="flex items-center justify-between mb-12">
            <h3 class="text-xl font-black text-gray-800 uppercase italic tracking-tighter">Academic Records</h3>
            <div class="flex gap-3">
                <div class="relative">
                    <input type="text" placeholder="Search ID or Name..." 
                           class="bg-gray-50 border border-gray-100 rounded-2xl px-6 py-3.5 text-xs font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition-all w-80 shadow-inner">
                    <i data-lucide="search" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 w-5 h-5"></i>
                </div>
                <button class="bg-gray-50 hover:bg-gray-100 px-5 rounded-2xl border border-gray-100 transition-all font-black text-[10px] uppercase tracking-widest text-gray-400">
                    <i data-lucide="sliders" class="w-4 h-4 mx-auto"></i>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] border-b border-gray-100">
                        <th class="px-6 py-6 font-black">Student Details</th>
                        <th class="px-6 py-6 font-black">Academic Path</th>
                        <th class="px-6 py-6 font-black">Performance</th>
                        <th class="px-6 py-6 font-black text-center">Status</th>
                        <th class="px-6 py-6 font-black text-right pr-12">Manage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(\App\Models\StudentProfile::with(['user', 'group'])->latest()->get() as $student)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-8">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center font-black text-blue-600 shadow-xl shadow-slate-100 border border-gray-100 group-hover:scale-110 transition-transform">
                                    {{ substr($student->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-base font-black text-gray-800 uppercase italic tracking-tighter">{{ $student->user->name }}</p>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1 opacity-70">{{ $student->student_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-8">
                            <p class="text-sm font-bold text-gray-600 uppercase italic opacity-80">{{ $student->group->name ?? 'G1' }}</p>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Full-Time DEV</p>
                        </td>
                        <td class="px-6 py-8">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-black text-blue-600 italic">94%</span>
                                <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" style="width: 94%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-8 text-center">
                            <span class="inline-flex items-center gap-2 py-2 px-4 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-sm shadow-emerald-500/5">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-8 text-right pr-6">
                            <div class="flex justify-end gap-2">
                                <button class="w-10 h-10 bg-gray-50 hover:bg-blue-600 hover:text-white text-gray-400 rounded-xl flex items-center justify-center transition-all border border-transparent hover:shadow-xl hover:shadow-blue-500/20 active:scale-95" title="Edit Profile">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                                </button>
                                <button class="w-10 h-10 bg-gray-50 hover:bg-red-600 hover:text-white text-gray-400 rounded-xl flex items-center justify-center transition-all border border-transparent hover:shadow-xl hover:shadow-red-500/20 active:scale-95" title="Suspend Account">
                                    <i data-lucide="user-minus" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-gray-300 font-bold uppercase tracking-[0.4em] opacity-30">The student directory is empty</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
