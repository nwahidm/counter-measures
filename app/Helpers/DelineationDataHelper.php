<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\CloseCase;
use App\Models\CaseCloseProgresses;
use App\Models\Observation\ObservCollectInfo;
use App\Models\Delineation\DelineationInformationValidation;
use App\Models\Delineation\DelineationInformationVerification;


class DelineationDataHelper
{

    public static function getInformationCollectionbyCaseId($case_id = null)
    {
        $case_id = request()->query('case_id') ?? $case_id;

        if (!$case_id) {
            return [];
        } else {
            $observation_collection_info = ObservCollectInfo::where('case_id', $case_id)->get();
            $data_observation_collection_info = $observation_collection_info->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] =  $item->information_collection_perihal . " - " . $item->information_collection_source . " - " . $item->information_collection_date?->isoFormat('YYYY-MM-DD');
                return $data;
            });
            return collect($data_observation_collection_info);
        }
    }

    public static function getInformationVerificationbyInformationCollectionId($information_collection_id = null)
    {
        $information_collection_id = request()->query('information_collection_id') ?? $information_collection_id;

        if (!$information_collection_id) {
            return [];
        } else {
            $observation_collection_info = DelineationInformationVerification::where('information_collection_id', $information_collection_id)->get();
            $data_observation_collection_info = $observation_collection_info->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] = $item->kredibilitas_sumber . " - " . $item->metode_verifikasi . " - " . $item->verification_date;
                return $data;
            });
            return collect($data_observation_collection_info);
        }
    }

    public static function getInformationValidationbyInformationVerificationId($information_verification_id = null)
    {
        $information_verification_id = request()->query('information_verification_id') ?? $information_verification_id;

        if (!$information_verification_id) {
            return [];
        } else {
            $observation_collection_info = DelineationInformationValidation::where('information_verification_id', $information_verification_id)->get();
            $data_observation_collection_info = $observation_collection_info->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] = $item->metode_validasi . " - " . $item->tanggal_validasi ;
                return $data;
            });
            return collect($data_observation_collection_info);
        }
    }
    
    public static function getCloseCaseByObservationReport()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.observation_laporan', '1')
            ->get();
        }else{  
            $case = CloseCase::where('satker_id', auth()->user()->satker->kode_satker)
            >join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.observation_laporan', '1')
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->case_id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    public static function getCloseCaseByDelineationInformationVerification()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.delineation_informasi_verifikasi', '1')
            ->get();
        }else{  
            $case = CloseCase::where('satker_id', auth()->user()->satker->kode_satker)
            >join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.delineation_informasi_verifikasi', '1')
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->case_id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    public static function getCloseCaseByDelineationInformationValidation()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.delineation_informasi_validation', '1')
            ->get();
        }else{  
            $case = CloseCase::where('satker_id', auth()->user()->satker->kode_satker)
            >join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.delineation_informasi_validation', '1')
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->case_id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    public static function getCloseCaseByDelineationSkenarioRelation()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.delineation_skenario_relasi', '1')
            ->get();
        }else{  
            $case = CloseCase::where('satker_id', auth()->user()->satker->kode_satker)
            >join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.delineation_skenario_relasi', '1')
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->case_id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }
}
