<?php

namespace App\Http\Controllers\Web\Backend\Iku;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\Iku\IkuProdiTrplRequest;
use App\Models\IkuProdiTrpl;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class IkuProdiTrplController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Indikator Kinerja Utama TRPL',
            'mods' => 'iku_prodi_trpl'
        ];

        return customView('iku_prodi_trpl.index', $data, 'backend');
    }

    public function getData()
    {
        return DataTables::of(IkuProdiTrpl::query())->addColumn('hashid', function ($data) {
            return Hashids::encode($data->id);
        })->make(true);
    }

    public function show(IkuProdiTrpl $ikuProdiTrpl)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $ikuProdiTrpl
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }

    public function store(IkuProdiTrplRequest $request)
    {
        try {
            IkuProdiTrpl::create([
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

    public function update(IkuProdiTrplRequest $request, IkuProdiTrpl $ikuProdiTrpl)
    {
        try {
            $ikuProdiTrpl->update([
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

    public function destroy(IkuProdiTrpl $ikuProdiTrpl)
    {
        try {
            $ikuProdiTrpl->delete();
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
