<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AlumniTrackingController extends Controller
{
    public function trackAlumni($id)
    {
        $alumni = Alumni::findOrFail($id);
        TrackingLog::where('alumni_id', $id)->delete();

        $apiKey = 'b46b163cfed526755edd39b39ae5bb5af314a3c196bf6dae447f17dd4ab38d76';

        $query = 'site:linkedin.com/in "' . $alumni->nama_lengkap . '"';

        $response = Http::get("https://serpapi.com/search", [
            'q' => $query,
            'api_key' => $apiKey,
            'engine' => 'google',
            'num' => 5
        ]);

        $data = $response->json();
        $results = $data['organic_results'] ?? [];

        $scoreNama = 0;
        $scoreAfiliasi = 0;
        $scoreTimeline = 0;
        $snippetLengkap = "Tidak ditemukan profil LinkedIn.";

        foreach ($results as $result) {
            $title = strtolower($result['title'] ?? '');
            $snippet = strtolower($result['snippet'] ?? '');
            $link = $result['link'] ?? '';
            $combinedText = $title . ' ' . $snippet;

            if (!str_contains($link, 'linkedin.com/in/')) continue;

            $currentNama = 0;
            $currentAfiliasi = 0;
            $currentTimeline = 0;

            // A. SKOR NAMA
            $nameParts = explode(' ', strtolower($alumni->nama_lengkap));
            $matchCount = 0;
            foreach ($nameParts as $part) {
                if (strlen($part) > 2 && str_contains($combinedText, $part)) {
                    $matchCount++;
                }
            }
            if ($matchCount >= 2) $currentNama = 40;
            elseif ($matchCount == 1) $currentNama = 20;

            // B. SKOR AFILIASI
            if ($currentNama > 0) {
                if (str_contains($combinedText, 'muhammadiyah') || str_contains($combinedText, 'umm')) {
                    $currentAfiliasi = 40;
                }

                // C. SKOR TIMELINE 
                if (preg_match('/20[0-2][0-9]/', $combinedText, $yearMatch)) {
                    if ($yearMatch[0] >= ($alumni->tahun_lulus - 2) && $yearMatch[0] <= ($alumni->tahun_lulus + 4)) {
                        $currentTimeline = 20;
                    }
                }
            }

            $totalMalamIni = $currentNama + $currentAfiliasi + $currentTimeline;
            if ($totalMalamIni > ($scoreNama + $scoreAfiliasi + $scoreTimeline)) {
                $scoreNama = $currentNama;
                $scoreAfiliasi = $currentAfiliasi;
                $scoreTimeline = $currentTimeline;
                $snippetLengkap = $result['snippet'] ?? $snippet;
            }
        }

        $totalScore = $scoreNama + $scoreAfiliasi + $scoreTimeline;
        $status = $this->determineStatus($totalScore);

        TrackingLog::create([
            'alumni_id' => $alumni->id,
            'sumber_data' => 'LinkedIn via SerpApi',
            'judul_temuan' => ($totalScore >= 40) ? "Kandidat Teridentifikasi" : "Data Tidak Cocok",
            'bukti_snippet' => "Detail: Nama(+$scoreNama), Afiliasi(+$scoreAfiliasi), Waktu(+$scoreTimeline)",
            'link_bukti' => "https://www.google.com/search?q=" . urlencode($query),
            'score_nama' => $scoreNama,
            'score_afiliasi' => $scoreAfiliasi,
            'score_timeline' => $scoreTimeline,
            'total_confidence_score' => $totalScore,
        ]);

        $alumni->update(['status_pelacakan' => $status]);

        return redirect()->back()->with('success', 'Pelacakan diperbarui.');
    }

    private function determineStatus($score)
    {
        if ($score >= 80) return 'Terverifikasi';
        if ($score >= 40) return 'Perlu Verifikasi Manual';
        return 'Tidak Cocok';
    }
}
