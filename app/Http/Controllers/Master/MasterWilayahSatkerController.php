<?php

namespace App\Http\Controllers\Master;

use Exception;
use Illuminate\Http\Request;
use App\Models\MasterWilayah;
use App\Models\WilayahSatker;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterWilayahSatkerDataTable;

class MasterWilayahSatkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterWilayahSatkerDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.wilayah-satker.index');
    }

    public function create()
    {
        return view('backoffice.master.wilayah-satker.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'satker' => 'required',
            'wilayah.*' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            foreach ($request->wilayah as $key => $wilayah) {
                WilayahSatker::updateOrCreate(
                [
                    'id_satker' => $request->satker,
                    'id_wilayah' => $wilayah
                ],
                [
                    'created_by' => $user->id,
                    'created_by_name' => $user->name
                ]);
            }

            DB::commit();
            return  redirect()->route('master.wilayah-satker.index')->with("success", "Data berhasil ditambah");
        } 
        catch(\Exception $ex)
        {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', $ex->getMessage() . ' ' . $ex->getLine());
        }
    }

    public function show($id)
    {

    }

    public function edit(Request $request, $id)
    {
        $data = WilayahSatker::findOrFail($id);

        return view('backoffice.master.wilayah-satker.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'satker' => 'required',
            'wilayah' => 'required'
        ]);
        
        $data = WilayahSatker::findOrFail($id);

        $data->update([
            'id_satker' => $request->satker,
            'id_wilayah' => $request->wilayah
        ]);

        return  redirect()->route('master.wilayah-satker.index')->with(["success" => "Data berhasil diupdate"]);
    }

    public function destroy($id, Request $request)
    {
        $user = WilayahSatker::findOrFail($id);
        $user->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

    public function listWilayah(Request $request)
    {
        $resp = MasterWilayah::join('wilayah_satker', 'master_wilayah.id_wilayah', '=', 'wilayah_satker.id_wilayah')
                                ->where('id_satker', request()->satker)
                                ->select('master_wilayah.id_wilayah','master_wilayah.nama')
                                ->get();

        return $resp->toJson();
    }
}
