<?php

namespace App\Http\Controllers\Master;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterJenisPemilihan;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterJenisPemilihanDataTable;

class MasterJenisPemilihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterJenisPemilihanDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.jenis-pemilihan.index');
    }

    public function create()
    {
        return view('backoffice.master.jenis-pemilihan.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'data.*.kategori' => 'required',
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
                MasterJenisPemilihan::updateOrCreate(
                [
                    'kode' => $item['kode'],
                    'kategori' => $item['kategori']
                ],
                [
                    'nama' => $item['nama'],
                    'deskripsi' => $item['nama']
                ]);
            }

            DB::commit();
            return  redirect()->route('master.jenis-pemilihan.index')->with("success", "Data berhasil ditambah");
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
        $data = MasterJenisPemilihan::findOrFail(urldecode($id));

        return view('backoffice.master.jenis-pemilihan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kategori' => 'required',
            'kode' => 'required',
            'nama' => 'required'
        ]);

        $user = auth()->user()->id;
        
        $data = MasterJenisPemilihan::findOrFail(urldecode($id));

        $data->update([
            'kategori' => $request->kategori,
            'nama' => $request->nama
        ]);

        return  redirect()->route('master.jenis-pemilihan.index')->with(["success" => "Data berhasil diupdate"]);
    }

    public function destroy($id, Request $request)
    {
        $user = MasterJenisPemilihan::findOrFail(urldecode($id));
        $user->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }
}
