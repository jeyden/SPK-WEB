<?php

namespace Database\Seeders;

use App\Models\RiasecQuestion;
use Illuminate\Database\Seeder;

class RiasecQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            // ========================================
            // REALISTIC (R)
            // ========================================

            [
                'category' => 'R',
                'indicator' => 1,
                'indicator_name' => 'Merawat dan Memperbaiki Perangkat',
                'question' => 'Saya tertarik mencoba memperbaiki komputer atau laptop ketika mengalami masalah sederhana.',
            ],
            [
                'category' => 'R',
                'indicator' => 1,
                'indicator_name' => 'Merawat dan Memperbaiki Perangkat',
                'question' => 'Saya senang membongkar, memasang, atau merakit perangkat elektronik untuk mengetahui cara kerjanya.',
            ],
            [
                'category' => 'R',
                'indicator' => 2,
                'indicator_name' => 'Memasang dan Mengatur Perangkat',
                'question' => 'Saya tertarik mencoba memasang kabel, router, atau perangkat Wi-Fi agar dapat digunakan dengan baik.',
            ],
            [
                'category' => 'R',
                'indicator' => 2,
                'indicator_name' => 'Memasang dan Mengatur Perangkat',
                'question' => 'Saya senang menata dan menghubungkan kabel atau perangkat elektronik agar terlihat rapi dan berfungsi dengan baik.',
            ],

            // ========================================
            // INVESTIGATIVE (I)
            // ========================================

            [
                'category' => 'I',
                'indicator' => 1,
                'indicator_name' => 'Mencari Solusi dan Memecahkan Masalah',
                'question' => 'Saya tertarik mencari penyebab ketika aplikasi, komputer, atau perangkat yang saya gunakan mengalami kesalahan.',
            ],
            [
                'category' => 'I',
                'indicator' => 1,
                'indicator_name' => 'Mencari Solusi dan Memecahkan Masalah',
                'question' => 'Saya senang menyelesaikan soal, permainan, atau teka-teki yang membutuhkan pemikiran dan logika.',
            ],
            [
                'category' => 'I',
                'indicator' => 2,
                'indicator_name' => 'Mengamati dan Menganalisis Informasi',
                'question' => 'Saya senang melihat angka, tabel, grafik, atau informasi untuk menemukan pola atau kesimpulan.',
            ],
            [
                'category' => 'I',
                'indicator' => 2,
                'indicator_name' => 'Mengamati dan Menganalisis Informasi',
                'question' => 'Saya tertarik mencari tahu bagaimana sebuah aplikasi atau sistem bekerja ketika saya menggunakannya.',
            ],

            // ========================================
            // ARTISTIC (A)
            // ========================================

            [
                'category' => 'A',
                'indicator' => 1,
                'indicator_name' => 'Membuat Tampilan Menarik',
                'question' => 'Saya senang mengatur warna, gambar, tulisan, atau tata letak agar tampilan tugas atau media digital terlihat menarik.',
            ],
            [
                'category' => 'A',
                'indicator' => 1,
                'indicator_name' => 'Membuat Tampilan Menarik',
                'question' => 'Saya tertarik memilih warna, gambar, dan bentuk yang menurut saya cocok dan nyaman untuk dilihat.',
            ],
            [
                'category' => 'A',
                'indicator' => 2,
                'indicator_name' => 'Mengembangkan Ide Kreatif',
                'question' => 'Saya senang membuat gambar, poster, infografis, atau karya visual menggunakan komputer atau ponsel.',
            ],
            [
                'category' => 'A',
                'indicator' => 2,
                'indicator_name' => 'Mengembangkan Ide Kreatif',
                'question' => 'Saya senang mencari cara yang berbeda dan kreatif untuk menyajikan tugas, informasi, atau ide kepada orang lain.',
            ],

            // ========================================
            // SOCIAL (S)
            // ========================================

            [
                'category' => 'S',
                'indicator' => 1,
                'indicator_name' => 'Membantu dan Mengajari Orang Lain',
                'question' => 'Saya senang membantu teman yang kesulitan menggunakan aplikasi, komputer, atau teknologi lainnya.',
            ],
            [
                'category' => 'S',
                'indicator' => 1,
                'indicator_name' => 'Membantu dan Mengajari Orang Lain',
                'question' => 'Saya mampu menjelaskan cara menggunakan sesuatu yang sulit kepada teman dengan bahasa yang mudah dipahami.',
            ],
            [
                'category' => 'S',
                'indicator' => 2,
                'indicator_name' => 'Bekerja Sama dan Berkomunikasi',
                'question' => 'Saya senang bekerja dalam kelompok untuk berdiskusi dan menyelesaikan tugas bersama.',
            ],
            [
                'category' => 'S',
                'indicator' => 2,
                'indicator_name' => 'Bekerja Sama dan Berkomunikasi',
                'question' => 'Saya bersedia mendengarkan masalah atau kesulitan teman dan membantu mencari solusinya.',
            ],

            // ========================================
            // ENTERPRISING (E)
            // ========================================

            [
                'category' => 'E',
                'indicator' => 1,
                'indicator_name' => 'Memimpin dan Mengatur Kegiatan',
                'question' => 'Saya senang menjadi orang yang mengatur pembagian tugas ketika mengerjakan kegiatan atau tugas kelompok.',
            ],
            [
                'category' => 'E',
                'indicator' => 1,
                'indicator_name' => 'Memimpin dan Mengatur Kegiatan',
                'question' => 'Saya percaya diri mengambil keputusan ketika kelompok harus menentukan pilihan atau menyelesaikan suatu masalah.',
            ],
            [
                'category' => 'E',
                'indicator' => 2,
                'indicator_name' => 'Mencari Peluang dan Mempengaruhi Orang Lain',
                'question' => 'Saya tertarik memikirkan cara menghasilkan uang dari ide, produk, aplikasi, atau kegiatan yang saya buat.',
            ],
            [
                'category' => 'E',
                'indicator' => 2,
                'indicator_name' => 'Mencari Peluang dan Mempengaruhi Orang Lain',
                'question' => 'Saya senang menyampaikan pendapat dan meyakinkan teman agar tertarik dengan ide atau rencana yang saya ajukan.',
            ],

            // ========================================
            // CONVENTIONAL (C)
            // ========================================

            [
                'category' => 'C',
                'indicator' => 1,
                'indicator_name' => 'Mengatur dan Memeriksa Data',
                'question' => 'Saya senang menyusun daftar, jadwal, atau data ke dalam tabel agar lebih rapi dan mudah dibaca.',
            ],
            [
                'category' => 'C',
                'indicator' => 1,
                'indicator_name' => 'Mengatur dan Memeriksa Data',
                'question' => 'Saya terbiasa memeriksa kembali angka, tugas, atau data sebelum mengumpulkannya untuk memastikan tidak ada kesalahan.',
            ],
            [
                'category' => 'C',
                'indicator' => 2,
                'indicator_name' => 'Mengikuti Aturan dan Membuat Dokumentasi',
                'question' => 'Saya nyaman mengerjakan tugas atau kegiatan dengan mengikuti aturan dan langkah-langkah yang sudah ditentukan.',
            ],
            [
                'category' => 'C',
                'indicator' => 2,
                'indicator_name' => 'Mengikuti Aturan dan Membuat Dokumentasi',
                'question' => 'Saya senang membuat catatan, daftar, atau laporan kegiatan secara rapi dan teratur.',
            ],
        ];

        foreach ($questions as $question) {
            RiasecQuestion::create($question);
        }
    }
}