<?php

namespace App\Http\Controllers\Observation;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CloseCase;
use App\Helpers\DataHelper;
use Illuminate\Support\Str;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CaseCloseProgresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseCloseEventHistoricalUpdates;
use App\Models\Observation\ObservDirective;
use App\Models\Observation\ObservCollectInfo;
use App\Models\Observation\ObservThreat;
use App\Models\Observation\ObservConnect;
use App\DataTables\Observation\ObservDirectiveDataTable;

class ObservDirectiveController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ObservDirectiveDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.observation.directive.index', compact('satker', 'users'));
    }
    // API
    public function list(Request $request)
    {
        $user = Auth::user();
        $idSatker = $user->satker->id_satker;

        $data = ObservDirective::when(!$user->hasRole(['superadmin',]), function($q) use ($idSatker) {
                                    $q->where('observation_surat_perintah.satker_id', '=', $idSatker);
                                })
                                ->with('case')
                                ->latest()
                                ->paginate(10);
        return response()->json($data);
    }
    public function individual($id)
    {

        $data = ObservDirective::with('case')
                                ->findOrFail($id);
        return response()->json($data);
    }

    public function create()
    {
        $satker = DataHelper::getSatker();
        $case = DataHelper::getCloseCase();

        return view('backoffice.close.observation.directive.create', compact('satker', 'case'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'case_id' => 'required|string|max:128',
            'surat_perintah_number' => 'required|string|max:128',
            'surat_perintah_perihal' => 'required|string|max:255',
            // 'surat_perintah_date' => 'required|string|max:128',
            // 'surat_perintah_date_started' => 'required|date',
            'upload_sprint' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = new ObservDirective;
        $data->satker_id = $user->satker?->id_satker;

        $data->case_id = $request->case_id;
        $data->surat_perintah_number = $request->surat_perintah_number;
        $data->surat_perintah_perihal = $request->surat_perintah_perihal;
        $data->surat_perintah_date = $request->surat_perintah_date;
        $data->surat_perintah_date_started = $request->surat_perintah_date_started;

        if ($request->hasFile('upload_sprint')) {
            $ext_upload_sprint = $request->file('upload_sprint')->extension();
            $upload_sprint = $request->file('upload_sprint')
                ->storePubliclyAs(
                    'close/observation/directive/upload_sprint',
                    Str::slug('observation directive sprint', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );

            $data->surat_perintah_path = $upload_sprint;
        }

        $data->created_by = $user->id;
        $data->updated_by = $user->id;
        $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->first();
        if ($request->submit_type === 'save') {
            
            $close_case_progress->update([
                'observation_surat_perintah' => "1",
                'status' => $close_case_progress->percentage > 4.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 4.5 ? $close_case_progress->substatus :  'Input Surat Perintah Pengamatan',
                'percentage' => $close_case_progress?->percentage > 4.5 ? $close_case_progress?->percentage : 4.5
            ]);
        }else{
            $close_case_progress->update([
                'observation_surat_perintah' => "1",
                'status' => $close_case_progress->percentage > 4.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 4.5 ? $close_case_progress->substatus :  'Input Surat Perintah Pengamatan',
                'percentage' => 100
            ]);
        }

        if ($data->save()) {
            // update progress historical
            $data_case_close_historical_update = new CaseCloseEventHistoricalUpdates;
            $data_case_close_historical_update->case_id = $data->case_id;
            $data_case_close_historical_update->action = "Penambahan Surat Perintah Pengamatan";

            $data_case_close_historical_update->created_by = $user->id;
            $data_case_close_historical_update->updated_by = $user->id;
            $data_case_close_historical_update->save();

            // update progress
           
            return redirect()->route('close.observation.directive.index')->with("success", "Data berhasil ditambah.");
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal disimpan!');
    }

    public function show(Request $request, $id)
    {
        $data = ObservDirective::find($id);
        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        return view('backoffice.close.observation.directive.show', compact('data'));
    }

    public function edit(Request $request, $id)
    {
        $data = ObservDirective::find($id);
        $users = User::where('id_satker', auth()->user()->id_satker)->get();
        $case = DataHelper::getCloseCase();
        $satker = DataHelper::getSatker();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        return view('backoffice.close.observation.directive.edit', compact(
            'data', 'users', 'case', 'satker'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'case_id' => 'required|string|max:128',
            'surat_perintah_number' => 'required|string|max:128',
            'surat_perintah_perihal' => 'required|string|max:255',
            // 'surat_perintah_date' => 'required|string|max:128',
            // 'surat_perintah_date_started' => 'required|date',
            'surat_perintah_path' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $user = auth()->user();

        $data = ObservDirective::find($id);

        if(!$data){
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }

        $data->case_id = $request->case_id;
        $data->surat_perintah_number = $request->surat_perintah_number;
        $data->surat_perintah_perihal = $request->surat_perintah_perihal;
        $data->surat_perintah_date = $request->surat_perintah_date;
        $data->surat_perintah_date_started = $request->surat_perintah_date_started;

        if ($request->hasFile('surat_perintah_path')) {
            $ext_upload_sprint = $request->file('surat_perintah_path')->extension();
            $upload_sprint = $request->file('surat_perintah_path')
                ->storePubliclyAs(
                    'close/observation/directive/upload_sprint',
                    Str::slug('observation directive sprint', '_') . '_' . Str::random() . '.' . $ext_upload_sprint,
                    'public'
                );

            if($request->temp_surat_perintah_path){
                if (Storage::disk('public')->exists($request->temp_surat_perintah_path)) {
                    Storage::disk('public')->delete($request->temp_surat_perintah_path);
                }
            }
            
            $data->surat_perintah_path = $upload_sprint;
        } else {
            $surat_perintah_path = $request->temp_surat_perintah_path;

            $data->surat_perintah_path = $surat_perintah_path;
        }

        $data->updated_by = $user->id;

        if ($request->submit_type === 'update_and_finish') {
            $close_case_progress = CaseCloseProgresses::where('case_id', $request->case_id)->first();
            $close_case_progress->update([
                'observation_surat_perintah' => "1",
                'status' => $close_case_progress->percentage > 4.5 ? $close_case_progress->status :  'Pengamatan',
                'substatus' => $close_case_progress->percentage > 4.5 ? $close_case_progress->substatus :  'Input Surat Perintah Pengamatan',
                'percentage' => 100
            ]);
        }

        if ($data->update()) {
            return redirect()->route('close.observation.directive.index')->with(["success" => "Data berhasil diupdate."]);
        }

        return redirect()->back()->withInput()->with('error', 'Data gagal diubah!');
    }

    public function destroy($id, Request $request)
    {
        $data = ObservDirective::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        if($data->surat_perintah_path){
            if (Storage::disk('public')->exists($data->surat_perintah_path)) {
                Storage::disk('public')->delete($data->surat_perintah_path);
            }
        }

        $data->delete();
        ObservCollectInfo::where('surat_perintah_id', $id)->delete();
        ObservThreat::where('surat_perintah_id', $id)->delete();
        ObservConnect::where('surat_perintah_id', $id)->delete();

        return redirect()->back()->with(["success" => "Data berhasil dihapus."]);
    }

    public function downloadFile($path)
    {
        return Storage::disk('public')->download(decrypt($path));
    }
}
