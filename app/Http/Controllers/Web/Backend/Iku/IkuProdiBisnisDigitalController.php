<?php

namespace App\Http\Controllers\Web\Backend\Iku;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Backend\Iku\IkuProdiBisnisDigitalRequest;
use App\Models\IkuProdiBisnisDigital;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class IkuProdiBisnisDigitalController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'IKU Bisnis Digital',
            'mods' => 'iku_prodi_bisnis_digital'
        ];

        return customView('iku_prodi_bisnis_digital.index', $data, 'backend');
    }

    public function getData()
    {
        return DataTables::of(IkuProdiBisnisDigital::query())->addColumn('hashid', function ($data) {
            return Hashids::encode($data->id);
        })->make(true);
    }

    public function show(IkuProdiBisnisDigital $ikuProdiBisnisDigital)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $ikuProdiBisnisDigital
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }

    public function store(IkuProdiBisnisDigitalRequest $request)
    {
        try {
            IkuProdiBisnisDigital::create([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'message' => 'Data telah ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    public function update(IkuProdiBisnisDigitalRequest $request, IkuProdiBisnisDigital $ikuProdiBisnisDigital)
    {
        try {
            $ikuProdiBisnisDigital->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'message' => 'Data telah diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    public function destroy(IkuProdiBisnisDigital $ikuProdiBisnisDigital)
    {
        try {
            $ikuProdiBisnisDigital->delete();
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
