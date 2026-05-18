<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Helpers\DataHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataTables\OpenCaseDashboard\Last5OpenCaseDataTable;
use App\DataTables\CloseCaseDashboard\Last5CloseCaseDataTable;
use App\DataTables\OpenCaseDashboard\Earlier5OpenCaseDataTable;
use App\DataTables\CloseCaseDashboard\Earlier5CloseCaseDataTable;

class DashboardController extends Controller
{

    public function home(Request $request)
    {
        $data = [
            'type' => $request->type ?? '',
            'label' => $request->label ?? 'Dashboard Utama'
        ];
        return view('backoffice.dashboard', compact('data'));
    }
    public function open(Request $request, Last5OpenCaseDataTable $lastOpenDataTable, Earlier5OpenCaseDataTable $earlierOpenDataTable)
    {
        $user = auth()->user();

        $bulan = $request->input('bulan', now()->format('Y-m'));

        $filterSatker = $request->input('satker') ?? $user->id_satker;
        if ($bulan) {
            $tanggalPertama = Carbon::parse($bulan)->startOfMonth()->toDateString();
            $tanggalTerakhir = Carbon::parse($bulan)->endOfMonth()->toDateString();
        } else {
            $tanggalPertama = Carbon::now()->startOfMonth()->toDateString();
            $tanggalTerakhir = Carbon::now()->endOfMonth()->toDateString();
        }
        
        $satker = DataHelper::getSatkerKejati();
        // data for pie chart
        $pieData = [];
        $pieLabel = [];
        $dataPieJumlah = DB::table('case_progresses')
                        ->join('open_case', 'open_case.id', 'case_progresses.case_id')
                        ->selectRaw('count(case_progresses.case_id) as jumlah, status')
                        ->join('master_satker', 'master_satker.id_satker', 'open_case.id_satker')
                        // ->when(!$user->hasRole(['superadmin']), function($q) use ($filterSatker) {
                        //     $q->where('open_case.id_satker', $filterSatker)
                        //       ->orWhere('master_satker.parent_id', $filterSatker);
                        // })
                        ->where('open_case.id_satker', $filterSatker)
                        ->whereRaw("case_progresses.created_at::date between '{$tanggalPertama}' and '{$tanggalTerakhir}'")
                        ->groupBy('status')
                        ->orderBy('status', 'asc')
                        ->get();
        foreach ($dataPieJumlah as $value) {
            $pieLabel[] = $value->status;
            $pieData[] = $value->jumlah;
        }

        // data for statistic chart
        // $statistic = [0,0,0,0,0,0,0,0,0,0,0,0];
        // $dataJumlah = DB::table('open_case')
        //             ->selectRaw("count(*) as jumlah, date_part('month', open_case.created_at)::int as bulan")
        //             // ->join('master_satker', 'master_satker.id_satker', 'open_case.id_satker')
        //             //     ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
        //             //         $q->where('open_case.id_satker', $user->id_satker)
        //             //           ->orWhere('master_satker.parent_id', $user->id_satker);
        //             //     })
        //             ->where('open_case.id_satker', $filterSatker)
        //             ->whereRaw("open_case.created_at::date between '{$tanggalPertama}' and '{$tanggalTerakhir}'")
        //             // ->whereRaw("date_part('year', open_case.created_at) = date_part('year', now())")
        //             ->groupByRaw("date_part('month', open_case.created_at)")
        //             ->get();
        
        // foreach ($dataJumlah as $value) {
        //     if ($value->jumlah > 0) {
        //         $statistic[$value->bulan-1] = $value->jumlah;
        //     }
        // }


        // Inisialisasi array statistik berdasarkan jumlah hari di bulan tersebut
        $statistic = [];

        // Membuat array tanggal dari $tanggalPertama hingga $tanggalTerakhir
        $allDates = [];
        $currentDate = $tanggalPertama; // Mulai dari tanggal pertama
        while (strtotime($currentDate) <= strtotime($tanggalTerakhir)) {
            $allDates[] = $currentDate; // Tambahkan ke array
            $statistic[$currentDate] = 0; // Inisialisasi statistik untuk setiap tanggal
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day')); // Increment tanggal
        }

        // Query untuk menghitung jumlah data per tanggal
        $dataJumlah = DB::table('open_case')
            ->selectRaw("count(*) as jumlah, to_char(open_case.created_at, 'YYYY-MM-DD') as tanggal")
            ->where('open_case.id_satker', $filterSatker)
            ->whereBetween('open_case.created_at', [$tanggalPertama, $tanggalTerakhir])
            ->groupByRaw("to_char(open_case.created_at, 'YYYY-MM-DD')")
            ->orderBy('tanggal', 'asc')
            ->get();

        // Memasukkan hasil query ke dalam array statistik
        foreach ($dataJumlah as $value) {
            $statistic[$value->tanggal] = $value->jumlah; // Update jumlah berdasarkan tanggal
        }


        return view('backoffice.open.dashboard', compact('bulan', 'filterSatker', 'statistic', 'satker', 'pieLabel', 'pieData', 'allDates'), [
            'lastOpenDataTable' => $lastOpenDataTable->html(),
            'earlierOpenDataTable' => $earlierOpenDataTable->html(),
        ]);
    }

