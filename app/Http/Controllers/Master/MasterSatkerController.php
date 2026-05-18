<?php

namespace App\Http\Controllers\Master;

use Exception;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterSatkerDataTable;

class MasterSatkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterSatkerDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.satker.index');
    }

    public function create()
    {
        return view('backoffice.master.satker.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_satker' => 'required',
            'tipe_satker' => 'required',
            'kode_satker' => 'required',
            'provinsi' => 'required',
            'kota' => 'required'
        ]);

        MasterSatker::updateOrCreate([
            'kode_satker' => $request->kode_satker
        ], [
            'nama_satker' => $request->nama_satker,
            'parent_id' => $request->parent_id,
            'tipe_satker' => $request->tipe_satker,
            'alamat_satker' => $request->alamat_satker,
            'provinsi' => $request->provinsi,
            'city' => $request->kota,
            'telp_satker' => $request->telp_satker,
            'website_satker' => $request->website_satker,
            'lat' => preg_replace("/[^0-9.]/", "", $request->lat),
            'long' => preg_replace("/[^0-9.]/", "", $request->long)
        ]);

        return  redirect()->route('master.satker.index')->with("success", "Data berhasil ditambah");
    }

    public function show($id)
    {

    }

    public function edit(Request $request, $id)
    {
        $data = MasterSatker::findOrFail($id);

        return view('backoffice.master.satker.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_satker' => 'required',
            'tipe_satker' => 'required',
            'kode_satker' => 'required',
            'provinsi' => 'required',
            'kota' => 'required'
        ]);

        $user = auth()->user()->id;
        
        $data = MasterSatker::findOrFail($id);

        $data->update([
            'kode_satker' => $request->kode_satker,
            'nama_satker' => $request->nama_satker,
            'parent_id' => $request->parent_id,
            'tipe_satker' => $request->tipe_satker,
            'alamat_satker' => $request->alamat_satker,
            'provinsi' => $request->provinsi,
            'city' => $request->kota,
            'telp_satker' => $request->telp_satker,
            'website_satker' => $request->website_satker,
            'lat' => preg_replace("/[^0-9.]/", "", $request->lat),
            'long' => preg_replace("/[^0-9.]/", "", $request->long)
        ]);

        return  redirect()->route('master.satker.index')->with(["success" => "Data berhasil diupdate"]);
    }

    public function destroy($id, Request $request)
    {
        $user = MasterSatker::findOrFail($id);
        $user->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }
}
