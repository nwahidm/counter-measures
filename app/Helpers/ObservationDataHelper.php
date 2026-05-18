<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\CloseCase;
use App\Models\CaseCloseProgresses;

class ObservationDataHelper
{

    // case sudah input surat perintah
    public static function getCloseCaseByObservDirective()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::
            join('observation_surat_perintah', 'close_case.id', 'observation_surat_perintah.case_id')
            ->select('close_case.id', 'close_case.case_name')
            ->distinct('close_case.case_name', 'close_case.id')
            ->orderBy('close_case.case_name')
            ->get();
        }else{  
            $case = CloseCase::
            join('observation_surat_perintah', 'close_case.id', 'observation_surat_perintah.case_id')
            ->select('close_case.id', 'close_case.case_name')
            ->distinct('close_case.case_name', 'close_case.id')
            ->orderBy('close_case.case_name')
            ->where('close_case.satker_id', auth()->user()->satker->kode_satker)
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    // case sudah input collect info
    public static function getCloseCaseByObservCollectInfo()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::
            join('observation_information_collection', 'close_case.id', 'observation_information_collection.case_id')
            ->select('close_case.id', 'close_case.case_name')
            ->distinct('close_case.case_name', 'close_case.id')
            ->orderBy('close_case.case_name')
            ->get();
        }else{  
            $case = CloseCase::
            join('observation_information_collection', 'close_case.id', 'observation_information_collection.case_id')
            ->select('close_case.id', 'close_case.case_name')
            ->distinct('close_case.case_name', 'close_case.id')
            ->orderBy('close_case.case_name')
            ->where('close_case.satker_id', auth()->user()->satker->id_satker)
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    // case sudah input threat
    public static function getCloseCaseByObservThreat()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::
            join('observation_potensi_aght', 'close_case.id', 'observation_potensi_aght.case_id')
            ->select('close_case.id', 'close_case.case_name')
            ->distinct('close_case.case_name', 'close_case.id')
            ->orderBy('close_case.case_name')
            ->get();
        }else{  
            $case = CloseCase::
            join('observation_potensi_aght', 'close_case.id', 'observation_potensi_aght.case_id')
            ->select('close_case.id', 'close_case.case_name')
            ->distinct('close_case.case_name', 'close_case.id')
            ->orderBy('close_case.case_name')
            ->where('close_case.satker_id', auth()->user()->satker->kode_satker)
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    // case sudah input connected identity
    public static function getCloseCaseByObservConnect()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::
            join('observation_connected_identity', 'close_case.id', 'observation_connected_identity.case_id')
            ->select('close_case.id', 'close_case.case_name')
            ->distinct('close_case.case_name', 'close_case.id')
            ->orderBy('close_case.case_name')
            ->get();
        }else{  
            $case = CloseCase::
            join('observation_connected_identity', 'close_case.id', 'observation_connected_identity.case_id')
            ->select('close_case.id', 'close_case.case_name')
            ->distinct('close_case.case_name', 'close_case.id')
            ->orderBy('close_case.case_name')
            ->where('close_case.satker_id', auth()->user()->satker->kode_satker)
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