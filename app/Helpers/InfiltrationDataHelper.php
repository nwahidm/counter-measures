<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\CloseCase;
use App\Models\CaseCloseProgresses;
use App\Models\Infiltration\InfiltrationSecretOperation;
use App\Models\Infiltration\InfiltrationTargetDynamics;


class InfiltrationDataHelper
{

    

    public static function getInfiltrationDinamikaTargetbyOperasiRahasiaId($infiltration_operasi_rahasia_idd = null)
    {
        $infiltration_operasi_rahasia_idd = request()->query('infiltration_operasi_rahasia_idd') ?? $infiltration_operasi_rahasia_idd;

        if (!$infiltration_operasi_rahasia_idd) {
            return [];
        } else {
            $observation_collection_info = InfiltrationTargetDynamics::where('infiltration_operasi_rahasia_id', $infiltration_operasi_rahasia_idd)->get();
            $data_observation_collection_info = $observation_collection_info->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] =  $item->dinamika_teramati . " - " . $item->tanggal_dinamika_teramati ;
                return $data;
            });
            return collect($data_observation_collection_info);
        }
    }

    public static function getInfiltrationOperasiRahasiabyCaseId($case_id = null)
    {
        $case_id = request()->query('case_id') ?? $case_id;

        if (!$case_id) {
            return [];
        } else {
            $observation_collection_info = InfiltrationSecretOperation::where('case_id', $case_id)->get();
            $data_observation_collection_info = $observation_collection_info->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] =  $item->nama_operasi_rahasia . " - " . $item->tanggal_operasi_rahasia ;
                return $data;
            });
            return collect($data_observation_collection_info);
        }
    }

    public static function getCloseCaseByTailingReport()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.tailing_laporan', '1')
            ->get();
        }else{  
            $case = CloseCase::where('satker_id', auth()->user()->satker->kode_satker)
            >join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.tailing_laporan', '1')
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->case_id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);
            
            return $data;
        });

        return collect($data);
    }

    public static function getCloseCaseByInfiltrationSecretOperation()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.infiltration_operasi_rahasia', '1')
            ->get();
        }else{  
            $case = CloseCase::where('satker_id', auth()->user()->satker->kode_satker)
            >join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.infiltration_operasi_rahasia', '1')
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
