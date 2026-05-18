<?php

namespace App\Http\Controllers\Master;

use Exception;
use App\Models\MasterPartai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterPartaiDataTable;

class MasterPartaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterPartaiDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.partai.index');
    }

    public function create()
    {
        return view('backoffice.master.partai.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'partai.*.nama' => 'required',
            'partai.*.tanggal_berdiri' => 'required',
            'partai.*.ketua_umum' => 'required'
        ]);

        if ($request->partai == null || empty($request->partai)) {
            return redirect()->back()->withInput()->with('error', 'Silahkan lengkapi data');
        }

        DB::beginTransaction();
        try {
            $user = auth()->user()->id;
            foreach ($request->partai as $key => $item) {
                MasterPartai::create(
                [
                    'nama' => $item['nama'],
                    'tanggal_berdiri' => $item['tanggal_berdiri'],
                    'ketua_umum' => $item['ketua_umum'],
                    'created_by' => $user,
                    'updated_by' => $user
                ]);
            }

            DB::commit();
            return  redirect()->route('master.partai.index')->with("success", "Data berhasil ditambah");
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
        $data = MasterPartai::findOrFail($id);

        return view('backoffice.master.partai.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required',
            'tanggal_berdiri' => 'required',
            'ketua_umum' => 'required'
        ]);

        $user = auth()->user()->id;
        
        $data = MasterPartai::findOrFail($id);

        $data->update([
            'nama' => $request->nama,
            'tanggal_berdiri' => $request->tanggal_berdiri,
            'ketua_umum' => $request->ketua_umum,
            'updated_by' => $user
        ]);

        return  redirect()->route('master.partai.index')->with(["success" => "Data berhasil diupdate"]);
    }

    public function destroy($id, Request $request)
    {
        $user = MasterPartai::findOrFail($id);
        $user->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus"]);
    }
}
