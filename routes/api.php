<?php

use App\Helpers\ResearchDataHelper;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\CommandCenterController;
use App\Http\Controllers\API\Open\OpenCaseController;
use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\close\CloseCaseController;
use App\Http\Controllers\API\Master\MasterDataController;
// use App\Http\Controllers\Observation\ObservThreatController;
// use App\Http\Controllers\Observation\ObservConnectController;
// use App\Http\Controllers\Observation\ObservDirectiveController;
use App\Http\Controllers\API\Open\OpenSingleFormController;
use App\Http\Controllers\API\close\CloseSingleFormController;
// use App\Http\Controllers\API\Observation\ObservCollectInfoController;

use App\Http\Controllers\API\close\tailing\TailingReportController;
use App\Http\Controllers\API\close\tapping\TappingReportController;
use App\Http\Controllers\API\Open\Research\ResearchReportController;
use App\Http\Controllers\API\Open\Interview\InterviewHasilController;

use App\Http\Controllers\API\close\observation\ObservReportController;
use App\Http\Controllers\API\close\observation\ObservThreatController;
use App\Http\Controllers\API\Open\Interview\InterviewJadwalController;
use App\Http\Controllers\API\Open\Interview\InterviewReportController;

use App\Http\Controllers\API\close\intrusion\IntrusionReportController;

use App\Http\Controllers\API\close\intrusion\IntrusionResultController;
use App\Http\Controllers\API\close\observation\ObservConnectController;
use App\Http\Controllers\API\close\observation\ObservDirectiveController;
use App\Http\Controllers\API\Open\Research\ResearchPotensiAghtController;

use App\Http\Controllers\API\close\intrusion\IntrusionTargetEnvController;
use App\Http\Controllers\API\close\intrusion\IntrusionTargetLocController;

use App\Http\Controllers\API\close\tailing\TailingTargetOperasiController;
use App\Http\Controllers\API\Open\Elicitation\ElicitationAdFollController;
use App\Http\Controllers\API\Open\Elicitation\ElicitationReportController;

use App\Http\Controllers\API\close\delineation\DelineationReportController;
use App\Http\Controllers\API\close\exploration\ExplorationReportController;
use App\Http\Controllers\API\close\observation\ObservCollectInfoController;
use App\Http\Controllers\API\Open\Research\ResearchSuratPerintahController;


use App\Http\Controllers\API\close\infiltration\InfiltrationReportController;
use App\Http\Controllers\API\close\tapping\TappingElectronicDeviceController;
use App\Http\Controllers\API\Open\Elicitation\ElicitationInterviewController;
use App\Http\Controllers\API\close\tailing\TailingPemahamanPerilakuController;

use App\Http\Controllers\API\close\tailing\TailingResultAchievementController;
use App\Http\Controllers\API\close\tapping\TappingIntelligentSignalController;
use App\Http\Controllers\API\close\tapping\TappingResultAchievementController;


use App\Http\Controllers\API\Open\Interrogation\InterrogationRecordController;
use App\Http\Controllers\API\Open\Interrogation\InterrogationReportController;
use App\Http\Controllers\API\close\exploration\ExplorationActionPlanController;

use App\Http\Controllers\API\Open\Research\ResearchSaranTindakLanjutController;
use App\Http\Controllers\API\Open\Interview\InterviewSaranTindakLanjutController;
use App\Http\Controllers\API\Open\Research\ResearchPotensiAghtLampiranController;

use App\Http\Controllers\API\Open\Research\ResearchSuratPerintahMemberController;
use App\Http\Controllers\API\close\exploration\ExplorationTargetIdentityController;
use App\Http\Controllers\API\Open\Research\ResearchLaporanInformasiKhususController;
use App\Http\Controllers\API\close\delineation\DelineationScenarioRelationController;
use App\Http\Controllers\API\close\infiltration\InfiltrationTargetDynamicsController;

