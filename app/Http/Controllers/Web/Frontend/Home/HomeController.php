<?php

namespace App\Http\Controllers\Web\Frontend\Home;

use App\Http\Controllers\Controller;
use App\Models\Cooperation;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Achievement;
use Illuminate\Support\Str;

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





    //Publikasi Jurusan Bisnis Dan Informatika
    public function publikasijurusan()
    {
        $data = [
            'title' => 'Publikasi Jurusan',
        ];

        // Daftar ID SDM spesifik
        $id_sdm_dosen = [
           'd8722444-05af-459f-838d-6d905af81770',
           'cf7bb033-168b-4507-8c5d-7dacc57d5b73',
           'c06c52e8-2d24-4417-898d-1b43fe7eb08f',
           '19c4ad0b-7e36-49ab-9d15-0c6d85134b6f',
           '0475fc50-9240-4689-8cac-9598a689ed49',
           '12c99923-e00e-48f2-869a-da35dd29123c',
           '8a873502-fa06-4e31-a697-6de9906a0fc1',
           '0e60a0f1-d57c-4a95-8b6f-323f78edb607',
           'a8e1bc04-962f-4788-9651-bf573039e3f2',
           'a5508e4a-7c84-4030-930a-e0026a484ba9',
           'bb5e6c12-4fee-42ff-a323-0d2d47a55f1a',
           '518473dd-8bba-4519-bb53-8422f2de6e8f',
           '86ca7be5-6aa7-4c13-b195-14670f3db7f3',
           '6ea6b6c8-4b6b-4d76-8cf4-45e557cada6f',
           '29c162a6-2eb9-4a3e-a398-5960b765e786',
           'd2d07dff-8838-489f-9c2e-b9909717280',
           '269ecb19-0475-414f-ab68-dce1abd719f9',
           '51721c9b-fe38-468f-a496-4ae9280b1bd5',
           '5a386c83-63f5-46a2-8bca-0b94104b0324',
           'cb4b79bc-162e-4ead-b339-4d35288cf37a',
           '04cd36ec-14eb-42af-8cdb-339f4d24737e',
           'cff56fc8-6bd0-47fb-b8b1-6eda97249966',
           '411b2d24-24ad-4968-9caf-fb8a51656956',
           'fa32bd47-822a-494f-85a1-44413ecb3b11',

        ];

        // List untuk menyimpan seluruh publikasi
        $allPublications = [];

        // Mendapatkan token
        $token = $this->requestToken();

        foreach ($id_sdm_dosen as $id_sdm) {
            $publications = $this->getPublicationsByIdSdm($id_sdm, $token);
            if (is_array($publications)) {
                $allPublications = array_merge($allPublications, $publications);
            }
        }

        // Mendefinisikan variabel publikasi untuk view
        $data['publikasi'] = $allPublications;

        // Pastikan path view sesuai dengan folder dan nama file
        return view('frontend.home.jurusan.publikasi_jurusan', $data);
    }

    // Mendapatkan publikasi berdasarkan id_sdm
    private function getPublicationsByIdSdm($id_sdm, $token)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://sister-api.kemdikbud.go.id/ws.php/1.0/publikasi?id_sdm=' . $id_sdm);

        if ($response->successful()) {
            return $response->json();
        } else {
            // Log error atau lakukan penanganan sesuai kebutuhan
            return [];
        }
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
