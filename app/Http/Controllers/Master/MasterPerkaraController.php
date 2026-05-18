<?php

namespace App\Http\Controllers\Master;

use Exception;
use App\Models\Perkara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\DataTables\Master\MasterPerkaraDataTable;

class MasterPerkaraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterPerkaraDataTable $dataTable)
    {
        return $dataTable->render('backoffice.master.perkara.index');
    }
}
