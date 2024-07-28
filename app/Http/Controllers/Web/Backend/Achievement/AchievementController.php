<?php

namespace App\Http\Controllers\Web\Backend\Achievement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\Achievement\AchievementRequest;
use App\Models\Achievement;
use App\Models\AchievementType;
use App\Models\AchievementLevel;
use App\Models\AchievementProgramStudi;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;
use File;
use \Illuminate\Support\Carbon;
// use Carbon\Carbon;

class AchievementController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Prestasi Mahasiswa',
            'mods' => 'achievement',
            'achievementTypes' => AchievementType::all(),
            'achievementLevels' => AchievementLevel::all(),
            'achievementProgramStudis' => AchievementProgramStudi::all()
        ];

        return customView('achievement.index', $data, 'backend');
    }

    public function getData()
    {
        return DataTables::of(Achievement::with(['achievementType', 'achievementLevel', 'achievementProgramStudi'])->get())
            ->addColumn('achievement_type', function ($data) {
                return $data->achievementType->name;
            })
            ->addColumn('achievement_level', function ($data) {
                return $data->achievementLevel->name; // Mengambil nama tingkat prestasi
            })
            ->addColumn('achievement_program_studi', function ($data) {
                return $data->achievementProgramStudi->name; 
            })
            ->addColumn('hashid', function ($data) {
                return Hashids::encode($data->id);
            })
            ->make(true);
    }


    public function show(Achievement $achievement)
{
    try {
        return response()->json([
            'success' => true,
            'data' => $achievement,
            'achievement_type_id' => Hashids::encode($achievement->achievement_type_id),
            'achievement_level_id' => Hashids::encode($achievement->achievement_level_id),
            'achievement_program_studi_id' => Hashids::encode($achievement->achievement_program_studi_id),
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'trace' => $e->getTrace()
        ]);
    }
}


public function store(AchievementRequest $request)
{
    try {
        if ($request->hasFile('file')) {
            $image = $this->uploadImage($request);
        } else {
            $image = null;
        }

        // Menggunakan Hashids untuk mendekode id
        $achievement_type_id = Hashids::decode($request->achievement_type_id)[0]; // Mendekode id tipe prestasi
        $achievement_level_id =  Hashids::decode($request->achievement_level_id)[0];
        $achievement_program_studi_id =  Hashids::decode($request->achievement_program_studi_id)[0];

        $tanggal = Carbon::parse($request->date)->translatedFormat('Y-m-d');

        Achievement::create([
            'achievement_type_id' => $achievement_type_id,
            'achievement_level_id' => $achievement_level_id,
            'achievement_program_studi_id' => $achievement_program_studi_id,
            'user_id' => getInfoLogin()->id,
            'title' => $request->title,
            'location' => $request->location,
            'date' => $tanggal,
            'slug' => $request->slug,
            'image' => $image,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Data telah ditambahkan'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'trace' => $e->getTrace()
        ], 500);
    }
}


public function update(AchievementRequest $request, Achievement $achievement)
{
    try {
        if ($request->hasFile('file')) {
            // Hapus gambar lama sebelum menyimpan yang baru
            File::delete(public_path('storage/images/achievement/' . $achievement->image));
            $image = $this->uploadImage($request);
        } else {
            // Jika tidak ada file yang diunggah, gunakan gambar yang sudah ada
            $image = $achievement->image;
        }

          // Menggunakan Hashids untuk mendekode id
          $achievement_type_id = Hashids::decode($request->achievement_type_id)[0]; // Mendekode id tipe prestasi
          $achievement_level_id =  Hashids::decode($request->achievement_level_id)[0];
          $achievement_program_studi_id =  Hashids::decode($request->achievement_program_studi_id)[0];

        // Ubah format tanggal
        $tanggal = Carbon::parse($request->date)->translatedFormat('Y-m-d');

        // Update data prestasi
        $achievement->update([
            'title' => $request->title,
            'location' => $request->location,
            'achievement_level_id' => $achievement_level_id, // Sesuaikan dengan tingkat prestasi yang ingin Anda tentukan
            'achievement_type_id' => $achievement_type_id,
            'achievement_program_studi_id' => $achievement_program_studi_id,
            'date' => $tanggal,
            'slug' => $request->slug,
            'image' => $image,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Data telah diubah'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'trace' => $e->getTrace()
        ], 500);
    }
}

public function updateStatus(Achievement $achievement) // Ubah parameter menjadi Achievement
{
    if (\Request::ajax()) {
        try {
            $achievement->update(['is_publish' => $achievement->is_publish == true ? false : true, 'published_at' => Carbon::now()]);

            return response()->json([
                'message' => 'Data telah diperbarui'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    } else {
        abort(403);
    }
}

    public function destroy(Achievement $achievement)
    {
        try {
            if ($achievement->image != null && file_exists(public_path('storage/images/achievement/' . $achievement->image))) {
                File::delete(public_path('storage/images/achievement/' . $achievement->image));
            }
            $achievement->delete();
            return response()->json([
                'message' => 'Data telah dihapus',
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return response()->json([
                    'message' => 'Data tidak dapat dihapus karena sudah digunakan',
                ], 500);
            } else {
                return response()->json([
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                ], 500);
            }
        }
    }

    private function uploadImage(Request $request)
    {
        $path = public_path('storage/images/achievement');
        $image = $request->file('file');
        $filename = 'Achievements_' . rand(0, 9999999999) . '_' . rand(0, 9999999999) . '.';
        $filename .= $image->getClientOriginalExtension();
        $image->move($path, $filename);
        return $filename;
    }
}
