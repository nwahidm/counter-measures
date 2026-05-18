<?php

namespace App\Http\Controllers\Master;

use Exception;
use App\Models\MasterPendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterPendidikanDataTable;

class MasterPendidikanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterPendidikanDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.pendidikan.index');
    }

    public function create()
    {
        return view('backoffice.master.pendidikan.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'pendidikan.*.kode' => 'required',
            'pendidikan.*.nama' => 'required'
        ]);

        if ($request->pendidikan == null || empty($request->pendidikan)) {
            return redirect()->back()->withInput()->with('error', 'Silahkan lengkapi data');
        }

        DB::beginTransaction();
        try {
            $user = auth()->user()->id;
            foreach ($request->pendidikan as $key => $item) {
                MasterPendidikan::updateOrCreate(
                [
                    'kode' => $item['kode']
                ],
                [
                    'nama' => $item['nama']
                ]);
            }

            DB::commit();
            return  redirect()->route('master.pendidikan.index')->with("success", "Data berhasil ditambah");
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
        $data = MasterPendidikan::findOrFail(urldecode($id));

        return view('backoffice.master.pendidikan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kode' => 'required',
            'nama' => 'required'
        ]);

        $user = auth()->user()->id;
        
        $data = MasterPendidikan::findOrFail(urldecode($id));

        $data->update([
            'nama' => $request->nama
        ]);

        return  redirect()->route('master.pendidikan.index')->with(["success" => "Data berhasil diupdate"]);
    }

    public function destroy($id, Request $request)
    {
        $user = MasterPendidikan::findOrFail(urldecode($id));
        $user->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }
}
