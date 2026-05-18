<?php

namespace App\Http\Controllers\Master;

use Exception;
use App\Models\MasterPekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterPekerjaanDataTable;

class MasterPekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterPekerjaanDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.pekerjaan.index');
    }

    public function create()
    {
        return view('backoffice.master.pekerjaan.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'pekerjaan.*.kode' => 'required',
            'pekerjaan.*.nama' => 'required'
        ]);

        if ($request->pekerjaan == null || empty($request->pekerjaan)) {
            return redirect()->back()->withInput()->with('error', 'Silahkan lengkapi data');
        }

        DB::beginTransaction();
        try {
            $user = auth()->user()->id;
            foreach ($request->pekerjaan as $key => $item) {
                MasterPekerjaan::updateOrCreate(
                [
                    'kode' => $item['kode']
                ],
                [
                    'nama' => $item['nama']
                ]);
            }

            DB::commit();
            return  redirect()->route('master.pekerjaan.index')->with("success", "Data berhasil ditambah");
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
        $data = MasterPekerjaan::findOrFail(urldecode($id));

        return view('backoffice.master.pekerjaan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kode' => 'required',
            'nama' => 'required'
        ]);

        $user = auth()->user()->id;
        
        $data = MasterPekerjaan::findOrFail(urldecode($id));

        $data->update([
            'nama' => $request->nama
        ]);

        return  redirect()->route('master.pekerjaan.index')->with(["success" => "Data berhasil diupdate"]);
    }

    public function destroy($id, Request $request)
    {
        $user = MasterPekerjaan::findOrFail(urldecode($id));
        $user->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }
}
