<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPA - Sistem Intelejen Pelacakan Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .hero-gradient { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">

    <header class="hero-gradient text-white py-16 mb-10 shadow-lg">
        <div class="container mx-auto px-6 lg:px-20">
            <nav class="mb-8">
                <span class="text-2xl font-bold tracking-tight">SIPA.</span>
            </nav>
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
                    Sistem Intelejen Pelacakan Alumni (SIPA)
                </h1>
                <p class="text-blue-100 text-lg">
                    Platform otomatisasi pelacakan jejak profesional (LinkedIn) dan akademik (Scholar) alumni UMM berbasis integrasi data publik.
                </p>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 lg:px-20 pb-20">

        @if(session('success'))
            <div class="mb-8 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded shadow-sm flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-900">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-bold rounded shadow-sm flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-900">&times;</button>
            </div>
        @endif

        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Data Master Alumni</h2>
                <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-sm font-medium">Total: {{ $alumnis->count() }} Orang</span>
            </div>

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs uppercase font-bold text-gray-500 tracking-wider">
                            <th class="px-8 py-4">Nama Alumni</th>
                            <th class="px-8 py-4">NIM</th>
                            <th class="px-8 py-4">Prodi</th>
                            <th class="px-8 py-4">Status Terakhir</th>
                            <th class="px-8 py-4">Aksi Pelacakan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($alumnis as $alumni)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-8 py-5">
                                <div class="text-sm font-semibold text-gray-900">{{ $alumni->nama_lengkap }}</div>
                            </td>
                            <td class="px-8 py-5 text-sm text-gray-600">{{ $alumni->nim }}</td>
                            <td class="px-8 py-5 text-sm text-gray-600">{{ $alumni->prodi }}</td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $alumni->status_pelacakan == 'Terverifikasi' ? 'bg-green-100 text-green-700' :
                                        ($alumni->status_pelacakan == 'Perlu Verifikasi Manual' ? 'bg-yellow-100 text-yellow-700' :
                                        ($alumni->status_pelacakan == 'Tidak Cocok' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500')) }}">
                                    {{ $alumni->status_pelacakan ?? 'Belum Dilacak' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <button @click="open = !open" type="button" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-all active:scale-95">
                                        Lacak
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false" x-cloak
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                        <div class="py-1">
                                            <form action="{{ route('alumni.track', [$alumni->id, 'linkedin']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 font-semibold">
                                                    LinkedIn (Karir)
                                                </button>
                                            </form>
                                            <form action="{{ route('alumni.track', [$alumni->id, 'scholar']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 font-semibold border-t border-gray-50">
                                                    Google Scholar (Akademik)
                                                </button>
                                            </form>

                                            <form action="{{ route('alumni.reset', $alumni->id) }}" method="POST" onsubmit="return confirm('Hapus jejak alumni ini?')">
                                                @csrf
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 font-bold border-t border-gray-100">
                                                    Hapus Jejak Bukti
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="border-gray-200 mb-12">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Jejak Bukti Temuan (Evidence Logs)</h2>

            <form action="{{ route('alumni.reset_all') }}" method="POST" onsubmit="return confirm('Hapus SELURUH jejak bukti dari semua alumni?')">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2.5 rounded-lg transition shadow-md hover:shadow-lg active:scale-95 flex items-center justify-center group" title="Bersihkan Semua Log">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-2">
            @php $hasLogs = false; @endphp
            @foreach($alumnis as $alumni)
                @foreach($alumni->trackingLogs->sortByDesc('created_at') as $log)
                @php $hasLogs = true; @endphp
                <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 transition-transform hover:-translate-y-1
                    {{ $log->total_confidence_score >= 80 ? 'border-green-500' : ($log->total_confidence_score >= 40 ? 'border-yellow-500' : 'border-red-400') }}">

                    <div class="flex items-center gap-2 mb-4">
                        <div class="bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded font-bold uppercase">ID: #{{ $alumni->id }}</div>
                        <div class="{{ str_contains($log->sumber_data, 'Scholar') ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }} text-[9px] px-2 py-0.5 rounded font-black uppercase">
                            {{ str_contains($log->sumber_data, 'Scholar') ? 'SCHOLAR' : 'LINKEDIN' }}
                        </div>
                    </div>

                    <div class="flex justify-between items-start mb-4">
                        <div class="max-w-[70%]">
                            <span class="text-sm font-bold text-gray-800 block mb-1 truncate">{{ $alumni->nama_lengkap }}</span>
                            <h3 class="font-black text-blue-600 uppercase text-[10px] tracking-widest">{{ $log->sumber_data }}</h3>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-black text-gray-800">{{ $log->total_confidence_score }}</span>
                            <span class="text-[10px] text-gray-400 block font-bold">SKOR</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed italic mb-4 min-h-[60px]">"{{ $log->bukti_snippet }}"</p>

                    <div class="flex flex-wrap gap-2 mb-6 border-t border-gray-50 pt-4">
                        <span class="bg-gray-100 text-gray-600 text-[9px] px-2 py-1 rounded font-bold">Nama +{{ $log->score_nama }}</span>
                        <span class="bg-gray-100 text-gray-600 text-[9px] px-2 py-1 rounded font-bold">Afiliasi +{{ $log->score_afiliasi }}</span>
                        <span class="bg-gray-100 text-gray-600 text-[9px] px-2 py-1 rounded font-bold">Waktu +{{ $log->score_timeline }}</span>
                    </div>

                    <a href="{{ $log->link_bukti }}" target="_blank" class="block text-center bg-gray-800 hover:bg-black text-white text-xs font-bold py-3 rounded-xl transition shadow-lg active:scale-95">
                        Lihat Sumber Data
                    </a>
                </div>
                @endforeach
            @endforeach

            @if(!$hasLogs)
                <div class="col-span-full py-20 text-center border-2 border-dashed border-gray-200 rounded-3xl">
                    <p class="text-gray-400 italic">Belum ada jejak bukti temuan yang tercatat di database.</p>
                </div>
            @endif
        </div>
    </main>

</body>
</html>
