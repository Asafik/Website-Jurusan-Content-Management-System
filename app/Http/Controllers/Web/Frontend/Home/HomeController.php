<?php

namespace App\Http\Controllers\Web\Frontend\Home;

use App\Http\Controllers\Controller;
use App\Models\Cooperation;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\Event;
use App\Models\IkuJurusan;
use App\Models\IkuProdiTrpl;
use App\Models\IkuProdiTrk;
use App\Models\IkuProdiBisnisDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Achievement;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use App\Models\Page; // Import model Page
class HomeController extends Controller

{


    public function index()
    {
        // Mendapatkan token dari request
        $token = $this->requestToken();

        // Memanggil API untuk mendapatkan data mahasiswa dengan token yang diperoleh
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://sister-api.kemdikbud.go.id/ws.php/1.0/referensi/mahasiswa_pddikti?id_perguruan_tinggi=b9b3f729-9c5b-4c82-8b36-4d63decccdb2&id_program_studi=bbe32aca-5907-4f3a-8ff1-3f427abf62d1');

        // Mengambil data JSON dari respons
        $mahasiswa = $response->json();

        // Data untuk dikirimkan ke view 'frontend.home.index'
        $data = [
            'title' => 'Beranda',
            'mahasiswa' => $mahasiswa
        ];

        // Menampilkan tampilan beranda dengan data yang telah ditentukan
        return view('frontend.home.index', $data);
    }

    //Halaman Page/
    public function pageBySlug($slug)
    {
        $page = Page::where(['slug' => $slug, 'is_publish' => true])->first();

        if ($page) {
            return view('frontend.home.pagebyslug', ['page' => $page]);
        } else {
            abort(404);
        }
    }

    public function refreshCsrf()
    {
        return response()->json(['csrf_token' => csrf_token()]);
    }


      // Mengatur data yang akan digunakan dalam tampilan beranda

    // Mengatur data yang akan digunakan dalam tampilan dokumen
    public function document()
    {
        $data = [
            'title' => 'Dokumen',
            'documents' => Document::with(['documentType'])->where('is_publish', true)->get(),
        ];
    // Menampilkan tampilan dokumen dengan data yang telah ditentukan
        return view('frontend.home./dokumen.document', $data);
    }

    //Halaman Detail Event
    public function event($slug)
    {
        // Mencari acara berdasarkan slug yang diberikan
        $event = Event::where(['slug' => $slug, 'is_publish' => true])->first();
        // Jika acara ditemukan
        if ($event) {
             // Mengatur data yang akan digunakan dalam tampilan acara
            $data = [
                'title' => $event->title,
                'event' => $event,
            ];
            // Menampilkan tampilan acara dengan data yang telah ditentukan
            return view('frontend.home.event', $data);
        } else {
             // Jika acara tidak ditemukan, tampilkan halaman error 404
            abort(404);
        }
    }
    // Menampilkan halaman All Event.
    public function allevent()
{
    $data = [
        'title' => 'All Berita Dan Event Jurusan',
        'events' => Event::where('is_publish', true)->get()
    ];

    return view('frontend.home.all_event', $data);
}



    // JURUSAN
    // Menampilkan halaman profil jurusan.
    public function profil()
    {
        $data = [
            'title' => 'Profil Jurusan',
        ];

        return view('frontend.home./jurusan.profil', $data);
    }


    // Menampilkan halaman sejarah jurusan.
    public function sejarah()
    {
        $data = [
            'title' => 'Sejarah',
        ];

        return view('frontend.home./jurusan.sejarah', $data);
    }


    // Menampilkan halaman visi dan misi jurusan
  	public function visimisi()
    {
        $data = [
            'title' => 'Visi dan Misi',
        ];

        return view('frontend.home./jurusan.visimisi', $data);
    }


    // Menampilkan halaman struktur organisasi jurusan
  	public function organisasi()
    {
        $data = [
            'title' => 'Struktur Organisasi',
        ];

        return view('frontend.home./jurusan.organisasi', $data);
    }

    // Menampilkan halaman kerjasama industri
    public function cooperation()
    {
        $data = [
            'title' => 'Kerjasama Industri',
            'cooperations' => Cooperation::with(['cooperationField', 'cooperationType', 'partner'])->where('is_publish', true)->get()
        ];

        return view('frontend.home./jurusan.cooperation', $data);
    }
    // Menampilkan halaman daftar dosen dan staff berdasarkan jenis pegawai.


