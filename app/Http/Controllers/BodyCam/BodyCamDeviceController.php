<?php

namespace App\Http\Controllers\BodyCam;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\BodyCamDevice\BodyCamDeviceDataTable;
use App\Helpers\DataHelper;
use App\Models\MasterSatker;
use App\Models\BodyCamDevice\BodyCamDevice;

class BodyCamDeviceController extends Controller
{

    public function index(BodyCamDeviceDataTable $dataTable)
    {
        
        return $dataTable->render('backoffice.bodycam.index');
    }

    public function create()
    {
        $satker = DataHelper::getSatker();

        return view('backoffice.bodycam.create', 
        compact('satker',));
    }

    public function edit(Request $request, $id)
    {
        $data = BodyCamDevice::find($id);
       
        $satker = DataHelper::getSatker();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.bodycam.edit', 
        compact('data','satker' ));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'device_name' => 'required|string|max:1000000',
            'id_satker' => 'required|string|max:1000000',
            'device_source_url' => 'required|string|max:1000000',
        ]);
        $user = auth()->user();
        $satker = MasterSatker::findOrFail($request->id_satker);
        


        $data = BodyCamDevice::find($id);
        $data->device_name = $request->device_name;
        $data->device_source_url = $request->device_source_url;
        $data->device_dahua_id = $request->device_dahua_id;

        $data->device_used_for = $request->id_satker;
        
        $data->updated_by = $user->id;

        if ($data->update()) {
            return redirect()->route('bodycam.body-cam.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'device_name' => 'required|string|max:1000000',
            'id_satker' => 'required|string|max:1000000',
            'device_source_url' => 'required|string|max:1000000',
        ]);
        $user = auth()->user();
        $satker = MasterSatker::findOrFail($request->id_satker);
        

        $data = new BodyCamDevice;
        $data->device_name = $request->device_name;
        $data->device_source_url = $request->device_source_url;
        $data->device_dahua_id = $request->device_dahua_id;

        $data->device_used_for = $request->id_satker;


        $data->created_by = $user->id;
        $data->updated_by = $user->id;

       
        if ($data->save()) {
            return redirect()->route('bodycam.body-cam.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function location(Request $request)
    {
        $user = auth()->user();
        $satker = MasterSatker::findOrFail($user->id_satker);
        $data = BodyCamDevice::when(!$user->hasRole(['superadmin']), function($q) use ($user) {
            $q->where('bodycam_devices.device_used_for', '=', $user->id_satker);
        })->get();
        
        $location = DataHelper::getLocation('all');
        
        $locationCollection = collect($location);  // Convert the array to a collection

        foreach ($data as $value) {
            $exists = $locationCollection->firstWhere('deviceCode', $value->device_dahua_id);
            
            if ($exists) {
                $value->long = $exists['gpsX'];
                $value->lat = $exists['gpsY'];
            } 
        }

        return view('backoffice.bodycam.location', compact(
            'data', 'satker'));
    }

    public function show(Request $request, $id)
    {
        $data = BodyCamDevice::find($id);
        $satker = MasterSatker::where('kode_satker', $data->device_used_for)->first();
        $location = DataHelper::getLocation($data->device_dahua_id);
        $data->lat = $location["lat"];
        $data->long = $location["long"];
        


        return view('backoffice.bodycam.show', compact(
            'data', 'satker'));
    }

    public function destroy($id, Request $request)
    {
        // dd($id);
        $data = BodyCamDevice::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $data->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }


}
