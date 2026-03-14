<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AlumniTrackingController extends Controller
{
    public function index()
    {
        $alumnis = Alumni::with('trackingLogs')->get();
        return view('dashboard', compact('alumnis'));
    }

    public function trackAlumni(Request $request, $id, $type = 'linkedin')
    {

        $alumni = Alumni::findOrFail($id);
        TrackingLog::where('alumni_id', $id)
            ->where('sumber_data', 'LIKE', ucfirst($type) . '%')
            ->delete();

        $apiKey = 'b46b163cfed526755edd39b39ae5bb5af314a3c196bf6dae447f17dd4ab38d76';

        if ($type === 'scholar') {
            $query = 'site:scholar.google.com "' . $alumni->nama_lengkap . '" "' . $alumni->university . '"';
            $displaySource = 'Google Scholar via SerpApi';
        } else {
            $query = 'site:linkedin.com/in "' . $alumni->nama_lengkap . '"';
            $displaySource = 'LinkedIn via SerpApi';
        }

        try {
            $response = Http::timeout(30)->get("https://serpapi.com/search", [
                'q' => $query,
                'api_key' => $apiKey,
                'engine' => 'google',
                'num' => 5
            ]);

            $data = $response->json();
            $results = $data['organic_results'] ?? [];
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghubungi server pelacak. Coba lagi nanti.');
        }

        $bestScoreNama = 0;
        $bestScoreAfiliasi = 0;
        $bestScoreTimeline = 0;
        $bestSnippet = "Data tidak ditemukan.";
        $bestLink = "https://www.google.com/search?q=" . urlencode($query);

        foreach ($results as $result) {
            $title = strtolower($result['title'] ?? '');
            $snippet = strtolower($result['snippet'] ?? '');
            $link = $result['link'] ?? '';
            $combinedText = $title . ' ' . $snippet;

            if ($type === 'scholar' && !str_contains($link, 'scholar.google.')) continue;
            if ($type === 'linkedin' && !str_contains($link, 'linkedin.com/in/')) continue;

            $currentNama = 0; $currentAfiliasi = 0; $currentTimeline = 0;

            $nameParts = explode(' ', strtolower($alumni->nama_lengkap));
            $matchCount = 0;
            foreach ($nameParts as $part) {
                if (strlen($part) > 2 && str_contains($combinedText, $part)) $matchCount++;
            }
            if ($matchCount >= 2) $currentNama = 40;
            elseif ($matchCount == 1) $currentNama = 20;

            $univKey = strtolower($alumni->university ?? 'muhammadiyah');
            if ($currentNama > 0 && (str_contains($combinedText, 'muhammadiyah') || str_contains($combinedText, 'umm') || str_contains($combinedText, $univKey))) {
                $currentAfiliasi = 40;
            }

            if ($currentNama > 0 && preg_match('/20[0-2][0-9]/', $combinedText, $yearMatch)) {
                $foundYear = (int)$yearMatch[0];
                if ($type === 'scholar') {
                    if ($foundYear >= ($alumni->tahun_lulus - 1)) $currentTimeline = 20;
                } else {
                    if ($foundYear >= ($alumni->tahun_lulus - 2) && $foundYear <= ($alumni->tahun_lulus + 4)) $currentTimeline = 20;
                }
            }

            if (($currentNama + $currentAfiliasi + $currentTimeline) > ($bestScoreNama + $bestScoreAfiliasi + $bestScoreTimeline)) {
                $bestScoreNama = $currentNama;
                $bestScoreAfiliasi = $currentAfiliasi;
                $bestScoreTimeline = $currentTimeline;
                $bestSnippet = $result['snippet'] ?? '';
                $bestLink = $link;
            }
        }

        $totalScore = $bestScoreNama + $bestScoreAfiliasi + $bestScoreTimeline;

        TrackingLog::create([
            'alumni_id' => $alumni->id,
            'sumber_data' => $displaySource,
            'judul_temuan' => ($totalScore >= 40) ? "Kandidat Teridentifikasi ($type)" : "Data Tidak Cocok",
            'bukti_snippet' => $bestSnippet,
            'link_bukti' => $bestLink,
            'score_nama' => $bestScoreNama,
            'score_afiliasi' => $bestScoreAfiliasi,
            'score_timeline' => $bestScoreTimeline,
            'total_confidence_score' => $totalScore,
        ]);

        $alumni->update(['status_pelacakan' => $this->determineStatus($totalScore)]);

        return redirect()->to(url('/'))->with('success', "Pelacakan $type selesai. Skor: $totalScore");
    }

    public function resetTracking($id)
    {
        TrackingLog::where('alumni_id', $id)->delete();
        Alumni::where('id', $id)->update(['status_pelacakan' => 'Belum Dilacak']);
        return redirect()->to(url('/'))->with('success', "Data alumni berhasil direset.");
    }

    public function resetAllTracking()
    {
        TrackingLog::truncate();
        Alumni::query()->update(['status_pelacakan' => 'Belum Dilacak']);
        return redirect()->to(url('/'))->with('success', "Semua log berhasil dibersihkan.");
    }

    private function determineStatus($score)
    {
        if ($score >= 80) return 'Terverifikasi';
        if ($score >= 40) return 'Perlu Verifikasi Manual';
        return 'Tidak Cocok';
    }
}
