<?php

namespace App\Http\Controllers\Master;

use Exception;
use App\Models\MasterPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterPegawaiDataTable;
use App\Helpers\DataHelper;

class MasterPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterPegawaiDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.pegawai.index');
    }

    public function create()
    {
        $satker = DataHelper::getSatker();

        return view('backoffice.master.pegawai.create', compact('satker'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'pegawai.*.nip' => 'required|string|max:150',
            'pegawai.*.nama' => 'required|string|max:150',
            'pegawai.*.satker' => 'required|numeric'
        ]);

        if ($request->pegawai == null || empty($request->pegawai)) {
            return redirect()->back()->withInput()->with('error', 'Silahkan lengkapi data');
        }

        DB::beginTransaction();
        try {
            foreach ($request->pegawai as $key => $item) {
                MasterPegawai::updateOrCreate(
                    [
                        'nip' => $item['nip'],
                        'nama' => $item['nama'],
                        'id_satker' => $item['satker']
                    ],
                );
            }

            DB::commit();
            return  redirect()->route('master.pegawai.index')->with("success", "Data berhasil ditambah");
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
        $data = MasterPegawai::findOrFail($id);
        $satker = DataHelper::getSatker();

        return view('backoffice.master.pegawai.edit', compact('data', 'satker'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'pegawai.*.nip' => 'required|string|max:150',
            'pegawai.*.nama' => 'required|string|max:150',
            'pegawai.*.satker' => 'required|numeric'
        ]);
        
        $data = MasterPegawai::findOrFail($id);

        $data->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'id_satker' => $request->id_satker
        ]);

        return  redirect()->route('master.pegawai.index')->with(["success" => "Data berhasil diupdate"]);
    }

    public function destroy($id, Request $request)
    {
        $user = MasterPegawai::findOrFail($id);
        $user->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }
}
