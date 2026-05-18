<?php

use App\Helpers\DataHelper;
use App\Helpers\ResearchDataHelper;
use App\Helpers\TailingDataHelper;
use App\Helpers\InterviewDataHelper;
use Illuminate\Support\Facades\Route;

use App\Helpers\DelineationDataHelper;

use App\Helpers\InfiltrationDataHelper;
use App\Helpers\BodycamDeviceDataHelper;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OpenCaseController;
use App\Http\Controllers\CloseCaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Rbac\RoleController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\Rbac\PermissionController;
use App\Http\Controllers\ElicitationAdFollController;
use App\Http\Controllers\ElicitationReportController;
use App\Http\Controllers\ElicitationResultController;
use App\Http\Controllers\InterogationRecordController;
use App\Http\Controllers\InterogationReportController;
use App\Http\Controllers\Master\MasterAgamaController;
use App\Http\Controllers\Master\MasterTahunController;
use App\Http\Controllers\Master\MasterPartaiController;
use App\Http\Controllers\Master\MasterSatkerController;
use App\Http\Controllers\Rbac\RolePermissionController;
use App\Http\Controllers\ElicitationInterviewController;
use App\Http\Controllers\Master\MasterPegawaiController;
use App\Http\Controllers\Master\MasterPerkaraController;
use App\Http\Controllers\Master\MasterWilayahController;
use App\Http\Controllers\BodyCam\BodyCamDeviceController;
use App\Http\Controllers\Tailing\TailingReportController;
use App\Http\Controllers\Tapping\TappingReportController;
use App\DataTables\Elicitation\ElicitationAdFollDataTable;
use App\Http\Controllers\ExplorationRencanaAksiController;
use App\Http\Controllers\Master\MasterPekerjaanController;
use App\Http\Controllers\Master\MasterPendidikanController;
use App\Http\Controllers\Research\ResearchReportController;

use App\Http\Controllers\Research\ResearchSprintController;
use App\Http\Controllers\Interview\InterviewHasilController;
use App\Http\Controllers\Observation\ObservReportController;


use App\Http\Controllers\Observation\ObservThreatController;
use App\Http\Controllers\Research\ResearchSaranTLController;
use App\Http\Controllers\ExplorationTargetIdentityController;
use App\Http\Controllers\Interview\InterviewJadwalController;
use App\Http\Controllers\Interview\InterviewReportController;
use App\Http\Controllers\Intrusion\IntrusionReportController;


use App\Http\Controllers\Intrusion\IntrusionResultController;

use App\Http\Controllers\Observation\ObservConnectController;
use App\Http\Controllers\Research\ResearchLapinsusController;
use App\Http\Controllers\Interview\InterviewSaranTLController;
use App\Http\Controllers\Master\MasterWilayahSatkerController;
use App\Http\Controllers\CommandCenter\CommandCenterController;
use App\Http\Controllers\ExplorationResultAchievmentController;

use App\Http\Controllers\Master\MasterJenisPemilihanController;
use App\Http\Controllers\Observation\ObservDirectiveController;
use App\Http\Controllers\Intrusion\IntrusionTargetEnvController;
use App\Http\Controllers\Intrusion\IntrusionTargetLocController;


use App\Http\Controllers\Research\ResearchPotensiAghtController;
use App\Http\Controllers\Tailing\TailingTargetOperasiController;
use App\Http\Controllers\Delineation\DelineationReportController;
use App\Http\Controllers\InterogationResultAchievementController;
use App\Http\Controllers\Observation\ObservCollectInfoController;
use App\Http\Controllers\OpenSingleForm\OpenSingleFormController;
use App\Http\Controllers\CloseSingleForm\CloseSingleFormController;

use App\Http\Controllers\Infiltration\InfiltrationReportController;
use App\Http\Controllers\Tapping\TappingElectronicDeviceController;
use App\Http\Controllers\InterogationTargetIdentificationController;
use App\Http\Controllers\Tailing\TailingPemahamanPerilakuController;
use App\Http\Controllers\Tailing\TailingResultAchievementController;
use App\Http\Controllers\Tapping\TappingIntelligentSignalController;
use App\Http\Controllers\Tapping\TappingResultAchievementController;
use App\Http\Controllers\Delineation\DelineationScenarioRelationController;
use App\Http\Controllers\Infiltration\InfiltrationTargetDynamicsController;

