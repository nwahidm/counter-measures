<?php

namespace App\Http\Controllers\Master;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterWilayah;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterWilayahDataTable;

class MasterWilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterWilayahDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.wilayah.index');
    }

    public function create()
    {
        return view('backoffice.master.wilayah.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'data.*.level' => 'required',
            'data.*.kode' => 'required',
            'data.*.nama' => 'required'
        ]);

        if ($request->data == null || empty($request->data)) {
            return redirect()->back()->withInput()->with('error', 'Silahkan lengkapi data');
        }

        DB::beginTransaction();
        try {
            $user = auth()->user()->id;
            foreach ($request->data as $key => $item) {
                MasterWilayah::updateOrCreate(
                [
                    'kode' => $item['kode']
                ],
                [
                    'level' => $item['level'],
                    'nama' => $item['nama']
                ]);
            }

            DB::commit();
            return  redirect()->route('master.wilayah.index')->with("success", "Data berhasil ditambah");
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
        $data = MasterWilayah::findOrFail($id);

        return view('backoffice.master.wilayah.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'level' => 'required',
            'kode' => 'required',
            'nama' => 'required'
        ]);

        $user = auth()->user()->id;
        
        $data = MasterWilayah::findOrFail($id);

        $data->update([
            'level' => $request->level,
            'nama' => $request->nama
        ]);

        return  redirect()->route('master.wilayah.index')->with(["success" => "Data berhasil diupdate"]);
    }

    public function destroy($id, Request $request)
    {
        $user = MasterWilayah::findOrFail($id);
        $user->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }

    public function listProvinsi(Request $request)
    {
        return  response()->json(listProvinsi());
    }

    public function listKota(Request $request)
    {
        return  response()->json(listKota($request->provinsi));
    }

    public function listWilayah(Request $request)
    {
        return  response()->json(listWilayah(['NEGARA','PROVINSI','KABUPATEN/KOTA','KECAMATAN']));
    }
}
