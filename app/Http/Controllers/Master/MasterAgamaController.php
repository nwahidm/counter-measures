<?php

namespace App\Http\Controllers\Master;

use Exception;
use App\Models\MasterAgama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterAgamaDataTable;

class MasterAgamaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterAgamaDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.agama.index');
    }

    public function create()
    {
        return view('backoffice.master.agama.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'agama.*.kode' => 'required',
            'agama.*.nama' => 'required'
        ]);

        if ($request->agama == null || empty($request->agama)) {
            return redirect()->back()->withInput()->with('error', 'Silahkan lengkapi data');
        }

        DB::beginTransaction();
        try {
            $user = auth()->user()->id;
            foreach ($request->agama as $key => $item) {
                MasterAgama::updateOrCreate(
                [
                    'kode' => $item['kode']
                ],
                [
                    'nama' => $item['nama']
                ]);
            }

            DB::commit();
            return  redirect()->route('master.agama.index')->with("success", "Data berhasil ditambah");
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
        $data = MasterAgama::findOrFail(urldecode($id));

        return view('backoffice.master.agama.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kode' => 'required',
            'nama' => 'required'
        ]);

        $user = auth()->user()->id;
        
        $data = MasterAgama::findOrFail(urldecode($id));

        $data->update([
            'nama' => $request->nama
        ]);

        return  redirect()->route('master.agama.index')->with(["success" => "Data berhasil diupdate"]);
    }

    public function destroy($id, Request $request)
    {
        $user = MasterAgama::findOrFail(urldecode($id));
        $user->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }
}
