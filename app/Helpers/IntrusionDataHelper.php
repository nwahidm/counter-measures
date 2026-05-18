<?php

namespace App\Helpers;

use App\Models\CloseCase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\CaseCloseProgresses;

class IntrusionDataHelper
{

    // case sudah input infiltration result
    public static function getCloseCaseByInfiltrationResult()
    {
        if(auth()->user()->user_roles == "superadmin"){
            // $case = CloseCase::
            // join('infiltration_hasil_yang_dicapai', DB::raw('close_case.id::text'), 'infiltration_hasil_yang_dicapai.case_id')
            // ->select('close_case.id', 'close_case.case_name')
            // ->distinct('close_case.case_name', 'close_case.id')
            // ->orderBy('close_case.case_name')
            // ->get();
            $case = CloseCase::
            select('id', 'case_name')
            ->distinct('case_name', 'id')
            ->orderBy('case_name')
            ->get();
        }else{  
            // $case = CloseCase::
            // join('infiltration_hasil_yang_dicapai', DB::raw('close_case.id::text'), 'infiltration_hasil_yang_dicapai.case_id')
            // ->select('close_case.id', 'close_case.case_name')
            // ->distinct('close_case.case_name', 'close_case.id')
            // ->orderBy('close_case.case_name')
            // ->where('close_case.satker_id', auth()->user()->satker->kode_satker)
            // ->get();
            $case =CloseCase::
            select('id', 'case_name')
            ->distinct('case_name', 'id')
            ->orderBy('case_name')
            ->where('satker_id', auth()->user()->satker->kode_satker)
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    // case sudah input location
    public static function getCloseCaseByIntrusionLocation()
    {
        if(auth()->user()->user_roles == "superadmin"){
            // $case = CloseCase::
            // join('intrusion_target_lokasi', 'close_case.id', 'intrusion_target_lokasi.case_id')
            // ->select('close_case.id', 'close_case.case_name')
            // ->distinct('close_case.case_name', 'close_case.id')
            // ->orderBy('close_case.case_name')
            // ->get();
            $case =CloseCase::
            select('id', 'case_name')
            ->distinct('case_name', 'id')
            ->orderBy('case_name')
            ->get();
        }else{  
            // $case = CloseCase::
            // join('intrusion_target_lokasi', 'close_case.id', 'intrusion_target_lokasi.case_id')
            // ->select('close_case.id', 'close_case.case_name')
            // ->distinct('close_case.case_name', 'close_case.id')
            // ->orderBy('close_case.case_name')
            // ->where('close_case.satker_id', auth()->user()->satker->kode_satker)
            // ->get();
            $case =CloseCase::
            select('id', 'case_name')
            ->distinct('case_name', 'id')
            ->orderBy('case_name')
            ->where('satker_id', auth()->user()->satker->kode_satker)
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    // case sudah input environment
    public static function getCloseCaseByIntrusionEnvironment()
    {
        if(auth()->user()->user_roles == "superadmin"){
            // $case = CloseCase::
            // join('intrusion_lingkungan_target', 'close_case.id', 'intrusion_lingkungan_target.case_id')
            // ->select('close_case.id', 'close_case.case_name')
            // ->distinct('close_case.case_name', 'close_case.id')
            // ->orderBy('close_case.case_name')
            // ->get();
            $case =CloseCase::
            select('id', 'case_name')
            ->distinct('case_name', 'id')
            ->orderBy('case_name')
            ->get();
        }else{  
            // $case = CloseCase::
            // join('intrusion_lingkungan_target', 'close_case.id', 'intrusion_lingkungan_target.case_id')
            // ->select('close_case.id', 'close_case.case_name')
            // ->distinct('close_case.case_name', 'close_case.id')
            // ->orderBy('close_case.case_name')
            // ->where('close_case.satker_id', auth()->user()->satker->kode_satker)
            // ->get();
            $case =CloseCase::
            select('id', 'case_name')
            ->distinct('case_name', 'id')
            ->orderBy('case_name')
            ->where('satker_id', auth()->user()->satker->kode_satker)
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    // case sudah input environment
    public static function getCloseCaseByIntrusionResult()
    {
        if(auth()->user()->user_roles == "superadmin"){
            // $case = CloseCase::
            // join('intrusion_hasil_yang_dicapai', 'close_case.id', 'intrusion_hasil_yang_dicapai.case_id')
            // ->select('close_case.id', 'close_case.case_name')
            // ->distinct('close_case.case_name', 'close_case.id')
            // ->orderBy('close_case.case_name')
            // ->get();
            $case =CloseCase::
            select('id', 'case_name')
            ->distinct('case_name', 'id')
            ->orderBy('case_name')
            ->get();
        }else{  
            // $case = CloseCase::
            // join('intrusion_hasil_yang_dicapai', 'close_case.id', 'intrusion_hasil_yang_dicapai.case_id')
            // ->select('close_case.id', 'close_case.case_name')
            // ->distinct('close_case.case_name', 'close_case.id')
            // ->orderBy('close_case.case_name')
            // ->where('close_case.satker_id', auth()->user()->satker->kode_satker)
            // ->get();
            $case =CloseCase::
            select('id', 'case_name')
            ->distinct('case_name', 'id')
            ->orderBy('case_name')
            ->where('satker_id', auth()->user()->satker->kode_satker)
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    

}