    //AKADEMIK
    public function trpl()
    {
        $data = [
            'title' => 'D4 Terapan Teknik Rekayasa Perangkat Lunak',
        ];

        return view('frontend.home./akademik.trpl', $data);
    }
  	public function trk()
    {
        $data = [
            'title' => 'D4 Terapan Rekayasa Komputer',
        ];

        return view('frontend.home./akademik.trk', $data);
    }
  	public function bsd()
    {
        $data = [
            'title' => 'D4 Terapan Bisnis Digital',
        ];

        return view('frontend.home./akademik.bsd', $data);
    }


    public function kalender()
    {
        $data = [
            'title' => 'Kalender Akademik',
        ];

        return view('frontend.home./akademik.kalender', $data);
    }

    public function pedoman()
    {
        $data = [
            'title' => 'Pedoman Akademik',
        ];

        return view('frontend.home./akademik.pedoman', $data);
    }
    public function peraturan()
    {
        $data = [
            'title' => 'Peraturan Akademik',
        ];

        return view('frontend.home./akademik.peraturan', $data);
    }

    public function jalurmasuk()
    {
        $data = [
            'title' => 'Jalur Masuk',
        ];

        return view('frontend.home./akademik.jalurmasuk', $data);
    }

    public function beasiswa()
    {
        $data = [
            'title' => 'Beasiswa',
        ];

        return view('frontend.home./akademik.beasiswa', $data);
    }


    public function biaya()
    {
        $data = [
            'title' => 'Biaya Pendidikan',
        ];

        return view('frontend.home./akademik.biaya', $data);
    }


    //Indikator Kinerja Utama Jurusan
    public function iku_jurusan_bi()
    {
        $ikus = IkuJurusan::all();
        $data = [
            'title' => 'Indikator Kinerja Utama Jurusan',
            'ikus' => $ikus
        ];

        return view('frontend.home.akademik.iku_jurusan', $data);
    }


    public function loadIkuJurusan($id)
    {
        Log::info('loadIkuJurusan called with id: ' . $id);

        $iku = IkuJurusan::find($id);
        if ($iku) {
            Log::info('IkuJurusan found: ' . $iku->content);
            return response()->json(['content' => $iku->content]);
        }
        Log::warning('IkuJurusan not found with id: ' . $id);
        return response()->json(['content' => 'Halaman tidak ditemukan.']);
    }

    //Indikator Kinerja Utama TRPL
    public function iku_prodi_trpl()
    {

        $trpls = IkuProdiTrpl::all();  // Mengganti IkuJurusan dengan IkuProdiTrpl
        $data = [
            'title' => 'Indikator Kinerja Utama Program Studi Rekayasa Perangkat Lunak',
            'trpls' => $trpls
        ];

        return view('frontend.home.akademik.iku_trpl', $data);
    }

        public function loadIkuProdiTrpl($id)
    {
        Log::info('loadIkuProdiTrpl called with id: ' . $id);  // Mengganti nama method

        $trpl = IkuProdiTrpl::find($id);  // Mengganti IkuJurusan dengan IkuProdiTrpl
        if ($trpl) {
            Log::info('IkuProdiTrpl found: ' . $trpl->content);  // Mengganti nama variabel
            return response()->json(['content' => $trpl->content]);
        }
        Log::warning('IkuProdiTrpl not found with id: ' . $id);  // Mengganti nama variabel
        return response()->json(['content' => 'Halaman tidak ditemukan.']);
    }

    //Indikator Kinerja Utama TRK
    public function iku_prodi_trk()
    {
        $trks = IkuProdiTrk::all();  // Mengganti IkuProdiTrpl dengan IkuProdiTrk
        $data = [
            'title' => 'Indikator Kinerja Utama Program Studi Teknik Komputer',
            'trks' => $trks
        ];

        return view('frontend.home.akademik.iku_trk', $data);
    }

        public function loadIkuProdiTrk($id)
    {
        Log::info('loadIkuProdiTrk called with id: ' . $id);  // Mengganti nama method

        $trk = IkuProdiTrk::find($id);  // Mengganti IkuProdiTrpl dengan IkuProdiTrk
        if ($trk) {
            Log::info('IkuProdiTrk found: ' . $trk->content);  // Mengganti nama variabel
            return response()->json(['content' => $trk->content]);
        }
        Log::warning('IkuProdiTrk not found with id: ' . $id);  // Mengganti nama variabel
        return response()->json(['content' => 'Halaman tidak ditemukan.']);
    }

