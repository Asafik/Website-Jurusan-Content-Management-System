<?php

namespace App\Http\Controllers\Web\Backend\Iku;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\Iku\IkuProdiTrkRequest;
use App\Models\IkuProdiTrk;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class IkuProdiTrkController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Indikator Kinerja Utama TRK',
            'mods' => 'iku_prodi_trk'
        ];

        return customView('iku_prodi_trk.index', $data, 'backend');
    }

    public function getData()
    {
        return DataTables::of(IkuProdiTrk::query())->addColumn('hashid', function ($data) {
            return Hashids::encode($data->id);
        })->make(true);
    }

    public function show(IkuProdiTrk $ikuProdiTrk)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $ikuProdiTrk
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }

    public function store(IkuProdiTrkRequest $request)
    {
        try {
            IkuProdiTrk::create([
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

    public function update(IkuProdiTrkRequest $request, IkuProdiTrk $ikuProdiTrk)
    {
        try {
            $ikuProdiTrk->update([
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

    public function destroy(IkuProdiTrk $ikuProdiTrk)
    {
        try {
            $ikuProdiTrk->delete();
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
