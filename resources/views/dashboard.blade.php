<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPA - Sistem Intelejen Pelacakan Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hero-gradient { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); }
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
                    Platform otomatisasi pelacakan jejak profesional dan akademik alumni Universitas Muhammadiyah Malang berbasis integrasi data publik.
                </p>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 lg:px-20 pb-20">

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
                            <th class="px-8 py-4">Status</th>
                            <th class="px-8 py-4">Aksi</th>
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
                                    {{ $alumni->status_pelacakan == 'Teridentifikasi' ? 'bg-green-100 text-green-700' :
                                       ($alumni->status_pelacakan == 'Perlu Verifikasi Manual' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                                    {{ $alumni->status_pelacakan }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <form action="{{ route('alumni.track', $alumni->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow-md transition-all active:scale-95">
                                        Lacak
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="border-gray-200 mb-12">
        <h2 class="text-2xl font-bold mb-8 text-gray-800">Jejak Bukti Temuan (Evidence Logs)</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-2">
            @foreach($alumnis as $alumni)
                @foreach($alumni->trackingLogs as $log)
                <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 transition-transform hover:-translate-y-1
                    {{ $log->total_confidence_score >= 80 ? 'border-green-500' : 'border-yellow-500' }}">

                    <div class="flex items-center gap-2 mb-4">
                        <div class="bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded font-bold uppercase">ALUMNI ID: #{{ $alumni->id }}</div>
                        <span class="text-sm font-bold text-gray-800 truncate">{{ $alumni->nama_lengkap }}</span>
                    </div>

                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-black text-blue-600 uppercase text-xs tracking-widest">{{ $log->sumber_data }}</h3>
                        <div class="text-right">
                            <span class="text-xl font-black text-gray-800">{{ $log->total_confidence_score }}</span>
                            <span class="text-[10px] text-gray-400 block font-bold">SKOR</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed italic mb-4">"{{ $log->bukti_snippet }}"</p>

                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-1 rounded font-bold">Nama +{{ $log->score_nama }}</span>
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-1 rounded font-bold">Afiliasi +{{ $log->score_afiliasi }}</span>
                        <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-1 rounded font-bold">Waktu +{{ $log->score_timeline }}</span>
                    </div>

                    <a href="{{ $log->link_bukti }}" target="_blank" class="block text-center bg-gray-800 hover:bg-black text-white text-xs font-bold py-3 rounded-xl transition shadow-lg">
                        Buka Sumber Asli
                    </a>
                </div>
                @endforeach
            @endforeach
        </div>
    </main>

</body>
</html>
