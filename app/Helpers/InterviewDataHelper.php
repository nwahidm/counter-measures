<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\OpenCase;
use App\Models\CaseProgresses;
use App\Models\Interview\InterviewJadwal;
use App\Models\Interview\InterviewHasil;



class InterviewDataHelper
{

    public static function getCloseCaseByResearchReport()
    {
        if (auth()->user()->user_roles == "superadmin") {
            $case = OpenCase::join('case_progresses', 'open_case.id', '=', 'case_progresses.case_id')
                ->where('case_progresses.percentage', '!=', '100')
                ->get();
        } else {
            $case = OpenCase::where('id_satker', auth()->user()->satker->id_satker)
                ->join('case_progresses', 'open_case.id', '=', 'case_progresses.case_id')
                ->where('case_progresses.percentage', '!=', '100')
                ->get();
        }
        
        
        $data = $case->map(function ($item, $key) {
            $data['id'] = $item->case_id;
            $data['text'] = 'Kasus ' . Str::limit(strip_tags($item->nama_kasus), 128);

            return $data;
        });

        return collect($data);
    }

    public static function getInterviewSchedule()
    {
        if(auth()->user()->user_roles == "superadmin"){
            $interview_scheduler = InterviewJadwal::all();
        }else{  
            $interview_scheduler = InterviewJadwal::where('satker_id', auth()->user()->satker->kode_satker)
            ->get();
        }
        
        $data = $interview_scheduler->map(function ($item, $key) {
            $person1 = $item->interviewer_name;
            $person2 = $item->source_person_name;
            $schedule = $item->interviewer_schedule->isoFormat('DD MMMM YYYY');

            $data['id'] = $item->id_interview_scheduler;
            $data['text'] = 'Wawancara ' . $person1 . ' dengan ' . $person2 . ' tanggal ' . $schedule;

            return $data;
        });

        return collect($data);
    }
    public static function getInterviewScheduleByCase($case_id = null)
    {
        $case_id = request()->query('case_id') ?? $case_id;

        if(auth()->user()->user_roles == "superadmin"){
            $interview_scheduler = InterviewJadwal::where('case_id', $case_id)->get();
        }else{  
            $interview_scheduler = InterviewJadwal::where('satker_id', auth()->user()->satker?->id_satker)
            ->where('case_id', $case_id)
            ->get();
        }
        
        $data = $interview_scheduler->map(function ($item, $key) {
            $person1 = $item->interviewer_name;
            $person2 = $item->source_person_name;
            $schedule = $item->interviewer_schedule->isoFormat('DD MMMM YYYY');

            $data['id'] = $item->id_interview_scheduler;
            $data['text'] = 'Wawancara ' . $person1 . ' dengan ' . $person2 . ' tanggal ' . $schedule;

            return $data;
        });
        
        return collect($data);
    }

    public static function getInterviewHasilByJadwal($id_jadwal = null){
        $id_jadwal = request()->query('id_jadwal') ?? $id_jadwal;

        if(auth()->user()->user_roles == "superadmin"){
            $interview_result = InterviewHasil::where('interview_scheduler_id', $id_jadwal)->get();
        }else{  
            $interview_result = InterviewHasil::where('satker_id', auth()->user()->satker->kode_satker)
            ->where('interview_scheduler_id', $id_jadwal)
            ->get();
        }
        
        $data = $interview_result->map(function ($item, $key) {
            
            
            $data['id'] = $item->id_interview_result;
            $data['text'] = Str::limit(strip_tags($item->keterangan), 128, '');

            return $data;
        });
        
        return collect($data);

    }
}
