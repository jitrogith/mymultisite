<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = ['content'];

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    public static function defaults(): array
    {
        return [
            'site_name' => 'Nusantara',
            'province' => 'Provinsi Republik Indonesia',
            'location' => 'Kabupaten Nusantara, Indonesia',
            'address' => 'Jl. Merdeka No. 1, Kabupaten Nusantara, Indonesia 12345',
            'phone' => '(021) 1234-567',
            'email' => 'info@nusantara.go.id',
            'hero_eyebrow' => 'Melayani dengan sepenuh hati',
            'hero_title' => 'Bersama membangun',
            'hero_highlight' => 'Nusantara yang lebih baik.',
            'hero_description' => 'Selamat datang di situs resmi Pemerintah Kabupaten Nusantara. Temukan informasi daerah, akses layanan publik, dan sampaikan aspirasi Anda dalam satu ruang informasi yang terbuka.',
            'announcement_label' => 'Pengumuman',
            'announcement_title' => 'Informasi:',
            'announcement_body' => 'Jadwal pelayanan administrasi kependudukan selama bulan Oktober 2026.',
            'services' => [
                ['title' => 'Administrasi Kependudukan', 'description' => 'Urus dokumen kependudukan dan pencatatan sipil.'],
                ['title' => 'Perizinan Berusaha', 'description' => 'Informasi dan pengajuan izin usaha daerah.'],
                ['title' => 'Pendidikan & Kesehatan', 'description' => 'Akses fasilitas, program, dan layanan masyarakat.'],
                ['title' => 'Pajak & Retribusi', 'description' => 'Informasi kewajiban dan pembayaran daerah.'],
            ],
            'news' => [
                ['date' => '22 Oktober 2026', 'author' => 'Humas Daerah', 'category' => 'Pemerintahan', 'title' => 'Musrenbang Kabupaten, pemerintah serap aspirasi untuk pembangunan 2027', 'excerpt' => 'Kolaborasi pemerintah dan masyarakat menjadi landasan dalam menyusun prioritas pembangunan yang inklusif dan berkelanjutan.'],
                ['date' => '20 Oktober 2026', 'author' => 'Disdukcapil', 'category' => 'Pelayanan', 'title' => 'Perluas layanan administrasi kependudukan hingga ke kecamatan', 'excerpt' => 'Warga dapat mengakses layanan dokumen lebih dekat melalui pelayanan keliling terjadwal.'],
                ['date' => '18 Oktober 2026', 'author' => 'Diskominfo', 'category' => 'Penghargaan', 'title' => 'Kabupaten Nusantara raih apresiasi keterbukaan informasi publik', 'excerpt' => 'Komitmen menyajikan informasi yang mudah diakses terus diperkuat di seluruh perangkat daerah.'],
            ],
            'agendas' => [
                ['day' => '24', 'month' => 'Okt', 'title' => 'Rapat koordinasi evaluasi pembangunan daerah', 'location' => 'Ruang Rapat Utama · Sekretariat Daerah', 'time' => '09.00 WIB'],
                ['day' => '27', 'month' => 'Okt', 'title' => 'Musyawarah perencanaan pembangunan kabupaten', 'location' => 'Aula Kantor Pemerintah Daerah · Bappeda', 'time' => '10.00 WIB'],
                ['day' => '30', 'month' => 'Okt', 'title' => 'Pelayanan terpadu keliling di Kecamatan Utara', 'location' => 'Halaman Kantor Kecamatan · Disdukcapil', 'time' => '08.30 WIB'],
            ],
            'transparency_title' => 'Informasi publik adalah hak setiap warga.',
            'transparency_description' => 'Akses dokumen, data, dan informasi penyelenggaraan pemerintahan melalui Pejabat Pengelola Informasi dan Dokumentasi.',
            'footer_description' => 'Situs resmi Pemerintah Kabupaten Nusantara sebagai sarana informasi publik dan pelayanan kepada masyarakat.',
        ];
    }

    public static function current(): array
    {
        return array_replace(
            self::defaults(),
            self::query()->find(1)?->content ?? [],
        );
    }
}
