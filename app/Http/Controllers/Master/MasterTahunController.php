<?php

namespace App\Http\Controllers\Master;

use Exception;
use App\Models\MasterTahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterTahunDataTable;

class MasterTahunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterTahunDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.tahun.index');
    }

    public function create()
    {
        return view('backoffice.master.tahun.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tahun.*.kode' => 'required',
            'tahun.*.nama' => 'required'
        ]);

        if ($request->tahun == null || empty($request->tahun)) {
            return redirect()->back()->withInput()->with('error', 'Silahkan lengkapi data');
        }

        DB::beginTransaction();
        try {
            $user = auth()->user()->id;
            foreach ($request->tahun as $key => $item) {
                MasterTahun::updateOrCreate(
                [
                    'kode' => $item['kode']
                ],
                [
                    'nama' => $item['nama'],
                    'is_current' => $item['is_current'] ?? false,
                    'created_by' => $user,
                    'updated_by' => $user
                ]);
            }

            DB::commit();
            return  redirect()->route('master.tahun.index')->with("success", "Data berhasil ditambah");
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
        $data = MasterTahun::findOrFail(urldecode($id));

        return view('backoffice.master.tahun.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kode' => 'required',
            'nama' => 'required'
        ]);

        $user = auth()->user()->id;
        
        $data = MasterTahun::findOrFail(urldecode($id));

        $data->update([
            'nama' => $request->nama,
            'is_current' => $request->is_current ?? false,
            'updated_by' => $user
        ]);

        return  redirect()->route('master.tahun.index')->with(["success" => "Data berhasil diupdate"]);
    }

    public function destroy($id, Request $request)
    {
        $user = MasterTahun::findOrFail(urldecode($id));
        $user->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }
}