    //Indikator Kinerja Utama Bisnia Digital
     public function iku_prodi_bisnis_digital()
     {

        $bisnisDigitals = IkuProdiBisnisDigital::all();  // Mengganti IkuProdiTrk dengan IkuProdiBisnisDigital
        $data = [
            'title' => 'Indikator Kinerja Utama Program Studi Bisnis Digital',
            'bisnisDigitals' => $bisnisDigitals
        ];

         return view('frontend.home.akademik.iku_bisnis_digital', $data);
     }

        public function loadIkuProdiBisnisDigital($id)
    {
        Log::info('loadIkuProdiBisnisDigital called with id: ' . $id);  // Mengganti nama method

        $bisnisDigital = IkuProdiBisnisDigital::find($id);  // Mengganti IkuProdiTrk dengan IkuProdiBisnisDigital
        if ($bisnisDigital) {
            Log::info('IkuProdiBisnisDigital found: ' . $bisnisDigital->content);  // Mengganti nama variabel
            return response()->json(['content' => $bisnisDigital->content]);
        }
        Log::warning('IkuProdiBisnisDigital not found with id: ' . $id);  // Mengganti nama variabel
        return response()->json(['content' => 'Halaman tidak ditemukan.']);
    }


    // Kemahasiswaan
    public function presma()
    {
        $data = [
            'title' => 'Prestasi Mahasiswa',
            'achievements' => Achievement::with(['achievementType', 'achievementLevel'])
                ->orderByRaw("CASE WHEN achievement_level_id = 1 THEN 0 ELSE 1 END, achievement_level_id")
                ->where('is_publish', true)
                ->get()
        ];
        return view('frontend.home./kemahasiswaan.presma', $data);
    }

    //Fitur Prestasi
    public function achievement($slug)
    {
        // Mencari pencapaian berdasarkan slug yang diberikan
        $achievement = Achievement::where(['slug' => $slug, 'is_publish' => true])->first();
        // Jika pencapaian ditemukan
        if ($achievement) {
             // Mengatur data yang akan digunakan dalam tampilan pencapaian
            $data = [
                'title' => $achievement->title,
                'achievement' => $achievement,
            ];
            // Menampilkan tampilan pencapaian dengan data yang telah ditentukan
            return view('frontend.home./kemahasiswaan.presma_detail', $data);
        } else {
             // Jika pencapaian tidak ditemukan, tampilkan halaman error 404
            abort(404);
        }
    }




    public function ormawa()
    {
        $data = [
            'title' => 'Organisasi Kemahasiswaan',
        ];

        return view('frontend.home./kemahasiswaan.ormawa', $data);
    }

    public function kehidupan()
    {
        $data = [
            'title' => 'Kehidupan Kampus',
        ];

        return view('frontend.home./kemahasiswaan.kehidupan', $data);
    }

    public function employee()
    {
        $data = [
            'title' => 'Dosen dan Staff',
            'employees' => EmployeeType::with(['employee'])->get(),
        ];

        return view('frontend.home./jurusan.employee', $data);
    }


    //Detail Staff Dengan Api Publikasi
    public function detailStaff($slug)
    {
        // Ambil data pegawai berdasarkan slug
        $employee = Employee::where('slug', $slug)->firstOrFail();

        // Ambil id_sdm dari tabel employees berdasarkan slug
        $id_sdm = Employee::where('slug', $slug)->value('id_sdm');

        // Memanggil fungsi permintaan token untuk mendapatkan token
        $token = $this->requestToken();

        // Memanggil API dengan token yang diperoleh untuk mendapatkan detail publikasi dosen berdasarkan id_sdm
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://sister-api.kemdikbud.go.id/ws.php/1.0/publikasi?id_sdm=' . $id_sdm);

        $data = $response->json();

        // Persiapkan data untuk tampilan
        $data = [
            'title' => $employee->name,
            'data' => $employee,
            'employees' => EmployeeType::with(['employee'])->get(),
            'publikasi' => $data,
        ];

        // Kembalikan tampilan dengan data yang sudah disiapkan
        return view('frontend.home.jurusan.detail', $data);
    }





    //Request Token Otomaatiss
    private function requestToken() {
        $response = Http::post('https://sister-api.kemdikbud.go.id/ws.php/1.0/authorize', [
            'username' => 'D4nD/ElznKfrIzXF/F/zOoWaMILDjdMXZ2dvlDHuHC8=',
            'password' => '/NZ0ZOppg7ghIJtx4rUhUgN2F+j59m3qPdqYq+vvTdQLrK48h/G2izl+Kw5Wza9O',
            'id_pengguna' => '86c7c79e-a1b7-4953-a5cf-9549ffc0b5fb',
        ]);

        $data = $response->json();

        return $data['token'];
    }

}
