# SIPA - Sistem Intelejen Pelacakan Alumni

**SIPA** adalah platform berbasis web yang dirancang untuk mengotomatisasi pencarian jejak digital alumni menggunakan integrasi **SerpApi (Google Search)** serta algoritma **Disambiguasi** untuk memvalidasi identitas profesional (LinkedIn) dan akademik (Google Scholar). 

Proyek ini merupakan pemenuhan tugas **Daily Project 3** pada mata kuliah **Rekayasa Kebutuhan**.

---

## Demo & Tautan

* **Link Website SIPA:** [https://nadzarsecario.my.id](https://nadzarsecario.my.id)
* **Repository:** [https://github.com/Nadzarsecario/Project_Sipa](https://github.com/Nadzarsecario/Project_Sipa)

---

## Logika Scoring (Disambiguasi)

Sistem menggunakan pembobotan atribut untuk menentukan tingkat kepercayaan (*Confidence Score*) terhadap data yang ditemukan di mesin pencari.

**Rumus Scoring:**

| Komponen | Skor Maks | Kriteria |
| :--- | :---: | :--- |
| **Atribut Nama** | 40 | Kecocokan nama lengkap melalui teknik *String Matching*. |
| **Atribut Afiliasi** | 40 | Kecocokan instansi atau kampus (UMM / Informatika). |
| **Atribut Timeline** | 20 | Kecocokan rentang tahun kelulusan dengan data temuan. |

---

## Tabel Pengujian Kualitas (Quality Assurance)

Berdasarkan rancangan pada **Daily Project 2**, berikut adalah hasil pengujian fungsionalitas pada lingkungan produksi (Live Server):

| No | Skenario Pengujian | Input Data (Simulasi) | Expected Result (Score) | Status Akhir | Keterangan |
| :--: | :--- | :--- | :---: | :---: | :--- |
| 1 | **Data Identitas Sempurna** | Nama Lengkap, UMM, Tahun Lulus Sesuai | 100 | ✅ Terverifikasi | LULUS |
| 2 | **Ambiguitas Tahun** | Nama & Universitas Cocok, Tahun tidak spesifik | 80 | ✅ Verifikasi Manual | LULUS |
| 3 | **Ambiguitas Afiliasi** | Nama & Tahun Cocok, Univ tidak spesifik | 60 | ⚠️ Verifikasi Manual | LULUS |
| 4 | **Data Nama Sempurna** | Nama Cocok, Univ dan tahun tidak spesifik | 40 | ⚠️ Verifikasi Manual | LULUS |
| 5 | **Data Tidak Relevan** | Nama & Tahun tidak ditemukan di web | 0 | ❌ Tidak Cocok | LULUS |
| 6 | **Reset Database** | Menghapus seluruh logs temuan | Logs Kosong | ✅ Cleaned | LULUS |

---

## Struktur Proyek

* **`app/Http/Controllers/AlumniTrackingController.php`**: Logika utama mesin pelacakan, integrasi API, dan algoritma scoring.
* **`resources/views/dashboard.blade.php`**: Dashboard interaktif berbasis Tailwind & Alpine.js.
* **`routes/web.php`**: Definisi endpoint pelacakan dan manajemen log.
* **`.env`**: Konfigurasi environment server dan API Key.

---

## Identitas Pengembang

* **Nama:** Nadzar Secario Djaku
* **NIM:** 202310370311025
* **Kelas:** Rekayasa Kebutuhan D
* **Instansi:** Universitas Muhammadiyah Malang
