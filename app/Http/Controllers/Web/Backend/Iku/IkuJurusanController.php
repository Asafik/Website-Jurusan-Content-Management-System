<?php

namespace App\Http\Controllers\Web\Backend\Iku;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\Iku\IkuJurusanRequest;
use App\Models\IkuJurusan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class IkuJurusanController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Indikator Kinerja Utama Jurusan',
            'mods' => 'iku_jurusan'
        ];

        return customView('iku_jurusan.index', $data, 'backend');
    }

    public function getData()
    {
        return DataTables::of(IkuJurusan::query())->addColumn('hashid', function ($data) {
            return Hashids::encode($data->id);
        })->make(true);
    }

    public function show(IkuJurusan $ikuJurusan)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $ikuJurusan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }


    public function store(IkuJurusanRequest $request)
    {
        try {
            IkuJurusan::create([
                'title' => $request->title,
                'content' => $request->content,
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




    public function update(IkuJurusanRequest $request, IkuJurusan $ikuJurusan) // Mengubah request dan model
    {
        try {

            $ikuJurusan->update([ // Mengubah model
                'title' => $request->title,
                'content' => $request->content,
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



    public function destroy(IkuJurusan $ikuJurusan)
    {
        try {
            $ikuJurusan->delete();
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
}