    // last 5 open
    public function getLastOpen(Last5OpenCaseDataTable $lastOpenDataTable)
    {
        return $lastOpenDataTable->render('backoffice.open.dashboard');
    }

    public function getEarlierOpen(Earlier5OpenCaseDataTable $earlierOpenDataTable)
    {
        return $earlierOpenDataTable->render('backoffice.open.dashboard');
    }

    public function close(Request $request, Last5CloseCaseDataTable $lastCloseDataTable, Earlier5CloseCaseDataTable $earlierCloseDataTable)
    {
        $user = auth()->user();
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $filterSatker = $request->input('satker') ?? $user->id_satker;
        if ($bulan) {
            $tanggalPertama = Carbon::parse($bulan)->startOfMonth()->toDateString();
            $tanggalTerakhir = Carbon::parse($bulan)->endOfMonth()->toDateString();
        } else {
            $tanggalPertama = Carbon::now()->startOfMonth()->toDateString();
            $tanggalTerakhir = Carbon::now()->endOfMonth()->toDateString();
        }
        $satker = DataHelper::getSatkerKejati();
        // data for pie chart
        $pieData = [];
        $pieLabel = [];
        $dataPieJumlah = DB::table('case_close_progresses')
                        ->join('close_case', 'close_case.id', 'case_close_progresses.case_id')
                        ->selectRaw('count(case_close_progresses.case_id) as jumlah, status')
                        ->join('master_satker', 'master_satker.id_satker', 'close_case.satker_id')
                        // ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                        //     $q->where('close_case.satker_id', $user->id_satker)
                        //       ->orWhere('master_satker.parent_id', $user->id_satker);
                        // })
                        ->where('close_case.satker_id', $filterSatker)
                        ->whereRaw("case_close_progresses.created_at::date between '{$tanggalPertama}' and '{$tanggalTerakhir}'")
                        ->groupBy('status')
                        ->orderBy('status', 'asc')
                        ->get();
        foreach ($dataPieJumlah as $value) {
            $pieLabel[] = $value->status;
            $pieData[] = $value->jumlah;
        }

        // data for statistic chart
        // $statistic = [0,0,0,0,0,0,0,0,0,0,0,0];
        // $dataJumlah = DB::table('close_case')
        //             ->selectRaw("count(*) as jumlah, date_part('month', close_case.created_at)::int as bulan")
        //             ->whereRaw("date_part('year', close_case.created_at) = date_part('year', now())")
        //             ->join('master_satker', 'master_satker.id_satker', 'close_case.satker_id')
        //             ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
        //                 $q->where('close_case.satker_id', $user->id_satker)
        //                     ->orWhere('master_satker.parent_id', $user->id_satker);
        //             })
        //             ->groupByRaw("date_part('month', close_case.created_at)")
        //             ->get();
        
        // foreach ($dataJumlah as $value) {
        //     if ($value->jumlah > 0) {
        //         $statistic[$value->bulan-1] = $value->jumlah;
        //     }
        // }

        $statistic = [];

        // Membuat array tanggal dari $tanggalPertama hingga $tanggalTerakhir
        $allDates = [];
        $currentDate = $tanggalPertama; // Mulai dari tanggal pertama
        while (strtotime($currentDate) <= strtotime($tanggalTerakhir)) {
            $allDates[] = $currentDate; // Tambahkan ke array
            $statistic[$currentDate] = 0; // Inisialisasi statistik untuk setiap tanggal
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day')); // Increment tanggal
        }

        // Query untuk menghitung jumlah data per tanggal
        $dataJumlah = DB::table('close_case')
            ->selectRaw("count(*) as jumlah, to_char(close_case.created_at, 'YYYY-MM-DD') as tanggal")
            ->where('close_case.satker_id', $filterSatker)
            ->whereBetween('close_case.created_at', [$tanggalPertama, $tanggalTerakhir])
            ->groupByRaw("to_char(close_case.created_at, 'YYYY-MM-DD')")
            ->orderBy('tanggal', 'asc')
            ->get();

        // Memasukkan hasil query ke dalam array statistik
        foreach ($dataJumlah as $value) {
            $statistic[$value->tanggal] = $value->jumlah; // Update jumlah berdasarkan tanggal
        }


        return view('backoffice.close.dashboard', compact('bulan', 'filterSatker', 'satker', 'statistic', 'pieLabel', 'pieData', 'allDates'), [
            'lastCloseDataTable' => $lastCloseDataTable->html(),
            'earlierCloseDataTable' => $earlierCloseDataTable->html(),
        ]);
    }

    // last 5 close
    public function getLastClose(Last5CloseCaseDataTable $lastCloseDataTable)
    {
        return $lastCloseDataTable->render('backoffice.close.dashboard');
    }

    public function getEarlierClose(Earlier5CloseCaseDataTable $earlierCloseDataTable)
    {
        return $earlierCloseDataTable->render('backoffice.close.dashboard');
    }

}
