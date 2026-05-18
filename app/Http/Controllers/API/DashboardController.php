<?php

namespace App\Http\Controllers\API;

use App\Models\OpenCase;
use App\Models\CloseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\DataTables\OpenCaseDashboard\Last5OpenCaseDataTable;
use App\DataTables\CloseCaseDashboard\Last5CloseCaseDataTable;
use App\DataTables\OpenCaseDashboard\Earlier5OpenCaseDataTable;
use App\DataTables\CloseCaseDashboard\Earlier5CloseCaseDataTable;

class DashboardController extends Controller
{
    public function open(Request $request)
    {
        $user = Auth::guard('api')->user();
        // data for pie chart
        $pieData = [];
        $pieLabel = [];
        $dataPieJumlah = DB::table('case_progresses')
                        ->join('open_case', 'open_case.id', 'case_progresses.case_id')
                        ->selectRaw('count(case_progresses.case_id) as jumlah, status')
                        ->join('master_satker', 'master_satker.id_satker', 'open_case.id_satker')
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('open_case.id_satker', '=', $user->id_satker)
                            ->where('master_satker.parent_id', '=', $user->id_satker);
                        })
                        ->groupBy('status')
                        ->orderBy('status', 'asc')
                        ->get();
        foreach ($dataPieJumlah as $value) {
            $pieLabel[] = $value->status;
            $pieData[] = $value->jumlah;
        }

        // data for statistic chart
        $statistic = [0,0,0,0,0,0,0,0,0,0,0,0];
        $dataJumlah = DB::table('open_case')
                        ->selectRaw("count(*) as jumlah, date_part('month', open_case.created_at)::int as bulan")
                        ->join('master_satker', 'master_satker.id_satker', 'open_case.id_satker')
                            ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                                $q->where('open_case.id_satker', '=', $user->id_satker)
                                ->where('master_satker.parent_id', '=', $user->id_satker);
                            })
                        ->whereRaw("date_part('year', open_case.created_at) = date_part('year', now())")
                        ->groupByRaw("date_part('month', open_case.created_at)")
                        ->get();
        
        foreach ($dataJumlah as $value) {
            if ($value->jumlah > 0) {
                $statistic[$value->bulan-1] = $value->jumlah;
            }
        }

        // get data latest and old
        $last5OpenCase = OpenCase::orderBy('open_case.created_at', 'desc')
                        ->limit(5)
                        ->join('master_satker', 'open_case.id_satker', '=', 'master_satker.id_satker')
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('open_case.id_satker', '=', $user->id_satker)
                              ->where('master_satker.parent_id', '=', $user->id_satker);
                        })
                        ->get();

        $earlier5OpenCase = OpenCase::orderBy('open_case.created_at', 'asc')
                        ->limit(5)
                        ->join('master_satker', 'open_case.id_satker', '=', 'master_satker.id_satker')
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('open_case.id_satker', '=', $user->id_satker)
                              ->where('master_satker.parent_id', '=', $user->id_satker);
                        })
                        ->get();


        // Buat response dengan data paginasi
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data Success',
            'timestamp' => floor(microtime(true) * 1000),
            'data' => [
                'pieData' => $pieData,
                'pieLabel' => $pieLabel,
                'statistic' => $statistic,
                'last5OpenCase' => $last5OpenCase,
                'earlier5OpenCase' => $earlier5OpenCase
            ]
            
        ]);
    }


    public function close(Request $request)
    {
        $user = Auth::guard('api')->user();
        // data for pie chart
        $pieData = [];
        $pieLabel = [];
        $dataPieJumlah = DB::table('case_close_progresses')
                        ->join('close_case', 'close_case.id', 'case_close_progresses.case_id')
                        ->selectRaw('count(case_close_progresses.case_id) as jumlah, status')
                        ->join('master_satker', 'master_satker.id_satker', 'close_case.satker_id')
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('close_case.satker_id', '=', $user->id_satker)
                              ->where('master_satker.parent_id', '=', $user->id_satker);
                        })
                        ->groupBy('status')
                        ->orderBy('status', 'asc')
                        ->get();
        foreach ($dataPieJumlah as $value) {
            $pieLabel[] = $value->status;
            $pieData[] = $value->jumlah;
        }

        // data for statistic chart
        $statistic = [0,0,0,0,0,0,0,0,0,0,0,0];
        $dataJumlah = DB::table('close_case')
                    ->selectRaw("count(*) as jumlah, date_part('month', close_case.created_at)::int as bulan")
                    ->whereRaw("date_part('year', close_case.created_at) = date_part('year', now())")
                    ->join('master_satker', 'master_satker.id_satker', 'close_case.satker_id')
                    ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                        $q->where('close_case.satker_id', '=', $user->id_satker)
                            ->where('master_satker.parent_id', '=', $user->id_satker);
                    })
                    ->groupByRaw("date_part('month', close_case.created_at)")
                    ->get();
        
        foreach ($dataJumlah as $value) {
            if ($value->jumlah > 0) {
                $statistic[$value->bulan-1] = $value->jumlah;
            }
        }

        // get data latest and old
        $last5CloseCase = CloseCase::orderBy('close_case.created_at', 'desc')
                        ->limit(5)
                        ->join('master_satker', 'close_case.satker_id', '=', 'master_satker.id_satker')
                        ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                            $q->where('close_case.satker_id', '=', $user->id_satker)
                                ->where('master_satker.parent_id', '=', $user->id_satker);
                        })
                        ->get();

        $earlier5CloseCase = CloseCase::orderBy('close_case.created_at', 'asc')
                            ->join('master_satker', 'close_case.satker_id', '=', 'master_satker.id_satker')
                            ->when(!$user->hasRole(['superadmin']), function($q) use ($user) {
                                $q->where('close_case.satker_id', '=', $user->id_satker)
                                    ->where('master_satker.parent_id', '=', $user->id_satker);
                            })
                            ->get();


        // Buat response dengan data paginasi
        return response()->json([
            "status" => Response::HTTP_OK,
            "statusMessage" => Response::$statusTexts[Response::HTTP_OK],
            "message" => 'Get Data Success',
            'timestamp' => floor(microtime(true) * 1000),
            'data' => [
                'pieData' => $pieData,
                'pieLabel' => $pieLabel,
                'statistic' => $statistic,
                'last5CloseCase' => $last5CloseCase,
                'earlier5CloseCase' => $earlier5CloseCase,
            ]
            
        ]);
    }


}