use App\Http\Controllers\Infiltration\InfiltrationSecretOperationController;
use App\Http\Controllers\Infiltration\InfiltrationResultAchievementController;
use App\Http\Controllers\Delineation\DelineationInformationValidationController;
use App\Http\Controllers\Delineation\DelineationInformationVerificationController;
use App\Http\Controllers\FfmpegController;
/*

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('loginpost', [CustomAuthController::class, 'login'])->name('customauth.loginpost');
Route::get('/anjay', function () {
    // $token = DataHelper::getStreamToken('1000012');
    $bodycam_devices = BodycamDeviceDataHelper::getBodycamDevicebyUser();
    // return view('backoffice.bodycam.videos2', compact('token','bodycam_devices'));
    return view('backoffice.bodycam.videos2', compact('bodycam_devices'));
})->name('player');


Route::post('/start-recording', [FfmpegController::class, 'startRecording'])->name('start-recording');
Route::post('/stop-recording', [FfmpegController::class, 'stopRecording'])->name('stop-recording');


Route::group([], function () {
    Route::group(['middleware' => 'auth:web'], function () {
        // logout
        Route::post('/logout', [CustomAuthController::class, 'logout'])->name('logout');

        Route::get('/', function () {
            return redirect(route('dashboard'));
        });
        Route::get('/pegawai/{nip}', [OpenCaseController::class, 'getPegawaiByNIP']);
        // ckeditor upload
        Route::post('ckeditor/upload', [DataHelper::class, 'uploadCkeditor'])->name('ckeditor.upload');

        // dashboard
        Route::get('/dashboard', [DashboardController::class, 'home'])->name('dashboard');
        
        Route::post('/upload-video', [InfiltrationSecretOperationController::class, 'uploadVideo'])->name('upload.video');
        // open case dashboard
        Route::get('/open/dashboard', [DashboardController::class, 'open'])->name('open-dashboard');
        Route::get('/open/last', [DashboardController::class, 'getLastOpen'])->name('open-getLastOpen');
        Route::get('/open/earlier', [DashboardController::class, 'getEarlierOpen'])->name('open-getEarlierOpen');
        // close case dashboard
        Route::get('/close/dashboard', [DashboardController::class, 'close'])->name('close-dashboard');
        Route::get('/close/last', [DashboardController::class, 'getLastClose'])->name('close-getLastClose');
        Route::get('/close/earlier', [DashboardController::class, 'getEarlierClose'])->name('close-getEarlierClose');

        Route::view('/profile', 'backoffice.profile')->name('profile');

        
        /* Manage Role */
        Route::group(['prefix' => 'manage-role', 'as' => 'manage-role.'], function () {
            Route::group(['middleware' => ['permission:read-role|modify-role|approve-role']], function () {
                Route::resource('role', RoleController::class);
                Route::resource('permission', PermissionController::class);
                Route::put('/rolepermission/permission/{role}', [RolePermissionController::class, 'setRolePermission'])->name('rolepermission.setRolePermission');
                Route::resource('rolepermission', RolePermissionController::class);
            });
        });

        /* Manage User */
        Route::group(['prefix' => 'manage-user'], function () {
            Route::group(['middleware' => ['permission:read-users|modify-users|approve-users']], function () {
                Route::resource('user', UserController::class);
            });
            Route::get('profile/{id}', [UserController::class, 'editProfile'])->name('edit.profile');
            Route::put('profile/{id}/update', [UserController::class, 'update'])->name('update.profile');
        });

        Route::get('/commandcenter', [CommandCenterController::class, 'index'])->name('commandcenter');
        Route::get('/commandcenter/report', [CommandCenterController::class, 'downloadReport'])->name('commandcenterpdf');
        // Route::get('/ptz/control/{action}', 'CommandCenterController@control');
        Route::get('/start-stream', [CommandCenterController::class, 'startStream']);
        Route::get('/stop-stream', [CommandCenterController::class, 'stopStream']);
        Route::post('/control-camera', [CommandCenterController::class, 'controlCamera']);

        Route::group(['prefix' => 'bodycam', 'as' => 'bodycam.'], function () {
            Route::resource('body-cam', BodyCamDeviceController::class)
                ->except(['downloadFile', 'detail', 'location']);
            Route::get('body-cam/download/{path}', [BodyCamDeviceController::class, 'downloadFile'])->name('doc-analytic.download-file');
            Route::post('body-cam/detail', [BodyCamDeviceController::class, 'detail'])->name('doc-analytic.detail');
            Route::get('body-cam-location', [BodyCamDeviceController::class, 'location'])->name('body-cam-location');
        });

        

        // HELPER ROUTE
        Route::get('/helper-research-sprint', [ResearchDataHelper::class, 'getSuratPerintahByCase'])->name('helper-research-sprint');
        Route::get('/helper-research-lapinsus', [ResearchDataHelper::class, 'getLapinsusBySuratPerintah'])->name('helper-research-lapinsus');
        Route::get('/helper-research-saran', [ResearchDataHelper::class, 'getSaranTinjutByLapinsus'])->name('helper-research-saran');

        Route::get('/helper-tapping-device', [DataHelper::class, 'getTappingElectronicDeviceByCase'])->name('helper-tapping-device');
        Route::get('/helper-tapping-signal', [DataHelper::class, 'getTappingIntelligentSignalByDevice'])->name('helper-tapping-signal');
        
        // HELPER OPEN CASE 
        Route::get('/helper-open-case', [DataHelper::class, 'getCase'])->name('helper-open-case');

        Route::get('/helper-interview-jadwal', [InterviewDataHelper::class, 'getInterviewScheduleByCase'])->name('helper-interview-jadwal');
        Route::get('/helper-interview-hasil', [DataHelper::class, 'getInterviewHasilByJadwal'])->name('helper-interview-hasil');

        /* Manage Master Umum*/
        Route::group(['prefix' => 'master', 'as' => 'master.'], function () {

            Route::group(['middleware' => ['permission:master-jenis-pemilihan']], function () {
                Route::resource('jenis-pemilihan', MasterJenisPemilihanController::class);
            });

            Route::group(['middleware' => ['permission:master-satker']], function () {
                Route::resource('satker', MasterSatkerController::class);
            });

            Route::group(['middleware' => ['permission:master-wilayah']], function () {
                Route::get('/wilayah/list-provinsi', [MasterWilayahController::class, 'listProvinsi'])->name('wilayah.listProvinsi');
                Route::get('/wilayah/list-kota', [MasterWilayahController::class, 'listKota'])->name('wilayah.listKota');
                Route::get('/wilayah/list-wilayah', [MasterWilayahController::class, 'listWilayah'])->name('wilayah.listWilayah');
                Route::resource('wilayah', MasterWilayahController::class);
            });

            Route::group(['middleware' => ['permission:master-wilayah-satker']], function () {
                Route::get('/wilayah-satker/list-wilayah', [MasterWilayahSatkerController::class, 'listWilayah'])->name('wilayah-satker.listWilayah');
                Route::resource('wilayah-satker', MasterWilayahSatkerController::class);
            });

            Route::group(['middleware' => ['permission:master-agama']], function () {
                Route::resource('agama', MasterAgamaController::class);
            });

            Route::group(['middleware' => ['permission:master-pegawai']], function () {
                Route::resource('pegawai', MasterPegawaiController::class);
            });

            Route::group(['middleware' => ['permission:master-pekerjaan']], function () {
                Route::resource('pekerjaan', MasterPekerjaanController::class);
            });

            Route::group(['middleware' => ['permission:master-pendidikan']], function () {
                Route::resource('pendidikan', MasterPendidikanController::class);
            });

            Route::group(['middleware' => ['permission:master-perkara']], function () {
                Route::resource('perkara', MasterPerkaraController::class);
            });

            Route::group(['middleware' => ['permission:master-tahun']], function () {
                Route::resource('tahun', MasterTahunController::class);
            });

            Route::group(['middleware' => ['permission:master-partai']], function () {
                Route::resource('partai', MasterPartaiController::class);
            });
        });

        // Open case
        Route::group(['prefix' => 'open', 'as' => 'open.'], function () {
            // open helper
            Route::get('/helper-case', [DataHelper::class, 'getOpenCase'])->name('open.helper-case');

            // Route::group(['middleware' => ['permission:read-users|modify-users|approve-users']], function () {
            Route::resource('case', OpenCaseController::class);
            Route::get('kependudukan/ceknik/{nik}', [OpenCaseController::class, 'checkNik'])->name('kependudukan.ceknik');
            // });
            Route::get('summary', [OpenCaseController::class, 'summary'])->name('summary');
            Route::get('summary/detail/{id}', [OpenCaseController::class, 'detailall'])->name('summary.detail');

            // Single Form
            Route::group(['prefix' => 'singleform', 'as' => 'singleform.'], function () {
                Route::resource('single-form', OpenSingleFormController::class)
                    ->middleware(['permission:read-interrog-record|modify-interrog-record|approve-interrog-record'])
                    ->except(['downloadFile']);
                    Route::get('single-form/download/{path}', [OpenSingleFormController::class, 'downloadFile'])->name('single-form.download-file');
                    Route::post('single-form/upload-video', [OpenSingleFormController::class, 'uploadVideo1'])->name('single-form.upload.video');
                
            });

            // Research
            Route::group(['prefix' => 'research', 'as' => 'research.'], function () {
                // Warrant
                Route::resource('warrant', ResearchSprintController::class)
                    ->middleware(['permission:read-research-sprint|modify-research-sprint|approve-research-sprint'])
                    ->except(['downloadFile', 'detail']);
                Route::get('warrant/download/{path}', [ResearchSprintController::class, 'downloadFile'])->name('warrant.download-file');
                Route::post('warrant/detail', [ResearchSprintController::class, 'detail'])->name('warrant.detail');

                // Spesific Intel Report
                Route::resource('spesific-intel-report', ResearchLapinsusController::class)
                    ->middleware(['permission:read-research-lapinsus|modify-research-lapinsus|approve-research-lapinsus'])
                    ->except(['downloadFile', 'detail']);
                Route::get('spesific-intel-report/download/{path}', [ResearchLapinsusController::class, 'downloadFile'])->name('spesific-intel-report.download-file');
                Route::get('spesific-intel-report-upload', [ResearchLapinsusController::class, 'upload'])->name('spesific-intel-report.upload');
                Route::post('spesific-intel-report-store-upload', [ResearchLapinsusController::class, 'storeUpload'])->name('spesific-intel-report.store-upload');
                Route::post('spesific-intel-report-update-upload/{id}', [ResearchLapinsusController::class, 'updateUpload'])->name('spesific-intel-report.update-upload');
                Route::post('spesific-intel-report/detail', [ResearchLapinsusController::class, 'detail'])->name('spesific-intel-report.detail');

                // Advice and Follow-up Measure
                Route::resource('advice-measure', ResearchSaranTLController::class)
                    ->middleware(['permission:read-research-saran-tl|modify-research-saran-tl|approve-research-saran-tl'])
                    ->except(['downloadFile', 'detail']);
                Route::get('advice-measure/download/{path}', [ResearchSaranTLController::class, 'downloadFile'])->name('advice-measure.download-file');
                Route::post('advice-measure/detail', [ResearchSaranTLController::class, 'detail'])->name('advice-measure.detail');
                Route::get('advice-measure-lapinsus/{id}', [ResearchSaranTLController::class, 'createFromLapinsus'])->name('saran_tl.createfromlapinsus');

                // Threats, Interference, Barrier, Challenges (TIBC)
                Route::resource('tibc', ResearchPotensiAghtController::class)
                    ->middleware(['permission:read-research-potensi-aght|modify-research-potensi-aght|approve-research-potensi-aght'])
                    ->except(['downloadFile', 'detail']);
                Route::get('tibc/download/{path}', [ResearchPotensiAghtController::class, 'downloadFile'])->name('tibc.download-file');
                Route::post('tibc/detail', [ResearchPotensiAghtController::class, 'detail'])->name('tibc.detail');

                // Report
                Route::resource('report', ResearchReportController::class)
                    ->middleware(['permission:read-research-report|modify-research-report|approve-research-report'])
                    ->except(['downloadFile']);
                Route::get('report/download/{path}', [ResearchReportController::class, 'downloadFile'])->name('report.download-file');
                Route::get('report/download/lapinsus/{path}', [ResearchReportController::class, 'downloadFilelapinsusus'])->name('report.download-lapinsus');
            });

            // Interview
            Route::group(['prefix' => 'interview', 'as' => 'interview.'], function () {
                // Jadwal
                Route::resource('jadwal', InterviewJadwalController::class)
                    ->middleware(['permission:read-interview-jadwal|modify-interview-jadwal|approve-interview-jadwal'])
                    ->except(['downloadFile', 'detail']);
                Route::get('jadwal/download/{path}', [InterviewJadwalController::class, 'downloadFile'])->name('jadwal.download-file');

                // Hasil
                Route::resource('hasil', InterviewHasilController::class)
                    ->middleware(['permission:read-interview-hasil|modify-interview-hasil|approve-interview-hasil'])
                    ->except(['downloadDokumen', 'downloadVideo','downloadAudiotoTextFile']);
                Route::get('hasil/download-dokumen-wawancara/{path}', [InterviewHasilController::class, 'downloadDokumen'])->name('hasil.download-dokumen-wawancara');
                Route::get('hasil/download-video-wawancara/{path}', [InterviewHasilController::class, 'downloadVideo'])->name('hasil.download-video-wawancara');
                Route::post('hasil/upload-video', [InterviewHasilController::class, 'uploadVideo'])->name('hasil.upload.video');
                Route::get('hasil/download-interview-video-audio/{path}', [InterviewHasilController::class, 'downloadAudiotoTextFile'])->name('hasil.download-interview-audio-to-text-file');
        

                // Saran Tindak Lanjut
                Route::resource('saran_tl', InterviewSaranTLController::class)
                    ->middleware(['permission:read-interview-saran-tl|modify-interview-saran-tl|approve-interview-saran-tl']);
                
                Route::get('saran_tl/tinjut-interview/{id}', [InterviewSaranTLController::class, 'createFromInterview'])->name('saran_tl.createfrominterview');
        
                // Report
                Route::resource('report', InterviewReportController::class)
                    ->middleware(['permission:read-interview-report|modify-interview-report|approve-interview-report'])
                    ->except(['downloadFile', 'downloadVideo', 'downloadReport']);
                Route::get('report/download-report/{path}', [InterviewReportController::class, 'downloadReport'])->name('report.download-report');
                Route::get('report/download-wawancara/{path}', [InterviewReportController::class, 'downloadReportwawancara'])->name('report.download-wawancara');
                Route::get('report/download-wawancara/word/{path}', [InterviewReportController::class, 'downloadReportwawancaraword'])->name('report.download-wawancara-word');
                Route::get('report/download-file/{path}', [InterviewReportController::class, 'downloadFile'])->name('report.download-file');
            
               
            });

			// Interogation Record
            Route::group(['prefix' => 'data', 'as' => 'data.'], function () {
                Route::resource('interrog-record', InterogationRecordController::class)
                    ->middleware(['permission:read-interrog-record|modify-interrog-record|approve-interrog-record'])
                    ->except(['downloadFile']);
                    Route::get('interrog-record/download/{path}', [InterogationTargetIdentificationController::class, 'downloadFile'])->name('interrog-record.download-file');

                Route::get('/interrog/{case_id}', [DataHelper::class, 'getInterogrecordByCase'])->name('getInterragtionRecord');
                Route::get('/interrog/target/{interog_id}', [DataHelper::class, 'getInterogTargetByRecord'])->name('getTergetIdentity');
                Route::get('interrog/download-bap/{path}', [InterogationRecordController::class, 'downloadBap'])->name('record.download-bap');

                Route::get('interrog-record-report-upload', [InterogationRecordController::class, 'upload'])->name('interrog-record-report.upload');
                Route::post('interrog-record-report-store-upload', [InterogationRecordController::class, 'storeUpload'])->name('interrog-record-report.store-upload');
                Route::post('interrog-record-report-update-upload/{id}', [InterogationRecordController::class, 'updateUpload'])->name('interrog-record-report.update-upload');
                Route::post('interrog-record-report/detail', [InterogationRecordController::class, 'detail'])->name('interrog-intel-record.detail');


            });

			// Interogation Target Identification
            Route::group(['prefix' => 'data', 'as' => 'data.'], function () {
                Route::resource('interogg-target-id', InterogationTargetIdentificationController::class)
                    ->middleware(['permission:read-interogg-target-id|modify-interogg-target-id|approve-interogg-target-id'])
                    ->except(['downloadFile']);
                Route::get('interrog-target-id/download/{path}', [InterogationTargetIdentificationController::class, 'downloadFile'])->name('interrog-target-id.download-file');
                 Route::post('interogg-achieve/get-hasil-identifikasi-target', [InterogationResultAchievementController::class, 'getHasilIdentifikasiTarget'])->name('get-hasil-identifikasi-target');
                 Route::get('interrog-target-id/download-interview-video-audio/{path}', [InterogationTargetIdentificationController::class, 'downloadAudiotoTextFile'])->name('interrog-target-id.download-interview-audio-to-text-file');
                 Route::post('interrog-target-id/upload-video', [InterogationTargetIdentificationController::class, 'uploadVideo'])->name('interrog-target-id.upload.video');
                
            });

			// Interogation Result Achievement
            Route::group(['prefix' => 'data', 'as' => 'data.'], function () {
                Route::resource('interogg-achieve', InterogationResultAchievementController::class)
                    ->middleware(['permission:read-interogg-achieve|modify-interogg-achieve|approve-interogg-achieve'])
                    ->except(['downloadFile']);
                    Route::get('interogg-achieve/download/{path}', [InterogationResultAchievementController::class, 'downloadFile'])->name('interogg-achieve.download-file');
                    Route::post('interogg-achieve/get-hasil-identifikasi-target', [InterogationResultAchievementController::class, 'getHasilIdentifikasiTarget'])->name('get-hasil-identifikasi-target');
            });

            // Interogation Report
            Route::group(['prefix' => 'interogation', 'as' => 'interogation.'], function () {
                Route::resource('report', InterogationReportController::class)
                ->middleware(['permission:read-interogg-report|modify-interogg-report|approve-interogg-report'])
                ->except(['downloadFile']);
                Route::get('report/download/{path}', [InterogationReportController::class, 'downloadFile'])->name('report.download-file');
            });

			// Elicitation Interview Result
            Route::group(['prefix' => 'data', 'as' => 'data.'], function () {
                Route::resource('elicit-interview', ElicitationInterviewController::class)
                    ->middleware(['permission:read-elicit-interview|modify-elicit-interview|approve-elicit-interview'])
                    ->except(['downloadFile']);
                Route::get('elicit-interview/download/{path}', [ElicitationInterviewController::class, 'downloadFile'])->name('elicit-interview.download-file');

                Route::get('/elicit/{case_id}', [ElicitationInterviewController::class, 'getElicitationRecord'])->name('getElicitationRecord');
                Route::get('/elicit/adfl/{elicit_id}', [ElicitationInterviewController::class, 'getAdFl'])->name('getAdFl');
                
                Route::post('/elicit/upload-video', [ElicitationInterviewController::class, 'uploadVideo'])->name('elicit-interview.upload.video');
               
                Route::get('/elicit/download-elicitation-interview-video-audio/{path}', [ElicitationInterviewController::class, 'downloadAudiotoTextFile'])->name('elicit-interview.download-elicitation-interview-audio-to-text-file');
        
            });

			// Elicitation Advice and Follow Up
            Route::group(['prefix' => 'data', 'as' => 'data.'], function () {
                Route::resource('elicit-adfoll', ElicitationAdFollController::class)
                    ->middleware(['permission:read-elicit-adfoll|modify-elicit-adfoll|approve-elicit-adfoll']);
            });

			// Elicitation Result
            Route::group(['prefix' => 'data', 'as' => 'data.'], function () {
                Route::resource('elicit-result', ElicitationResultController::class)
                    ->middleware(['permission:read-elicit-result|modify-elicit-result|approve-elicit-result'])
                    ->except(['downloadFile']);
                Route::get('elicit-result/download/{path}', [ElicitationResultController::class, 'downloadFile'])->name('elicit-result.download-file');
            });

            // Elicitation Report
            Route::group(['prefix' => 'elicitation', 'as' => 'elicitation.'], function () {
                Route::resource('report', ElicitationReportController::class)
                    ->middleware(['permission:read-elicit-report|modify-elicit-report|approve-elicit-report'])
                    ->except(['downloadFile']);
                Route::get('report/download/{path}', [ElicitationReportController::class, 'downloadFile'])->name('report.download-file');

                Route::get('report/download/laporanhasilpelaksanaantugas/{path}', [ElicitationReportController::class, 'downloadHasilPelaksanaanTugas'])->name('report.download-hasil-pelaksanaan-tugas');
            });
        });

        Route::post('/command-center/camera-control', [CommandCenterController::class, 'cameraControl']);
        Route::post('/command-center/camera-zoom-control', [CommandCenterController::class, 'cameraZoomControl']);
        Route::post('/command-center/camera-start-record', [CommandCenterController::class, 'startRecord']);
        Route::post('/command-center/camera-stop-record', [CommandCenterController::class, 'stopRecord']);          
        Route::get('/command-center/camera-stream', [CommandCenterController::class, 'stream']);
        Route::get('/command-center/get-obd-data-first', [CommandCenterController::class, 'getCommandCenterObdDataFirst']); 
        Route::post('/command-center/upload-video', [CommandCenterController::class, 'uploadVideo'])->name('commandcenter.upload.video');
        
        Route::get('/bodycam-device-by-id', [
            BodycamDeviceDataHelper::class, 
            'getBodycamDatabyId']);
                   
        // Close case
        Route::group(['prefix' => 'close', 'as' => 'close.'], function () {
            // Route::group(['middleware' => ['permission:read-users|modify-users|approve-users']], function () {
            Route::resource('case', CloseCaseController::class);
            Route::get('summary', [CloseCaseController::class, 'summary'])->name('summary');
            Route::get('summary/detail/{id}', [CloseCaseController::class, 'detailall'])->name('summary.detail');
            // });
            Route::get('kependudukan/ceknik/{nik}', [OpenCaseController::class, 'checkNik'])->name('kependudukan.ceknik');
            

            // Single Form
            Route::group(['prefix' => 'singleform', 'as' => 'singleform.'], function () {
                Route::resource('single-form', CloseSingleFormController::class)
                    ->middleware(['permission:read-interrog-record|modify-interrog-record|approve-interrog-record'])
                    ->except(['downloadFile']);
                    Route::get('single-form/download-dokumen/{path}', [CloseSingleFormController::class, 'downloadDokumen'])->name('single-form.download-dokumen');
                    Route::get('single-form/download/{path}', [CloseSingleFormController::class, 'downloadFile'])->name('single-form.download-file');
                    Route::post('single-form/upload-video', [CloseSingleFormController::class, 'uploadVideo1'])->name('single-form.upload.video');

                    Route::get('/single-form/video/tailing-perilaku', [CloseSingleFormController::class, 'videoTailingPerilaku'])->name('single-form.video.tailing-perilaku');
                    Route::get('/single-form/video/tailing-operasi', [CloseSingleFormController::class, 'videoTailingOperasi'])->name('single-form.video.tailing-operasi');
                    
                    Route::get('/single-form/video/infiltration-operasi', [CloseSingleFormController::class, 'videoInfiltrationOperasi'])->name('single-form.video.infiltration-operasi');
                    Route::get('/single-form/video/infiltration-dinamika', [CloseSingleFormController::class, 'videoInfiltrationDinamika'])->name('single-form.video.infiltration-dinamika');
                    
                    Route::get('/single-form/video/tapping-operasi', [CloseSingleFormController::class, 'videoTappingOperasi'])->name('single-form.video.tapping-operasi');
            });


            // Exploration Rencana Aksi
            Route::group(['prefix' => 'exploration', 'as' => 'exploration.'], function () {
                Route::resource('rencana-aksi', ExplorationRencanaAksiController::class)
                    // ->middleware(['permission:read-rencana-aksi|modify-rencana-aksi|approve-rencana-aksi'])
                    ;
                Route::get('collect-info/download/{path}', [ExplorationRencanaAksiController::class, 'downloadFile'])->name('collect-info.download-file');
            });
            // Exploration Identitas Target
            Route::group(['prefix' => 'exploration', 'as' => 'exploration.'], function () {
                Route::resource('identitas-target', ExplorationTargetIdentityController::class);
                    // ->middleware(['permission:read-identitas-target|modify-identitas-target|approve-identitas-target']);
                Route::get('indentitastarget-collect-info/download/{path}', [ExplorationTargetIdentityController::class, 'downloadFile'])->name('indentitastarget.collect-info.download-file');
            });
            // Exploration Hasil Pencapaian
            Route::group(['prefix' => 'exploration', 'as' => 'exploration.'], function () {
                Route::resource('hasil-pencapaian', ExplorationResultAchievmentController::class);
                    // ->middleware(['permission:read-hasil-pencapaian|modify-hasil-pencapaian|approve-hasil-pencapaian']);
                Route::get('hasil-pencapaian-collect-info/download/{path}', [ExplorationTargetIdentityController::class, 'downloadFile'])->name('hasil-pencapaian.collect-info.download-file');
                Route::get('report', [ExplorationResultAchievmentController::class, 'report'])->name('report');
                Route::get('report/download-report/{id}', [ExplorationResultAchievmentController::class, 'downloadReport'])->name('report.download');
            });


            // Delineation/ Penggambaran
            Route::group(['prefix' => 'delineation', 'as' => 'delineation.'], function () {
                // Warrant
                Route::resource('information-verification', DelineationInformationVerificationController::class)
                    // ->middleware(['permission:read-research-sprint|modify-research-sprint|approve-research-sprint'])
                    ->except(['downloadFile', 'detail']);
                Route::get('information-verification/download/{path}', [DelineationInformationVerificationController::class, 'downloadFile'])->name('warrant.download-file');
                Route::post('information-verification/detail', [DelineationInformationVerificationController::class, 'detail'])->name('warrant.detail');

                // Spesific Intel Report
                Route::resource('information-validation', DelineationInformationValidationController::class)
                    // ->middleware(['permission:read-research-lapinsus|modify-research-lapinsus|approve-research-lapinsus'])
                    ->except(['downloadFile', 'detail']);
                Route::get('information-validation/download/{path}', [DelineationInformationValidationController::class, 'downloadFile'])->name('spesific-intel-report.download-file');
                Route::post('information-validationt/detail', [DelineationInformationValidationController::class, 'detail'])->name('spesific-intel-report.detail');

                // Advice and Follow-up Measure
                Route::resource('scenario-relation', DelineationScenarioRelationController::class)
                    // ->middleware(['permission:read-research-saran-tl|modify-research-saran-tl|approve-research-saran-tl'])
                    ->except(['downloadFile', 'detail']);
                Route::get('scenario-relation/download/{path}', [DelineationScenarioRelationController::class, 'downloadFile'])->name('advice-measure.download-file');
                Route::post('scenario-relation/detail', [DelineationScenarioRelationController::class, 'detail'])->name('advice-measure.detail');

                // Threats, Interference, Barrier, Challenges (TIBC)
                Route::resource('report', DelineationReportController::class)
                    // ->middleware(['permission:read-research-potensi-aght|modify-research-potensi-aght|approve-research-potensi-aght'])
                    ->except(['downloadFile', 'detail']);
                Route::get('report/download/{path}', [DelineationReportController::class, 'downloadFile'])->name('report.download-file');
                Route::post('report/detail', [DelineationReportController::class, 'detail'])->name('tibc.detail');
            });


            // Tailing/ Pembuntutan
            Route::group(['prefix' => 'tailing', 'as' => 'tailing.'], function () {
                // Warrant
                Route::resource('pemahaman-perilaku', TailingPemahamanPerilakuController::class)
                    // ->middleware(['permission:read-research-sprint|modify-research-sprint|approve-research-sprint'])
                    ->except(['downloadFile', 'detail']);
                Route::get('pemahaman-perilaku/download/{path}', [TailingPemahamanPerilakuController::class, 'downloadFile'])->name('pemahaman-perilaku.download-file');
                Route::post('pemahaman-perilaku/detail', [TailingPemahamanPerilakuController::class, 'detail'])->name('warrant.detail');
                Route::post('pemahaman-perilaku/upload-video', [TailingPemahamanPerilakuController::class, 'uploadVideo'])->name('pemahaman-perilaku.upload.video');
                Route::get('pemahaman-perilaku/download-interview-video-audio/{path}', [TailingPemahamanPerilakuController::class, 'downloadAudiotoTextFile'])->name('pemahaman-perilaku.download-interview-audio-to-text-file');
        


                // Spesific Intel Report
                Route::resource('target-operasi', TailingTargetOperasiController::class)
                    // ->middleware(['permission:read-research-lapinsus|modify-research-lapinsus|approve-research-lapinsus'])
                    ->except(['downloadFile', 'detail']);
                Route::get('target-operasi/download/{path}', [TailingTargetOperasiController::class, 'downloadFile'])->name('target-operasi.download-file');
                Route::post('target-operasi/detail', [TailingTargetOperasiController::class, 'detail'])->name('spesific-intel-report.detail');
                Route::post('target-operasi/upload-video', [TailingTargetOperasiController::class, 'uploadVideo'])->name('target-operasi.upload.video');
                Route::get('target-operasi/download-interview-video-audio/{path}', [TailingTargetOperasiController::class, 'downloadAudiotoTextFile'])->name('target-operasi.download-interview-audio-to-text-file');
        

                // Advice and Follow-up Measure
                Route::resource('result-achievement', TailingResultAchievementController::class)
                    // ->middleware(['permission:read-research-saran-tl|modify-research-saran-tl|approve-research-saran-tl'])
                    ->except(['downloadFile', 'detail']);
                Route::get('result-achievement/download/{path}', [TailingResultAchievementController::class, 'downloadFile'])->name('result-achievement.download-file');
                Route::post('result-achievement/detail', [TailingResultAchievementController::class, 'detail'])->name('advice-measure.detail');

                // Threats, Interference, Barrier, Challenges (TIBC)
                Route::resource('report', TailingReportController::class)
                    // ->middleware(['permission:read-research-potensi-aght|modify-research-potensi-aght|approve-research-potensi-aght'])
                    ->except(['downloadFile', 'detail']);
                Route::get('report/download/{path}', [TailingReportController::class, 'downloadFile'])->name('report.download-file');
                Route::post('report/detail', [TailingReportController::class, 'detail'])->name('tibc.detail');
            });

            // close helper
            Route::get('/helper-case', [DataHelper::class, 'getCloseCase'])->name('close.helper-case');
            Route::get('/helper-sprint', [DataHelper::class, 'getCloseSprint'])->name('close.helper-sprint');
            Route::get('/helper-collect-info', [DataHelper::class, 'getCollectInfo'])->name('close.helper-collect-info');
            Route::get('/helper-threat', [DataHelper::class, 'getCloseAght'])->name('close.helper-threat');
            Route::get('/helper-exploration-rencana-aksi', [DataHelper::class, 'getExplorationRencanaAksi'])->name('close.helper-rencana-aksi');
            Route::get('/helper-exploration-target-identity', [DataHelper::class, 'getExplorationTargetId'])->name('close.helper-target-identity');
            
            Route::get('/helper-information-collection', [
                DelineationDataHelper::class, 
                'getInformationCollectionbyCaseId'])->name('close.helper-information-collection');
            
            Route::get('/helper-information-verification', [
                DelineationDataHelper::class, 
                    'getInformationVerificationbyInformationCollectionId'])->name('close.helper-information-verification');
            

            Route::get('/helper-information-validation', [
                DelineationDataHelper::class, 
                    'getInformationValidationbyInformationVerificationId'])->name('close.helper-information-validation');
        
            Route::get('/helper-infiltration-operasi-rahasia', [
                InfiltrationDataHelper::class, 
                'getInfiltrationOperasiRahasiabyCaseId'])->name('close.helper-infiltration-operasi-rahasia');
                
            Route::get('/helper-infiltration-dinamika-target', [
                InfiltrationDataHelper::class, 
                'getInfiltrationDinamikaTargetbyOperasiRahasiaId'])->name('close.helper-infiltration-dinamika-target');
            
            Route::get('/helper-tailing-pemahaman-perilaku', [
                    TailingDataHelper::class, 
                    'getTailingPemahamanPerilakubyCaseId'])->name('close.helper-tailing-pemahaman-perilaku');
                
             Route::get('/helper-tailing-target-operasi', [
                    TailingDataHelper::class, 
                    'getTailingTargetOperasibyTailingPemahamanPerilakuId'])->name('close.helper-tailing-target-operasi');
                     
           
            
            // infiltration Directive
            Route::group(['prefix' => 'infiltration', 'as' => 'infiltration.'], function () {
                // DIRECTIVE
                Route::resource('secret-operation', InfiltrationSecretOperationController::class)
                    // ->middleware(['permission:read-observation-directive|modify-observation-directive|approve-observation-directive'])
                    ->except(['downloadFile', 'detail']);
                // Route::get('secret-operation/download/{path}', [InfiltrationSecretOperationController::class, 'downloadFile'])->name('directive.download-file');
                Route::get('secret-operation/download/{path}', [InfiltrationSecretOperationController::class, 'downloadFile'])->name('secret-operation.download-file');
                Route::post('secret-operation/upload-video', [InfiltrationSecretOperationController::class, 'uploadVideo1'])->name('secret-operation.upload.video');
                Route::get('secret-operation/download-interview-video-audio/{path}', [InfiltrationSecretOperationController::class, 'downloadAudiotoTextFile'])->name('secret-operation.download-interview-audio-to-text-file');
        

                // INFORMATION COLLECT
                Route::resource('target-dynamics', InfiltrationTargetDynamicsController::class)
                    // ->middleware(['permission:read-observation-collect-info|modify-observation-collect-info|approve-observation-collect-info'])
                    ->except(['downloadFile', 'detail']);
                // Route::get('target-dynamics/download/{path}', [InfiltrationTargetDynamicsController::class, 'downloadFile'])->name('collect-info.download-file');
                Route::get('target-dynamics/download/{path}', [InfiltrationTargetDynamicsController::class, 'downloadFile'])->name('target-dynamics.download-file');
                Route::post('target-dynamics/upload-video', [InfiltrationTargetDynamicsController::class, 'uploadVideo1'])->name('target-dynamics.upload.video');
                Route::get('target-dynamics/download-interview-video-audio/{path}', [InfiltrationTargetDynamicsController::class, 'downloadAudiotoTextFile'])->name('target-dynamics.download-interview-audio-to-text-file');
        

                // THREAT / AGHT
                Route::resource('result-achievement', InfiltrationResultAchievementController::class)
                    // ->middleware(['permission:read-observation-threat|modify-observation-threat|approve-observation-threat'])
                    ->except(['downloadFile', 'detail']);
                Route::get('result-achievement/download/{path}', [InfiltrationResultAchievementController::class, 'downloadFile'])->name('result-achievement.download-file');

                // CONNECTED IDENTITY
                Route::resource('report', InfiltrationReportController::class)
                // ->middleware(['permission:read-research-potensi-aght|modify-research-potensi-aght|approve-research-potensi-aght'])
                    ->except(['downloadFile', 'detail']);
                Route::get('report/download/{path}', [InfiltrationReportController::class, 'downloadFile'])->name('report.download-file');
                Route::post('report/detail', [InfiltrationReportController::class, 'detail'])->name('report.detail');

                // Report
            });

            // Observation Directive
            Route::group(['prefix' => 'observation', 'as' => 'observation.'], function () {
                // DIRECTIVE
                Route::resource('directive', ObservDirectiveController::class)
                    ->middleware(['permission:read-observation-directive|modify-observation-directive|approve-observation-directive'])
                    ->except(['downloadFile', 'detail']);
                Route::get('directive/download/{path}', [ObservDirectiveController::class, 'downloadFile'])->name('directive.download-file');
                // Route::post('directive/detail', [ObservDirectiveController::class, 'detail'])->name('directive.detail');

                // INFORMATION COLLECT
                Route::resource('collect-info', ObservCollectInfoController::class)
                    ->middleware(['permission:read-observation-collect-info|modify-observation-collect-info|approve-observation-collect-info'])
                    ->except(['downloadFile', 'detail']);
                Route::get('collect-info/download/{path}', [ObservCollectInfoController::class, 'downloadFile'])->name('collect-info.download-file');

                // THREAT / AGHT
                Route::resource('threat', ObservThreatController::class)
                    ->middleware(['permission:read-observation-threat|modify-observation-threat|approve-observation-threat'])
                    ->except(['downloadFile', 'detail']);
                Route::get('threat/download/{path}', [ObservThreatController::class, 'downloadFile'])->name('threat.download-file');

                // CONNECTED IDENTITY
                Route::resource('connected-identity', ObservConnectController::class)
                    ->middleware(['permission:read-observation-connected-identity|modify-observation-connected-identity|approve-observation-connected-identity'])
                    ->except(['detail']);

                // Report
                Route::resource('report', ObservReportController::class)
                    ->middleware(['permission:read-observation-report|modify-observation-report|approve-observation-report'])
                    ->except(['downloadFile']);
                Route::get('report/download/{path}', [ObservReportController::class, 'downloadFile'])->name('report.download-file');
            });


            // Intrusion
            // intrusion helper
            Route::get('/helper-target-loc', [DataHelper::class, 'getClosTargetLoc'])->name('close.helper-target-loc');
            Route::get('/helper-target-env', [DataHelper::class, 'getClosTargetEnv'])->name('close.helper-target-env');
            Route::group(['prefix' => 'intrusion', 'as' => 'intrusion.'], function () {

                // Target location
                Route::resource('target-loc', IntrusionTargetLocController::class)
                    ->middleware(['permission:read-intrusion-target-loc|modify-intrusion-target-loc|approve-intrusion-target-loc'])
                    ->except(['downloadFile', 'detail']);
                Route::get('target-loc/download/{path}', [IntrusionTargetLocController::class, 'downloadFile'])->name('target-loc.download-file');
                Route::post('target-loc/upload-video', [IntrusionTargetLocController::class, 'uploadVideo'])->name('target-loc.upload.video');
                Route::get('target-loc/download-interview-video-audio/{path}', [IntrusionTargetLocController::class, 'downloadAudiotoTextFile'])->name('target-loc.download-interview-audio-to-text-file');
        

                // Target location
                Route::resource('target-env', IntrusionTargetEnvController::class)
                    ->middleware(['permission:read-intrusion-target-env|modify-intrusion-target-env|approve-intrusion-target-env'])
                    ->except(['downloadFile', 'detail']);
                Route::get('target-env/download/{path}', [IntrusionTargetEnvController::class, 'downloadFile'])->name('target-env.download-file');
                Route::post('target-env/upload-video', [IntrusionTargetEnvController::class, 'uploadVideo'])->name('target-env.upload.video');
                Route::get('target-env/download-interview-video-audio/{path}', [IntrusionTargetEnvController::class, 'downloadAudiotoTextFile'])->name('target-env.download-interview-audio-to-text-file');
        

                // Result
                Route::resource('result', IntrusionResultController::class)
                    ->middleware(['permission:read-intrusion-result|modify-intrusion-result|approve-intrusion-result'])
                    ->except(['downloadFile', 'detail']);
                Route::get('result/download/{path}', [IntrusionResultController::class, 'downloadFile'])->name('result.download-file');

                // Report
                Route::resource('report', IntrusionReportController::class)
                    ->middleware(['permission:read-tapping-result_achievement|modify-tapping-result_achievement|approve-tapping-result_achievement'])
                    ->except(['downloadFile']);
                Route::get('report/download/{path}', [IntrusionReportController::class, 'downloadFile'])->name('report.download-file');
            });

            // Tapping
            Route::group(['prefix' => 'tapping', 'as' => 'tapping.'], function () {
                // Electronic Device
                Route::resource('electronic_device', TappingElectronicDeviceController::class)
                    ->middleware(['permission:read-tapping-electronic_device|modify-tapping-electronic_device|approve-tapping-electronic_device'])
                    ->except(['downloadFile']);
                Route::get('electronic_device/download-dokumen/{path}', [TappingElectronicDeviceController::class, 'downloadDokumen'])->name('hasil.download-dokumen');
                Route::get('electronic_device/download-video/{path}', [TappingElectronicDeviceController::class, 'downloadVideo'])->name('hasil.download-video');
                Route::post('electronic_device/upload-video', [TappingElectronicDeviceController::class, 'uploadVideo1'])->name('electronic-device.upload.video');
                Route::get('electronic_device/download-interview-video-audio/{path}', [TappingElectronicDeviceController::class, 'downloadAudiotoTextFile'])->name('electronic-device.download-interview-audio-to-text-file');
        


                // Intelligent Signal
                Route::resource('intelligent_signal', TappingIntelligentSignalController::class)
                    ->middleware(['permission:read-tapping-intelligent_signal|modify-tapping-intelligent_signal|approve-tapping-intelligent_signal'])
                    ->except(['downloadFile']);
                Route::get('intelligent_signal/download-dokumen/{path}', [TappingIntelligentSignalController::class, 'downloadDokumen'])->name('intelligent_signal.download-dokumen');
                Route::get('intelligent_signal/download-video/{path}', [TappingIntelligentSignalController::class, 'downloadVideo'])->name('intelligent_signal.download-video');
                Route::post('intelligent_signal/upload-video', [TappingIntelligentSignalController::class, 'uploadVideo1'])->name('intelligent-signal.upload.video');
                Route::get('intelligent_signal/download-interview-video-audio/{path}', [TappingIntelligentSignalController::class, 'downloadAudiotoTextFile'])->name('intelligent-signal.download-interview-audio-to-text-file');
        

                // Result Achievement
                Route::resource('result_achievement', TappingResultAchievementController::class)
                    ->middleware(['permission:read-tapping-result_achievement|modify-tapping-result_achievement|approve-tapping-result_achievement'])
                    ->except(['downloadFile']);
                Route::get('result_achievement/download-dokumen/{path}', [TappingElectronicDeviceController::class, 'downloadDokumen'])->name('result_achievement.download-dokumen');
                Route::get('report/download/{path}', [TappingResultAchievementController::class, 'downloadFile'])->name('result_achievement.download-file');

                // Report
                Route::resource('report', TappingReportController::class)
                    ->middleware(['permission:read-tapping-report|modify-tapping-report|approve-tapping-report'])
                    ->except(['downloadFile']);
                Route::get('report/download/{path}', [TappingReportController::class, 'downloadFile'])->name('report.download-file');
            });
        });
        Route::get('tibc/download/{path}', [ResearchPotensiAghtController::class, 'downloadFile'])->name('tibc.download-file');
    });
});
