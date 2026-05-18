<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\CloseCase;
use App\Models\CaseCloseProgresses;
use App\Models\Tailing\TailingPemahamanPerilaku;
use App\Models\Tailing\TailingTargetOperasi;

class TailingDataHelper
{

    public static function getCloseCaseByExplorationReport()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.exploration_laporan', '1')
            ->get();
        }else{  
            $case = CloseCase::where('satker_id', auth()->user()->satker->kode_satker)
            >join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.exploration_laporan', '1')
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->case_id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }


    public static function getCloseCaseByTailingPemahamanPerilaku()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $case = CloseCase::join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.tailing_pemahaman_perilaku', '1')
            ->get();
        }else{  
            $case = CloseCase::where('satker_id', auth()->user()->satker->kode_satker)
            >join('case_close_progresses', 'close_case.id', '=', 'case_close_progresses.case_id')
            // ->where('case_close_progresses.tailing_pemahaman_perilaku', '1')
            ->get();
        }
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->case_id;
            $data['text'] = Str::limit(strip_tags($item->case_name), 128);

            return $data;
        });

        return collect($data);
    }

    public static function getTailingPemahamanPerilakubyCaseId($case_id = null){
        $case_id = request()->query('case_id') ?? $case_id;

        if (!$case_id) {
            return [];
        } else {
            $observation_collection_info = TailingPemahamanPerilaku::where('case_id', $case_id)->get();
            $data_observation_collection_info = $observation_collection_info->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] =  "Pemahaman Perilaku - " . $item->target_name ;
                return $data;
            });
            return collect($data_observation_collection_info);
        }
    }

    public static function getTailingTargetOperasibyTailingPemahamanPerilakuId($tailing_pemahaman_perilaku_id = null){
        $tailing_pemahaman_perilaku_id = request()->query('tailing_pemahaman_perilaku_id') ?? $tailing_pemahaman_perilaku_id;

        if (!$tailing_pemahaman_perilaku_id) {
            return [];
        } else {
            $observation_collection_info = TailingTargetOperasi::where('tailing_pemahaman_perilaku_id', $tailing_pemahaman_perilaku_id)->get();
            $data_observation_collection_info = $observation_collection_info->map(function ($item, $key) {
                $data['id'] = $item->id;
                $data['text'] =  "Target Operasi - " . $item->rencana_target_operasi ;
                return $data;
            });
            return collect($data_observation_collection_info);
        }
    }
}