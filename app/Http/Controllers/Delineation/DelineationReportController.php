<?php

namespace App\Http\Controllers\Delineation;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Delineation\DelineationReportDataTable;
use App\Helpers\DataHelper;
use App\Models\User;
use App\Models\MasterSatker;
use App\Models\Observation\ObservCollectInfo;
use App\Models\CloseCase;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Date;
use App\Models\Delineation\DelineationInformationVerification;
use App\Models\Delineation\DelineationInformationValidation;
use App\Models\Delineation\DelineationScenarioRelation;
use App\Models\CaseCloseProgresses;
use App\Models\CaseCloseEventHistoricalUpdates;


class DelineationReportController extends Controller
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
    public function index(DelineationReportDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.delineation.report.index', compact('satker', 'users'));
    }

    public function show(Request $request, $id)
    {
        
        $data = CloseCase::findOrFail($id);
        
        $satker = MasterSatker::findOrFail($data->satker_id);
        $observation_information_collection = ObservCollectInfo::where('case_id', $data->id)->firstOrFail();
        $delineation_information_verification = DelineationInformationVerification::where('case_id', $data->id)->firstOrFail();
        $delineation_information_validation = DelineationInformationValidation::where('case_id', $data->id)->firstOrFail();
        $delineation_scenario_relation = DelineationScenarioRelation::where('case_id', $data->id)->firstOrFail();
        
        return view('backoffice.close.delineation.report.show', compact(
         'satker', 'data', 'observation_information_collection',
        'delineation_information_verification', 'delineation_information_validation', 
        'delineation_scenario_relation'));
    }
  
    public function downloadFile($id)
    {
        $delineation_information_verification_datas = DelineationInformationVerification::where('case_id', $id)->get();
        $delineation_information_validation_datas = DelineationInformationValidation::where('case_id', $id)->get();
        $delineation_scenario_relation_datas = DelineationScenarioRelation::where('case_id', $id)->get();
       

        // $satker = MasterSatker::findOrFail($delineation_scenario_relation_datas->satker_id);
        $case = CloseCase::findOrFail($id);
        $case->target_photo = json_decode($case->target_photo, true);

        $satker = MasterSatker::findOrFail($case->satker_id);
        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.close.delineation.report.pdf", 
        compact(
            'delineation_information_verification_datas',
            'delineation_information_validation_datas', 
            'delineation_scenario_relation_datas',
            'satker', 
            'case'
        )));

        $filename = 'Delineation_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }

}
