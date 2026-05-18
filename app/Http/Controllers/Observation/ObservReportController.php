<?php

namespace App\Http\Controllers\Observation;

use App\DataTables\Observation\ObservReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\OpenCase;
use App\Models\Observation\ObservLapinsus;
use App\Models\User;
use App\Models\MasterSatker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Mpdf\Mpdf;
use App\Helpers\DataHelper;
use App\Models\CloseCase;
use App\Models\Observation\ObservCollectInfo;
use App\Models\Observation\ObservConnect;
use App\Models\Observation\ObservDirective;
use App\Models\Observation\ObservThreat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class ObservReportController extends Controller
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
    public function index(ObservReportDataTable $dataTable)
    {
        $satker = DataHelper::getSatker();
        $users = User::with('satker', 'satker.wilayah');

        if (!auth()->user()->hasRole(['superadmin', 'admin-kejagung', 'jaksa-penegakkan-hukum'])) {
            $users->where('id_satker', auth()->user()->id_satker);
        }

        $users = $users->get();

        return $dataTable->render('backoffice.close.observation.report.index', compact('satker', 'users'));
    }

    public function downloadFile($id_case)
    {
        $data = CloseCase::find(decrypt($id_case));
        $satker = MasterSatker::findOrFail($data->satker_id);
        $observationDirective = ObservDirective::where('case_id', decrypt($id_case))->get();
        $observationCollectInfo = ObservCollectInfo::where('case_id', decrypt($id_case))->get();
        $observationThreat = ObservThreat::where('case_id', decrypt($id_case))->get();
        $observationConnect = ObservConnect::where('case_id', decrypt($id_case))->get();
        // dd($observationDirective);

        $images = [];

        if ($data->target_photo) {
            $imagePaths = json_decode($data->target_photo);
            foreach ($imagePaths as $imagePath) {
                $images[] =Storage::url('close/case/' . $imagePath);
            }
        }

        

        $mpdf = new Mpdf([
            'orientation' => 'P',
            //'margin_top' => 13,
            //'mode' => 'utf-8',
            'format' => [215, 330]
        ]);

        //$mpdf->SetFont('timesnewroman', '', 12);
        $mpdf->WriteHTML(view("backoffice.close.observation.report.pdf", 
        compact(
            'data', 
            'observationDirective',
             'observationCollectInfo', 
             'observationThreat', 
             'observationConnect',
             'satker',
            'images')));

        $filename = 'Open_Observation_Report-' . Date::now('Asia/Jakarta')->timestamp . '.pdf';
        $mpdf->Output($filename, 'I');
    }
}
