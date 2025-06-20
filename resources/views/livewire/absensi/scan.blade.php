<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
<div class="flex items-center justify-center min-h-screen bg-slate-100 font-sans p-4">
    <div class="relative w-full max-w-sm overflow-hidden rounded-2xl shadow-2xl shadow-slate-300/50 animate-fade-in-up">
        @if ($status === 'success')
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute w-2 h-4 bg-yellow-400 rounded-full animate-confetti-1"></div>
                <div class="absolute w-2 h-2 bg-green-500 rounded-full animate-confetti-2"></div>
                <div class="absolute w-2 h-4 bg-blue-500 rounded-full animate-confetti-3"></div>
                <div class="absolute w-2 h-2 bg-pink-500 rounded-full animate-confetti-4"></div>
                <div class="absolute w-2 h-4 bg-indigo-500 rounded-full animate-confetti-5"></div>
            </div>
        @endif
        
        <div @class([
            'relative',
            'p-8',
            'text-center',
            'space-y-4',
            'bg-gradient-to-br from-teal-50 to-cyan-100' => $status === 'success',
            'bg-gradient-to-br from-red-50 to-rose-100' => $status !== 'success'
        ])>
        
            @if ($status === 'success')
                <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-white/50 shadow-lg animate-pop-in">
                    <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-slate-800">Berhasil!</h2>
                    <p class="text-slate-600">{{ $message }}</p>
                </div>

            @else
                <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-white/50 shadow-lg animate-pop-in">
                    <svg class="w-12 h-12 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-slate-800">Gagal!</h2>
                    <p class="text-slate-600">{{ $message }}</p>
                </div>
            @endif
            
            <a href="{{ route('santri.scanner') }}" 
               class="inline-block pt-4 w-full">
                <span class="block w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold shadow-lg shadow-indigo-500/30
                       transform transition-transform duration-300 ease-in-out
                       hover:scale-105 active:scale-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Scan Lagi
                </span>
            </a>
        </div>
    </div>
</div>