use App\Http\Controllers\API\Open\Elicitation\ElicitationResultAchievementController;
// use App\Http\Controllers\API\close\delineation\DelineationReportController;
// use App\Http\Controllers\API\close\exploration\ExplorationReportController;
// use App\Http\Controllers\API\close\infiltration\InfiltrationReportController;
use App\Http\Controllers\API\close\exploration\ExplorationResultAchievementController;
use App\Http\Controllers\API\close\infiltration\InfiltrationSecretOperationController;
use App\Http\Controllers\API\close\infiltration\InfiltrationResultAchievementController;
use App\Http\Controllers\API\Open\Interrogation\InterrogationResultAchievementController;
use App\Http\Controllers\API\close\delineation\DelineationInformationValidationController;
use App\Http\Controllers\API\close\delineation\DelineationInformationVerificationController;
use App\Http\Controllers\API\Open\Interrogation\InterrogationTargetIdentificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'authentication'], function () {
    Route::post('login', [AuthenticationController::class, 'login']);

    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('refresh', [AuthenticationController::class, 'refresh']);
        Route::post('logout', [AuthenticationController::class, 'logout']);
    });
});

Route::middleware(['authChecker'])->group(function () {
    // USER
    Route::resource('user-management', UserController::class);

    // MASTER DATA
    Route::group(['prefix' => 'master', 'as' => 'api.master.'], function () {

        Route::get('get-satker', [MasterDataController::class, 'getSatker']);
        // Master for open case
        Route::get('open-case', [MasterDataController::class, 'getOpenCase']);
        Route::get('getSuratPerintahbyCaseId', [MasterDataController::class, 'getSuratPerintahbyCaseId']);
        Route::get('getLapinsusbySuratPerintahId', [MasterDataController::class, 'getLapinsusbySuratPerintahId']);
        Route::get('getSaranTLbyLapinsusId', [MasterDataController::class, 'getSaranTLbyLapinsusId']);

        Route::get('getJadwalWawancarabyCaseId', [MasterDataController::class, 'getJadwalWawancarabyCaseId']);
        Route::get('getHasilWawancarabyJadwalWawancaraId', [MasterDataController::class, 'getHasilWawancarabyJadwalWawancaraId']);

        Route::get('getBeritaAcarabyCaseId', [MasterDataController::class, 'getBeritaAcarabyCaseId']);
        Route::get('getIdentifikasiTargetbyBeritaAcaraId', [MasterDataController::class, 'getIdentifikasiTargetbyBeritaAcaraId']);

        Route::get('getHasilWawancaraElicitationbyCaseId', [MasterDataController::class, 'getHasilWawancaraElicitationbyCaseId']);
        Route::get('getElicitationSaranTLbyElicitationInterviewId', [MasterDataController::class, 'getElicitationSaranTLbyElicitationInterviewId']);




        Route::get('close-case', [MasterDataController::class, 'getCloseCase']);
        Route::get('getSuratPerintahClosebyCaseId', [MasterDataController::class, 'getSuratPerintahClosebyCaseId']);
        Route::get('getSumberInformasibySuratPerintahId', [MasterDataController::class, 'getSumberInformasibySuratPerintahId']);
        Route::get('getAGHTbySumberInformasiId', [MasterDataController::class, 'getAGHTbySumberInformasiId']);

        Route::get('getInformationVerificationbyInformationCollectionId', [MasterDataController::class, 'getInformationVerificationbyInformationCollectionId']);
        Route::get('getInformationValidationbyInformationVerificationId', [MasterDataController::class, 'getInformationValidationbyInformationVerificationId']);

        Route::get('getRencanaAksibyCaseId', [MasterDataController::class, 'getRencanaAksibyCaseId']);
        Route::get('getIdentitasTargetbyRencanaAksiId', [MasterDataController::class, 'getIdentitasTargetbyRencanaAksiId']);

        Route::get('getTailingPemahamanPerilakubyCaseId', [MasterDataController::class, 'getTailingPemahamanPerilakubyCaseId']);
        Route::get('getTailingTargetOperasibyTailingPemahamanPerilakuId', [MasterDataController::class, 'getTailingTargetOperasibyTailingPemahamanPerilakuId']);
        
        Route::get('getPenyusupanOperasiRahasiabyCaseId', [MasterDataController::class, 'getPenyusupanOperasiRahasiabyCaseId']);
        Route::get('getPenyusupanDinamikaTargetbyPenyusupanOperasiRahasiaId', [MasterDataController::class, 'getPenyusupanDinamikaTargetbyPenyusupanOperasiRahasiaId']);
        
        Route::get('getIntrusionLokasiTargetbyCaseId', [MasterDataController::class, 'getIntrusionLokasiTargetbyCaseId']);
        Route::get('getIntrusionLingkunganTargetbyIntrusionLokasiId', [MasterDataController::class, 'getIntrusionLingkunganTargetbyIntrusionLokasiId']);
        
        Route::get('getTappingDataElectronicbyCaseId', [MasterDataController::class, 'getTappingDataElectronicbyCaseId']);
        Route::get('getTappingSinyalDatabyTappingDataElectronicId', [MasterDataController::class, 'getTappingSinyalDatabyTappingDataElectronicId']);
       

        

        Route::get('getCaseValidInterogationRecord', [MasterDataController::class, 'getCaseValidInterogationRecord']);
        Route::get('getSprint', [MasterDataController::class, 'getSprint']);
        Route::get('getInterogrecord', [MasterDataController::class, 'getInterogrecord']);
        Route::get('getInterogTarget', [MasterDataController::class, 'getInterogTarget']);
        Route::get('getLapinsus', [MasterDataController::class, 'getLapinsus']);
        Route::get('getElicitationInterview', [MasterDataController::class, 'getElicitationInterview']);
        Route::get('getSaranTl', [MasterDataController::class, 'getSaranTl']);
        Route::get('getElicitationAdfoll', [MasterDataController::class, 'getElicitationAdfoll']);

        // Master for close case
        // Route::get('getCloseCase', [MasterDataController::class, 'getCloseCase']);
        Route::get('getCloseSprint', [MasterDataController::class, 'getCloseSprint']);
        Route::get('getCloseCaseByObservDirective', [MasterDataController::class, 'getCloseCaseByObservDirective']);
        Route::get('getCloseCaseByObservCollectInfo', [MasterDataController::class, 'getCloseCaseByObservCollectInfo']);
        Route::get('getCloseCaseByObservThreat', [MasterDataController::class, 'getCloseCaseByObservThreat']);
        Route::get('getCloseCaseByObservConnect', [MasterDataController::class, 'getCloseCaseByObservConnect']);

    });
    // COMMAND CENTER
    Route::group(['prefix' => 'commandcenter', 'as' => 'commandcetner.'], function () {
        Route::get('getlastvideo', [CommandCenterController::class, 'getLastVideo']);
        Route::get('getlastposition', [CommandCenterController::class, 'getLastPosition']);
        Route::get('getcamera', [CommandCenterController::class, 'getCamera']);
    });

    // DASHBOARD OPEN & CLOSE
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::get('open', [DashboardController::class, 'open']);
        Route::get('close', [DashboardController::class, 'close']);
    });

    // OPEN CASE
    Route::group(['prefix' => 'open', 'as' => 'api.open.'], function () {
        Route::resource('case', OpenCaseController::class);
        Route::get('case-all', [OpenCaseController::class, 'getreport']);

        // OPEN CASE Single Form
        Route::group(['prefix' => 'singleform', 'as' => 'singleform.'], function () {
            Route::resource('single-form', OpenSingleFormController::class)
                ->except(['downloadFile']);
                Route::get('single-form/download/{path}', [OpenSingleFormController::class, 'downloadFile'])->name('single-form.download-file');
        });
    });

    Route::get('/helper-research-sprint', [ResearchDataHelper::class, 'getSuratPerintahByCaseAPI'])->name('helper-research-sprint');
    Route::get('/helper-research-lapinsus', [ResearchDataHelper::class, 'getLapinsusBySuratPerintahAPI'])->name('helper-research-lapinsus');
    Route::get('/helper-research-saran', [ResearchDataHelper::class, 'getSaranTinjutByLapinsusAPI'])->name('helper-research-saran');


    // RESEARCH
    Route::group(['prefix' => 'open', 'as' => 'open.'], function () {
        Route::group(['prefix' => 'penelitian', 'as' => 'penelitian.'], function () {
            Route::resource('surat-perintah', ResearchSuratPerintahController::class)->except('downloadFile');
            // Route::resource('surat-perintah-member', ResearchSuratPerintahMemberController::class);
            Route::resource('laporan-informasi-khusus', ResearchLaporanInformasiKhususController::class)->except('downloadFile');
            Route::resource('saran-tindak-lanjut', ResearchSaranTindakLanjutController::class);
            Route::resource('potensi-aght', ResearchPotensiAghtController::class);
            // Route::resource('potensi-aght-lampiran', ResearchPotensiAghtLampiranController::class);
            // Route::get('laporan/{case_id}', ResearchReportController::class)->name('report');
            
            Route::get('get-report/{caseid}', [ResearchReportController::class, 'downloadReport']);
    

            Route::get('surat-perintah/unduh-file/{path}', [ResearchSuratPerintahController::class, 'downloadFile'])
                ->name('surat-perintah.download-file');
            Route::get('laporan-informasi-khusus/unduh-file/{path}', [ResearchLaporanInformasiKhususController::class, 'downloadFile'])
                ->name('laporan-informasi-khusus.download-file');
        });
    });

    // INTERVIEW
    Route::group(['prefix' => 'open', 'as' => 'open.'], function () {
        Route::group(['prefix' => 'wawancara', 'as' => 'wawancara.'], function () {
            Route::resource('jadwal', InterviewJadwalController::class)->except('downloadFile');
            Route::resource('hasil', InterviewHasilController::class)->except('downloadFile');
            Route::resource('saran-tindak-lanjut', InterviewSaranTindakLanjutController::class);
            // Route::get('laporan/{case_id}', InterviewReportController::class)->name('laporan');
            
            Route::get('get-report/{caseid}', [InterviewReportController::class, 'downloadReport']);
    

            Route::get('jadwal/unduh-file/{path}', [InterviewJadwalController::class, 'downloadFile'])->name('jadwal.download-file');
            Route::get('hasil/unduh-file/{path}', [InterviewHasilController::class, 'downloadFile'])->name('hasil.download-file');
        });

        // INTERROGATION
        Route::group(['prefix' => 'interrogation', 'as' => 'interrogation.'], function () {
            Route::get('interrogation-bap/{id}', [InterrogationRecordController::class, 'downloadBap'])->name('interogation.downloadBap');
            Route::resource('interrogation-record', InterrogationRecordController::class);

            Route::resource('interrogation-target-ident', InterrogationTargetIdentificationController::class);
            Route::resource('interrogation-result-achievement', InterrogationResultAchievementController::class);

            Route::get('get-report/{caseid}', [InterrogationReportController::class, 'downloadReport']);
        });
    });

    

    // ELICIATION
    Route::group(['prefix' => 'open', 'as' => 'open.'], function () {
        Route::group(['prefix' => 'elicitation', 'as' => 'elicitation.'], function () {
            Route::resource('elicitation-interview-result', ElicitationInterviewController::class);
            Route::resource('elicitation-ad-fol', ElicitationAdFollController::class);
            Route::resource('elicitation-result-achievement', ElicitationResultAchievementController::class);

            Route::get('get-report/{caseid}', [ElicitationReportController::class, 'downloadReport']);
        });
    });

    // CLOSE CASE
    Route::group(['prefix' => 'close', 'as' => 'close'], function () {
        Route::resource('case', CloseCaseController::class);
        Route::get('case-all', [CloseCaseController::class, 'getreport']);
        Route::post('case/store', [CloseCaseController::class, 'save']);
        Route::post('case/update/{id}', [CloseCaseController::class, 'update']);
        Route::delete('case/delete/{id}', [CloseCaseController::class, 'delete']);

        // CLOSE CASE Single Form
        Route::group(['prefix' => 'singleform', 'as' => 'singleform.'], function () {
            Route::resource('single-form', CloseSingleFormController::class)
                ->except(['downloadFile']);
                Route::get('single-form/download/{path}', [CloseSingleFormController::class, 'downloadFile'])->name('single-form.download-file');
        });

        // OBSERVATION
        Route::group(['prefix' => 'observation', 'as' => 'observation.'], function () {
            // DIRECTIVE
            Route::resource('directive', ObservDirectiveController::class);
            // INFORMATION COLLECT
            Route::resource('collect-info', ObservCollectInfoController::class);
            // THREAT
            Route::resource('threat', ObservThreatController::class);
            // CONNECTED IDENTITY
            Route::resource('connected-identity', ObservConnectController::class);

            Route::get('get-report/{caseid}', [ObservReportController::class, 'downloadReport']);
            // Route::get('get-report/{caseid}', [ElicitationReportController::class, 'downloadReport']);
        });  

         // EXPLORATION
        Route::group(['prefix' => 'exploration', 'as' => 'exploration.'], function () {
            Route::resource('exploration-action-plan', ExplorationActionPlanController::class);
            Route::resource('exploration-target-identity', ExplorationTargetIdentityController::class);
            Route::resource('exploration-result-achievement', ExplorationResultAchievementController::class);

            // Route::get('get-report/{caseid}', [ExplorationReportController::class, 'getreport']);
            Route::get('get-report/{caseid}', [ExplorationReportController::class, 'downloadReport']);
        });

      
        // INTRUSION
        Route::group(['prefix' => 'intrusion', 'as' => 'intrusion.'], function () {
            // TARGET LOCATION
            Route::resource('target-loc', IntrusionTargetLocController::class);
            // TARGET ENVIRONMENT
            Route::resource('target-env', IntrusionTargetEnvController::class);
            // RESULT
            Route::resource('result', IntrusionResultController::class);

            Route::get('get-report/{caseid}', [IntrusionReportController::class, 'downloadReport']);
        });  

        // DELINEATION
        Route::group(['prefix' => 'delineation', 'as' => 'delineation.'], function () {
            Route::resource('information-verification', DelineationInformationVerificationController::class);
            Route::resource('information-validation', DelineationInformationValidationController::class);
            Route::resource('scenario-relation', DelineationScenarioRelationController::class);

            // Route::get('get-report/{caseid}', [DelineationReportController::class, 'getreport']);
            Route::get('get-report/{caseid}', [DelineationReportController::class, 'downloadReport']);
        });

        // TAILING
        Route::group(['prefix' => 'tailing', 'as' => 'tailing.'], function () {
            Route::resource('pemahaman-perilaku', TailingPemahamanPerilakuController::class);
            Route::resource('target-operasi', TailingTargetOperasiController::class);
            Route::resource('result-achievement', TailingResultAchievementController::class);
            Route::get('get-report/{caseid}', [TailingReportController::class, 'downloadReport']);
            // Route::get('get-report/{caseid}', [TailingReportController::class, 'getreport']);
        });

        // INFILTRATION
        Route::group(['prefix' => 'infiltration', 'as' => 'infiltration.'], function () {
            Route::resource('secret-operation', InfiltrationSecretOperationController::class);
            Route::resource('target-dynamics', InfiltrationTargetDynamicsController::class);
            Route::resource('result-achievement', InfiltrationResultAchievementController::class);

            Route::get('get-report/{caseid}', [InfiltrationReportController::class, 'downloadReport']);
        });

        // TAPPING
        Route::group(['prefix' => 'penyadapan', 'as' => 'penyadapan.'], function () {
            Route::resource('perangkat-elektronik', TappingElectronicDeviceController::class)->except('downloadFile');
            Route::resource('sinyal-intelijen', TappingIntelligentSignalController::class)->except('downloadFile');
            Route::resource('hasil-pencapaian', TappingResultAchievementController::class)->except('downloadFile');
            Route::get('get-report/{case_id}', [TappingReportController::class, 'downloadReport']);
        
            Route::get('perangkat-elektronik/unduh-file/{path}', [TappingElectronicDeviceController::class, 'downloadFile'])->name('hasil.download-file');
            Route::get('sinyal-intelijen/unduh-file/{path}', [TappingIntelligentSignalController::class, 'downloadFile'])->name('hasil.download-file');
            Route::get('hasil-pencapaian/unduh-file/{path}', [TappingResultAchievementController::class, 'downloadFile'])->name('hasil.download-file');
        });
    });

   
});
