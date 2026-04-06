@extends('layouts.dashboard')

@section('title', 'Justification Hub')
@section('page_title', 'Validation Center')

@section('header_actions')
<div class="flex items-center gap-3">
    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-widest">
        7 Pending Approval
    </span>
</div>
@endsection

@section('content')
<div class="space-y-8">
    
    <!-- Hero Stats for Justifications (Mockup Style) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm overflow-hidden relative group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 rounded-full group-hover:scale-125 transition-transform"></div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 opacity-70">Total Submitted</p>
            <p class="text-3xl font-black text-gray-800 italic">{{ \App\Models\Justification::count() }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm overflow-hidden relative group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-50 rounded-full group-hover:scale-125 transition-transform"></div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 opacity-70">Pending</p>
            <p class="text-3xl font-black text-amber-600 italic">{{ \App\Models\Justification::where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm overflow-hidden relative group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-green-50 rounded-full group-hover:scale-125 transition-transform"></div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 opacity-70">Approved</p>
            <p class="text-3xl font-black text-green-600 italic">{{ \App\Models\Justification::where('status', 'approved')->count() }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm overflow-hidden relative group">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-50 rounded-full group-hover:scale-125 transition-transform"></div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 opacity-70">Denied</p>
            <p class="text-3xl font-black text-red-600 italic">{{ \App\Models\Justification::where('status', 'rejected')->count() }}</p>
        </div>
    </div>

    <!-- Justification List (Mockup Style Table) -->
    <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 lg:p-10 shadow-sm relative overflow-hidden">
        <div class="flex items-center justify-between mb-10">
            <h3 class="text-xl font-black text-gray-800 uppercase italic tracking-tighter">Validation Queue</h3>
            <div class="flex gap-2">
                <input type="text" placeholder="Search by name..." class="bg-gray-50 border border-gray-100 rounded-xl px-5 py-2.5 text-xs font-bold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition-all w-64 shadow-sm">
                <button class="bg-gray-50 hover:bg-gray-100 p-2.5 rounded-xl border border-gray-100 transition-all">
                    <i data-lucide="filter" class="w-5 h-5 text-gray-400"></i>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Reason / Type</th>
                        <th class="px-6 py-4">Document</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(\App\Models\Justification::with('studentProfile.user')->latest()->get() as $just)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center font-black text-blue-600 shadow-sm border border-gray-50">
                                    {{ substr($just->studentProfile->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800 uppercase tracking-tight italic">{{ $just->studentProfile->user->name }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $just->studentProfile->student_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 font-bold text-gray-600 opacity-80 text-xs">
                            {{ $just->reason }}
                        </td>
                        <td class="px-6 py-6">
                            <a href="{{ Storage::url($just->file_path) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-black text-blue-600 hover:text-blue-800 uppercase tracking-widest bg-blue-50 px-3 py-2 rounded-xl transition-all">
                                <i data-lucide="file-text" class="w-4 h-4"></i> View Doc
                            </a>
                        </td>
                        <td class="px-6 py-6">
                            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-[10px] font-black uppercase tracking-widest border 
                                {{ $just->status == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                                {{ $just->status == 'approved' ? 'bg-green-50 text-green-600 border-green-100' : '' }}
                                {{ $just->status == 'rejected' ? 'bg-red-50 text-red-600 border-red-100' : '' }}">
                                {{ $just->status }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @if($just->status == 'pending')
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('admin.justifications.update', $just) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="w-10 h-10 bg-green-50 hover:bg-green-100 text-green-600 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-90" title="Approve">
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.justifications.update', $just) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="w-10 h-10 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-90" title="Reject">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest italic">Processed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-[0.2em] opacity-40">No pending justifications found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
