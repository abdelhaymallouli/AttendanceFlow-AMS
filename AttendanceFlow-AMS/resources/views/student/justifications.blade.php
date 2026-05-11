@extends('layouts.dashboard')

@section('title', 'Justification Upload')
@section('page_title', 'Absence Proof Upload')

@section('header_actions')
<div class="flex items-center gap-3">
    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-widest leading-none">
        Secure Portal
    </span>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Upload Form (Mockup Style) -->
    <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 lg:p-12 shadow-2xl shadow-slate-200/50 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/5 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-700"></div>
        
        <div class="mb-10 text-center lg:text-left relative z-10">
            <h3 class="text-3xl font-black text-gray-800 tracking-tight mb-2 uppercase italic">New Proof</h3>
            <p class="text-slate-400 font-bold text-xs tracking-widest uppercase opacity-80">Upload your documentation for administrative review</p>
        </div>

        <form action="{{ route('student.justifications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 relative z-10">
            @csrf
            
            @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 font-bold border border-red-100">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="space-y-2 ml-2">
                <label for="reason" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest opacity-80">Reason for Absence</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i data-lucide="help-circle" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="text" name="reason" id="reason" 
                           class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none transition-all font-bold text-gray-800"
                           placeholder="e.g., Medical Appointment, Family Emergency" required>
                </div>
            </div>

            <div class="space-y-2 ml-2">
                <label for="file" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest opacity-80">Supporting Document (PDF/JPG)</label>
                <div class="relative">
                    <label class="group flex flex-col items-center justify-center p-8 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl hover:bg-white hover:border-blue-600 transition-all cursor-pointer">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-blue-600 mb-2 shadow-sm group-hover:scale-110 transition-transform">
                            <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                        </div>
                        <p class="text-xs font-black text-gray-500 group-hover:text-blue-600 uppercase tracking-widest">Tap to browse files</p>
                        <p class="text-[9px] font-bold text-gray-300 mt-1 uppercase tracking-widest">Max size: 5MB</p>
                        <input type="file" name="file" id="file" class="hidden" required @change="fileName = $event.target.files[0].name" x-data="{ fileName: '' }">
                    </label>
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-5 px-6 rounded-2xl transition-all duration-300 flex items-center justify-center gap-3 shadow-xl shadow-slate-200 hover:shadow-blue-500/20 active:scale-95">
                <span>SUBMIT FOR APPROVAL</span>
                <i data-lucide="send" class="w-5 h-5"></i>
            </button>
        </form>
    </div>

    <!-- My Recent Uploads (Mockup Style) -->
    <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 lg:p-10 shadow-sm relative">
        <h3 class="text-lg font-black text-gray-800 uppercase tracking-widest mb-8 flex items-center">
            <i data-lucide="history" class="w-5 h-5 mr-3 text-blue-600"></i>
            My Recent Proofs
        </h3>
        
        <div class="space-y-4">
            @forelse(Auth::user()->studentProfile->justifications()->latest()->take(5)->get() as $just)
            <div class="flex items-center justify-between p-5 bg-gray-50/50 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm border border-gray-100">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-gray-800 uppercase italic tracking-tighter truncate max-w-[150px]">{{ $just->reason }}</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ \Carbon\Carbon::parse($just->created_at)->format('M d, Y') }}</p>
                    </div>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border
                    {{ $just->status == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                    {{ $just->status == 'approved' ? 'bg-green-50 text-green-600 border-green-100' : '' }}
                    {{ $just->status == 'rejected' ? 'bg-red-50 text-red-600 border-red-100' : '' }}">
                    {{ $just->status }}
                </span>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-[0.2em] opacity-40">No justifications uploaded yet</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
