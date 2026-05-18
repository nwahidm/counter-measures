<?php

use Carbon\Carbon;
use App\Models\App;
use App\Models\City;
use App\Models\SLide;
use Firebase\JWT\JWT;
use App\Models\Client;
use App\Models\Satker;
use App\Models\District;
use App\Models\Penduduk;
use App\Models\Province;
use App\Helpers\DataHelper;
use App\Helpers\MenuHelper;
use App\Models\Application;
use App\Models\MasterTahun;
use App\Models\SubDistrict;
use Illuminate\Support\Str;
use App\Models\MasterCapres;
use App\Models\MasterPartai;
use App\Models\MasterSatker;
use Ryuamy\WAQiscus\Message;
use App\Models\MasterWilayah;
use App\Models\EsignVerifyData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Exception\GuzzleException;

function getAvatar()
{
    $avatar = auth()->user()->profile_photo_path;
    if ($avatar != null) {
        return asset('storage/user/' . $avatar);
    }
    return asset('assets/images/ic_user.svg');
}

function tglIndo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

function bulanIndo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);
 
	return $bulan[ (int)$pecahkan[1] ];
}

function hummanDateFormat($date)
{
    $dateFormatted = Carbon::parse($date)->format('d-m-Y H:i');
    return $dateFormatted;
}

function retrieveAllTable()
{
    $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
    return $tables;
}

function getPrimaryKeyColumnFromTable($table)
{
    $indexes = DB::connection()->getDoctrineSchemaManager()->listTableIndexes($table);
    $columns = $indexes['primary']->getColumns();

    return $columns;
}

function indonesianMonth($monthNum)
{
    $month = array(
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    );

    return $month[$monthNum] ?? '-';
}

function sanitizeByUnderscore(string $string)
{
    return str_replace(" ", "_", preg_replace('/[^a-zA-Z0-9 ]/', '', $string));
}

function hummanReadableFile($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
    return round($bytes, 2) . ' ' . $units[$i];
}

function langSetup()
{
    $lang = session()->has('lang') ? session()->get('lang') : config('app.locale');
    return $lang;
}

function loadAsset($path, $filename)
{
    return asset("storage/{$path}/{$filename}");
}

function dateSpecificFormat($data, $format = 'Y-M-d H:i:s')
{
    $stringDate = Carbon::parse($data)->format($format);

    return $stringDate;
}

function menuActive($paths)
{

    $isActive = '';

    foreach ($paths as $val) {
        if (request()->is($val)) {
            $isActive = 'active';
            break;
        }
    }

    return $isActive;
}

function menuActiveByRoute($path)
{
    if (Route::is($path)) {
        return 'active';
    }
    return '';
}

function collapseShow($paths)
{

    $collapse = '';

    foreach ($paths as $val) {
        if (request()->is($val)) {
            $collapse = 'show';
            break;
        }
    }

    return $collapse;
}

function ariaTrue($paths)
{

    $aria = "false";

    foreach ($paths as $val) {
        if (request()->is($val)) {
            $aria = "true";
            break;
        }
    }

    return $aria;
}

function badgeStatus($status)
{
    $badge = "";
    switch (strtolower($status)) {
        case 1:
            $badge = '<span class="badge badge-light-success">Active</span>';
            break;
        default:
            $badge = '<span class="badge badge-light-dark">Non Active</span>';
            break;
    }
    return $badge;
}

function badgeRole($role)
{
    $badge = "";
    switch (strtolower($role)) {
        case "superadmin":
            $badge = '<span class="badge badge-light-primary">' . $role . '</span>';
            break;
        case "admin":
            $badge = '<span class="badge badge-light-success">' . $role . '</span>';
            break;
        case "user":
            $badge = '<span class="badge badge-light-warning">' . $role . '</span>';
            break;
        default:
            $badge = '<span class="badge badge-light-dark">' . $role . '</span>';
            break;
    }
    return $badge;
}

function loadLogoApp($logo)
{

    if (filter_var($logo, FILTER_VALIDATE_URL) !== false) {
        return $logo;
    } else {
        return empty($logo) ? asset('image/default.jpg') : asset('storage/logo/' . $logo);
    }
}

function optApp($apps, $value = null)
{
    $opt = '<option value=""></option>';
    foreach ($apps as $app) {
        $opt .= '<option value="' . $app->code . '" ' . ($value == $app->code ? 'selected' : '') . '>' . $app->name . '</option>';
    }

    return $opt;
}

function optMethod($methods, $value = null)
{
    $opt = '<option value=""></option>';
    foreach ($methods as $method) {
        $opt .= '<option value="' . $method . '" ' . ($value == $method ? 'selected' : '') . '>' . $method . '</option>';
    }

    return $opt;
}

function listMethod()
{
    return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];
}

function optLogLevel($levels, $value = null)
{
    $opt = '<option value=""></option>';
    foreach ($levels as $level) {
        $opt .= '<option value="' . $level . '" ' . ($value == $level ? 'selected' : '') . '>' . strtoupper($level) . '</option>';
    }

    return $opt;
}

function listLogLevel()
{
    return ['info', 'warning', 'error', 'severe', 'critical'];
}

function optHttpStatus($httpStatus, $value = null)
{
    $opt = '<option value=""></option>';
    foreach ($httpStatus as $status) {
        $opt .= '<option value="' . $status . '" ' . ($value == $status ? 'selected' : '') . '>' . $status . '</option>';
    }

    return $opt;
}

function listHttpStatus()
{
    return ['200', '201', '302', '401', '403', '404', '500', '502', '503'];
}

function menuHere($paths, $pathsnone = [])
{
    $isActive = '';

    foreach ($paths as $val) {
        if (request()->is($val)) {
            if (empty($pathsnone)) {
                $isActive = 'show here';
                break;
            }
            foreach($pathsnone as $none) {
                if (!request()->is($none)) {
                    $isActive = 'show here';
                    break;
                }
            }
        }
    }

    return $isActive;
}

function menuLinkHere($paths, $pathsnone = [])
{

    $isActive = '';

    foreach ($paths as $val) {
        if (request()->is($val)) {
            if (empty($pathsnone)) {
                $isActive = 'active';
                break;
            }
            foreach($pathsnone as $none) {
                if (!request()->is($none)) {
                    $isActive = 'active';
                    break;
                }
            }
        }
    }

    return $isActive;
}

function optSatker($value = null)
{
    $opt = '<option value="">-Pilih-</option>';
    $satkers = MasterSatker::select('id_satker', 'nama_satker')->orderBy('tipe_satker')->get();
    foreach ($satkers as $satker) {
        $opt .= '<option value="' . $satker->id_satker . '" ' . ($value == $satker->id_satker ? 'selected' : '') . '>' . $satker->nama_satker . '</option>';
    }

    return $opt;
}

function optStatus($value = null)
{
    $data = '
        [
            {
                "value": "",
                "label": "All Status"
            },
            {
                "value": "0",
                "label": "Waiting Process"
            },
            {
                "value": "1",
                "label": "In Process"
            },
            {
                "value": "2",
                "label": "Success"
            },
            {
                "value": "3",
                "label": "Failed"
            }
        ]';

    $opt = '';
    $datas = json_decode($data, true);
    foreach ($datas as $data) {
        $opt .= '<option value="' . $data['value'] . '" ' . ($value == $data['value'] ? 'selected' : '') . '>' . $data['label'] . '</option>';
    }

    return $opt;
}

function eventDataType($type) {
    $dataSidang = ['Berita Acara Sidang', 'Berita Acara Pemeriksaan Saksi', 'Berita Acara Pemeriksaan Terdakwa', 'Berita Acara Pemeriksaan Korban', 'Berita Acara Pemeriksaan Barang Bukti'];
    $dataNonSidang = ['Perekaman Percakapan Bebas'];

    if ($type == 'sidang') {
        return $dataSidang;
    }

    return $dataNonSidang;
}

function badgeStatusInternal($status, $remark) {

    $badge = '';
    switch ($status) {
        case "0" :
            $badge = '<span class="badge badge-light">Waiting Process</span>';
            break;
        case "1" :
            $badge = '<span class="badge badge-primary">In Process</span>';
            break;
        case "2" :
            $badge = '<span class="badge badge-success">Success</span>';
            break;
        case "3" :
            $badge = '<span class="badge badge-danger">Failed <i class="fas fa-exclamation-circle ms-4 fs-7 text-white" data-bs-toggle="tooltip" title="'.$remark.'"></i></span>';
            break;
        default :
            $badge = '<span class="badge badge-secondary">Unprocess</span>';
            break;
    }

    return $badge;
}

function loadFileApi($filename) {
    return route('event-data.getFile', ['filename' => urlencode($filename)]);
}

function initializeName($name) {
    if (empty($name)) {
        return '-';
    }

    return strtoupper(substr($name, 0, 1));
}

function loadVisualization($type) {
    $type = empty($type) ? config('constant.metabase.url_general') : \Crypt::decryptString($type);
    return signDashboardLink($type);
}

function loadSubVisualization($type, $param) {
    return signDashboardLink($type, $param);
}

function recaptchaValidation($token, $ip) {
    $validateRecaptcha = Http::asForm()->post(config('services.google.gcaptcha_api').'/siteverify', [
        'secret' => config('services.google.gcaptcha_secretkey'),
        'response' => $token,
        'remoteip' => $ip
    ]);

    if (!$validateRecaptcha->successful()) return false;

    $responseCaptcha = $validateRecaptcha->json();
    if (isset($responseCaptcha['success']) && !$responseCaptcha['success']) return false;

    return true;
}

function signDashboardLink($dashboardId, $param = null) {
    $dashboardIdFix = (int) $dashboardId;
    $params = $param ? (object) $param : (object) [];
    $payload = [
        "resource" => (object) ["dashboard" => $dashboardIdFix],
        "params" => $params,
        "exp" => time() + (10 * 60) // 10 minute expiration
    ];
    
    $token = JWT::encode($payload, config('constant.metabase.secret'), 'HS256');

    $url = config('constant.metabase.url') . "/embed/dashboard/" . $token . "#bordered=false&titled=false";

    return $url;
}

function optTahun2() {
    $tahun = MasterTahun::all();
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($tahun as $row) {
        $opt .= '<option value="'.$row->kode.'">'.$row->nama.'</option>';
    }
    return $opt;
}

function optTahun($data) {
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($data as $row) {
        $opt .= '<option value="'.$row->kode.'">'.$row->nama.'</option>';
    }
    return $opt;
}

function optTahunKirka($data) {
    $opt = ''; // Initialize the options variable

    foreach ($data as $index => $row) {
        $value = $row->kode;
        $nama = $row->nama;

        // Determine whether to set the 'selected' and 'disabled' attributes
        $selected = ($index === 0) ? 'selected' : '';
        $disabled = ($index === 0) ? '' : 'disabled';

        // Append each option to the $opt string
        $opt .= '<option value="' . $value . '" ' . $selected . ' ' . $disabled . '>' . $nama . '</option>';
    }

    return $opt;
}

function optCapres() {
    $capres = MasterCapres::all();
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($capres as $row) {
        $opt .= '<option value="'.$row->id.'">'.$row->nomor_urut.' - '.$row->nama.'</option>';
    }
    return $opt;
}

function optPartai() {
    $capres = MasterPartai::all();
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($capres as $row) {
        $opt .= '<option value="'.$row->id.'">'.$row->nama.'</option>';
    }
    return $opt;
}

function optWilayah($kodeSatker, $needChild = -1, $level = null) {
    if ($level != null) {
        $level = explode(".", $level);
    }
    $wilayah = DataHelper::listWilayah($kodeSatker, $needChild, $level);
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($wilayah as $wil) {
        $opt .= '<option value="'.$wil->id_wilayah.'">'.$wil->nama.'</option>';
    }
    return $opt;
}

function optAGHT() {
    $aghts = DataHelper::jenisAGHT();
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($aghts as $key => $row) {
        $opt .= '<option value="'.$key.'">'.$row.'</option>';
    }
    return $opt;
}

function optStatusAGHT() {
    $aghts = DataHelper::jenisStatusAGHT();
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($aghts as $key => $row) {
        $opt .= '<option value="'.$key.'">'.$row.'</option>';
    }
    return $opt;
}

function optTahapLogsitik() {
    $aghts = DataHelper::tahapLogistik();
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($aghts as $key => $row) {
        $opt .= '<option value="'.$key.'">'.$row.'</option>';
    }
    return $opt;
}

function number_unformat($value) {
    return str_replace(',','',$value);
}

function indonesianDate($date, $format = 'dddd, D MMMM Y') {
	return Carbon::parse($date)->isoFormat($format);
}

function optSatkerByTipeSatker() {
    $satker = auth()->user()->satker;
    $tipeSatker = (int) $satker->tipe_satker;

    return optSatkerWithChild($satker->kode_satker, 1, range($tipeSatker, 4));
}

function optSatkerWithChild($kodeSatker, $needChild = -1, $level = null) {
    $satkers = DataHelper::listSatker($kodeSatker, $needChild, $level);
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($satkers as $row) {
        $opt .= '<option value="'.$row->id_satker.'" data-kodesatker="'.$row->kode_satker.'">'.$row->nama_satker.'</option>';
    }
    return $opt;
}

function optStatusJalanDaerah() {
    $aghts = DataHelper::jenisStatusJalanDaerah();
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($aghts as $key => $row) {
        $opt .= '<option value="'.$key.'">'.$row.'</option>';
    }
    return $opt;
}

function optMetodeJalanDaerah() {
    $aghts = DataHelper::jenisMetodeJalanDaerah();
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($aghts as $key => $row) {
        $opt .= '<option value="'.$key.'">'.$row.'</option>';
    }
    return $opt;
}

function optStatusProgresJalanDaerah() {
    $aghts = DataHelper::jenisStatusProgresJalanDaerah();
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($aghts as $key => $row) {
        $opt .= '<option value="'.$key.'">'.$row.'</option>';
    }
    return $opt;
}

function percentageValue($value1, $value2) {
    return number_unformat($value1) / number_unformat($value2) * 100;
}

function statusBarangLogistik($value1, $value2) {
    $value1 = number_unformat($value1);
    $value2 = number_unformat($value2);

    if ($value1 < $value2) {
        return "LEBIH";
    }
    else if ($value1 > $value2) {
        return "KURANG";
    } else {
        return "SESUAI";
    }
}

function optStatusLogistik() {
    $aghts = DataHelper::jenisStatusLogistik();
    $opt = '<option value="">Silakan Pilih</option>';
    foreach ($aghts as $key => $row) {
        $opt .= '<option value="'.$key.'">'.$row.'</option>';
    }
    return $opt;
}

function optSatkerByRole()
{
    $user = auth()->user();
    $opt = '<option value="">-Pilih-</option>';
    $satkers = MasterSatker::when(!$user->hasRole('superadmin'), function($q) use ($user) {
                                $q->where('master_satker.id_satker', $user->id_satker);
                            })
                            ->select('id_satker', 'nama_satker')
                            ->orderBy('tipe_satker')
                            ->get();
                            
    foreach ($satkers as $satker) {
        $opt .= '<option value="' . $satker->id_satker . '">' . $satker->nama_satker . '</option>';
    }

    return $opt;
}

function fetchClient($clientId) {
    $value = Cache::remember('client_'.$clientId, 3600*24, function () use ($clientId) {
        return Client::find($clientId);
    });

    return $value;
}

function fetchClientByUsername($clientUsername) {
    $value = Cache::remember('client_'.$clientUsername, 3600*24, function () use ($clientUsername) {
        return Client::where('client_username', $clientUsername)->first();
    });

    return $value;
}

function isISO8601($timestamp) {
    $dateTime = DateTime::createFromFormat(DateTime::ISO8601, $timestamp);
    return ($dateTime !== false && !empty($dateTime->errors));
}

function sendNotifWA($telp, $message) {
	// Wa Nawasena
	
	$numberKeys = array(
		'inteliz4',
		'inteliz3'
	);

	$randomNumber = array_rand($numberKeys);
	$numberKey = $numberKeys[$randomNumber];


    $noHp = $telp;
    if(substr($telp, 0, 3) == '+62'){
        $noHp = substr($telp, 1);
    }elseif(substr($telp, 0, 1) == '0'){
        $noHp = '62'.substr($telp, 1);
    }

    $url = 'https://waotp.kejaksaanri.id/api/sendtext';
    
    $response = Http::post($url, [

        'sessions' => $numberKey,
        'target' => $noHp,
        'message' => $message
        
        ]
    );

    $result = json_decode($response);

    return response()->json($result);
}

function CreateToPendudukHelper($data) {
	foreach($data as $value){
        $insert=Penduduk::firstOrCreate(['NIK' =>  $value->NIK],[
            'NO_KK' => $value->NO_KK,
            'NAMA_LGKP' => $value->NAMA_LGKP,
            'TMPT_LHR' => $value->TMPT_LHR,
            'TGL_LHR' => $value->TGL_LHR,
            'PROP_NAME' => $value->PROP_NAME,
            'KAB_NAME' => $value->KAB_NAME,
            'KEC_NAME' => $value->KEC_NAME,
            'KEL_NAME' => $value->KEL_NAME,
            'NO_RT' => $value->NO_RT,
            'NO_RW' => $value->NO_RW,
            'ALAMAT' => $value->ALAMAT,
            'NIK_IBU' => $value->NIK_IBU,
            'NAMA_LGKP_IBU' => $value->NAMA_LGKP_IBU,
            'AGAMA' => $value->AGAMA,
            'STATUS_KAWIN' => $value->STATUS_KAWIN,
            'JENIS_PKRJN' => $value->JENIS_PKRJN,
            'JENIS_KLMIN' => $value->JENIS_KLMIN,
            'PDDK_AKH' => $value->PDDK_AKH,
            'STAT_HBKEL' => $value->STAT_HBKEL,
            'NO_PROP' => $value->NO_PROP,
            'NO_KAB' => $value->NO_KAB,
            'NO_KEC' => $value->NO_KEC,
            'NO_KEL' => $value->NO_KEL,
            'NIK' => $value->NIK,
            'FOTO' => $value->FOTO,
        ]);
    }

    return $insert;
}

function whatsappNotificationBlast($whatsapp, $nama, $pesan, $template) {
    $Session = new \Illuminate\Support\Facades\Session;
    $qiscus_auth = new \Ryuamy\WAQiscus\Authentication();

    // unset($_SESSION['qiscus_auth']);

    if (!session('qiscus_auth')) {
        $get_token_qiscus = $qiscus_auth->getToken(
            env('QISCUS_APP_ID'),
            [ 'email' => env('QISCUS_ACCOUNT_USERNAME'), 'password' => env('QISCUS_ACCOUNT_PASSWORD') ]
        );

        if( isset($get_token_qiscus->data->user->authentication_token) ) {
            $qiscus_auth = $get_token_qiscus->data->user->authentication_token;
            $Session::put('qiscus_auth', $qiscus_auth);
        }
    }

    if (!session('qiscus_auth')) {
        return 'Invalid Qiscus Token';
    }

    $tokenQiscus = session('qiscus_auth');

    $qiscus_language_id = 'id';
    if(env('QISCUS_TESTING') === true) {
        $whatsapp = env('QISCUS_TESTING_NUMBER');
    } else {
        if (substr($whatsapp, 0, 1) === '0') {
            $whatsapp = '62'.substr($whatsapp, 1);
        } else if (substr($whatsapp, 0, 1) === '+') {
            $whatsapp = substr($whatsapp, 1);
        }
    }

    $whatsapp_components = array(
        array(
            'type' => 'header',
            'parameters' => array(
                array(
                    'type' => 'text',
                    'text' => $nama
                )
            )
        ),
        array(
            'type' => 'body',
            'parameters' => array(
                array(
                    'type' => 'text',
                    'text' => $pesan
                )
            )
        ),
    );
    
    try {
        $e = Message::template(
            env('QISCUS_APP_ID'),
            $tokenQiscus,
            env('QISCUS_CHANNEL_ID'),
            [
                'whatsapp_number' => $whatsapp,
                'template_namespace' => env('QISCUS_TEMPLATE_NAMESPACE'),
                'template_name' => $template,
                'language_code' => $qiscus_language_id,
                'components' => $whatsapp_components
            ]
        );
        
    }catch(Exception $e){
        return $e;
    }
    
    return $e;
}

function whatsappNotification($whatsapp, $nama, $pesan, $template) {

    if(env('QISCUS_TESTING') === true) {
        $whatsapp = env('QISCUS_TESTING_NUMBER');
    } else {
        if (substr($whatsapp, 0, 1) === '0') {
            $whatsapp = '62'.substr($whatsapp, 1);
        } else if (substr($whatsapp, 0, 1) === '+') {
            $whatsapp = substr($whatsapp, 1);
        }
    }

    try {
        // $response = $client->request('POST', 'https://omnichannel.qiscus.com/whatsapp/v1/fivnv-kwu3rf6yimhppdw/4635/messages', [
        //     'headers' => [
        //         'Qiscus-App-Id' =>  env('QISCUS_APP_ID'),
        //         'Qiscus-Secret-Key' => env('QISCUS_APP_SECRET'),
        //         'Content-Type' => 'application/json',
        //     ],
        //     'body' => json_encode([
        //         'recipient_type' => 'individual',
        //         'to' => $whatsapp,
        //         'type' => 'text',
        //         'text' => [
        //             'body' => $pesan,
        //         ],
        //     ]),
        // ]);

        $dataKiriman = json_encode(array(
            "recipient_type" => "individual",
            "to" => $whatsapp,
            "type" => "text",
            "text" => array(
                "body" => $pesan
            )
        ));

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://omnichannel.qiscus.com/whatsapp/v1/fivnv-kwu3rf6yimhppdw/4635/messages',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $dataKiriman,
        CURLOPT_HTTPHEADER => array(
            'Qiscus-App-Id: fivnv-kwu3rf6yimhppdw',
            'Qiscus-Secret-Key: a4067785f559664102f3c40caca1ef0b',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    
   
    } catch (Exception $e) {

    }
}

function splitKodeWilayah($string, $delimiter = '.') {
    if (strlen($string) <= 6) {
        $chunks = str_split($string, 2);
    } else {
        $chunks = array_merge(
            str_split(substr($string, 0, 6), 2),
            str_split(substr($string, 6), 4)
        );
    }

    $result = implode($delimiter, $chunks);
    return $result;
}

function trimOnlyDigit($string) {
    return preg_replace("/[^0-9]/", "", $string);
}

function phoneNumber($string) {

    if (Str::startsWith($string, '62')) {
        return $string;
    }
    else if (Str::startsWith($string, '0')) {
        return $string;
    }
    return '0'.$string;
}

function accessTokenInteliz($body) {
    $timestamp =  Carbon::now()->format("c");
    $clientId = 'INTEL_001';
    $clientKey = '7b27b1a8-33e2-4224-b69e-81aec958d5fc';
    $username = 'INTEL_USER';
    $password = 'Inte7P@sSw0rd';
    $messagePayload = 'POST'.md5(json_encode($body)).'/api/v1/authentication/create-token'.$timestamp;

    $signatureServer = base64_encode(hash_hmac('sha512', $messagePayload, md5($clientKey), true));

    return [
        'X-Api-Client-Id' => $clientId,
        'X-Api-Timestamp' => $timestamp,
        'X-Api-Signature' => $signatureServer
    ];
}

function serviceSignatureInteliz($method, $url, $payload, $accessToken) {
    $timestamp =  Carbon::now()->format("c");
    $clientId = 'INTEL_001';
    $clientKey = $accessToken;
    $username = 'INTEL_USER';
    $password = 'Inte7P@sSw0rd';
    
    if ($method == "GET") { 
        $messagePayload = $method.$url.$timestamp;
    } else {
        $messagePayload = $method.md5(json_encode($payload)).$url.$timestamp;
    }
    
    $signatureServer = base64_encode(hash_hmac('sha512', $messagePayload, md5($clientKey), true));

    return [
        'X-Api-Client-Id' => $clientId,
        'X-Api-Timestamp' => $timestamp,
        'X-Api-Signature' => $signatureServer,
        'Authorization'   => "Bearer {$accessToken}"
    ];
}

function getKongTokenMySimkari(){
    $param = array (
        'email' => env('API_MAIL', 'mataelang@kejaksaan.go.id'),
        'password' => env('API_PASS', 'm4t4el4n9*!!'),
    );
    $param = http_build_query($param);
    $url = 'https://api-dev.kejaksaan.go.id/token/api/auth/login?'.$param;
    $response = Http::post($url);
    if ($response->successful()) {
        $result = json_decode($response->body());
        return $token = $result->access_token;			
    }else{
        return false;
    }
}

function getPimpinanMySimkari($id_satker){

    $param = array (
        'email' => env('API_MAIL', 'mataelang@kejaksaan.go.id'),
        'password' => env('API_PASS', 'm4t4el4n9*!!'),
    );
    $param = http_build_query($param);
    $url = 'https://api-dev.kejaksaan.go.id/token/api/auth/login?'.$param;
    $response = Http::post($url);
    if ($response->successful()) {
        $result = json_decode($response->body());
        $token = $result->access_token;		

        $url = 'https://api-dev.kejaksaan.go.id/mysimkariv2/api/v2/web/get-pimpinan/satker/'.$id_satker;
        $response = Http::withToken($token)->get($url);
        return $dataRespon = $response['data'];
    }else{
        return false;
    }

}
function GetToken(){
	//get token jwt
	$token = "";
	$param = array (
				'email' => 'sipede@kejaksaan.go.id',
				'password' => '5p3d32022!!?',
			);
	$param = http_build_query($param);
	$url = 'https://api-dev.kejaksaan.go.id/token/api/auth/login?'.$param;
	$response = Http::post($url);
	if ($response->successful()) {
		$result = json_decode($response->body());
		$token = $result->access_token;			
	}
	
	return $token;
}

function footerQrContent($hostMedia, $path, $filename, $id) {
	$year = (int) date('Y');

	if ($year < 2023) {
		return $hostMedia . $path . $filename;
	}
	
	return route('document-index.check', ['id' => !empty($id) ? $id : $filename]);
}

function createEsignVerifyData($source_app, $doc_id, $doc_number, $doc_filename, $doc_date, $doc_subject, $doc_signed_at, $doc_signed_by, $doc_signed_job, $doc_satker, $doc_content = null) {
	try {
		EsignVerifyData::updateOrCreate([
			'source_app' => $source_app,
			'doc_id' => $doc_id,
			'doc_number' => $doc_number ?? '-'
		], 
		[
			'doc_filename' => $doc_filename,
			'doc_date' => $doc_date,
			'doc_subject' => $doc_subject,
			'doc_signed_at' => $doc_signed_at,
			'doc_signed_by' => $doc_signed_by ?? '-',
			'doc_signed_job' => $doc_signed_job ?? '-',
            'id_satker' => $doc_satker,
			'doc_content' => $doc_content
		]);
        // return "ok";
        // DB::commit();
	}
	catch (\Exception $ex) {
        // return $doc_signed_by;
		Log::info("Failed createEsignVerifyData : {$ex->getMessage()}");
	}
}

function optKategoriPemilu($value = null)
{
    $categories = ['PILPRES','PILEG','PILKADA','PILKADES'];
    $opt = '<option value=""></option>';
    foreach ($categories as $category) {
        $opt .= '<option value="' . $category . '" ' . ($value == $category ? 'selected' : '') . '>' . $category . '</option>';
    }

    return $opt;
}

function listProvinsi()
{
    $data = MasterWilayah::where('level', 'PROVINSI')
                        ->select('kode as id', 'nama as text')
                        ->get();

    return $data;
}

function listKota($provinsi)
{
    $data = MasterWilayah::where('level', 'KABUPATEN/KOTA')
                        ->where('kode', 'ilike', "{$provinsi}%")
                        ->select('kode as id', 'nama as text')
                        ->get();

    return $data;
}

function listWilayah($type)
{
    $data = MasterWilayah::whereIn('level', $type)
                        ->select('id_wilayah as id', 'nama as text')
                        ->get();

    return $data;
}

function optLevelWilayah($value = null)
{
    $categories = ['NEGARA','PROVINSI','KABUPATEN/KOTA','KECAMATAN', 'KELURAHAN/DESA'];
    $opt = '<option value=""></option>';
    foreach ($categories as $category) {
        $opt .= '<option value="' . $category . '" ' . ($value == $category ? 'selected' : '') . '>' . $category . '</option>';
    }

    return $opt;
}

function optSatkerWithChildV2($needChild = -1, $optionPrefix = null, $level = null, $typeId = 'kode_satker') {
    $satker = auth()->user()->satker;
    if ($level == null) {
        $level = range((int) $satker->tipe_satker, 4);
    }
    $satkers = DataHelper::listSatker2($satker->kode_satker, $needChild, $level);

    $opt = '';
    if ($optionPrefix == 'all') {
        $opt = '<option value="all">Semua</option>';
    } else if ($optionPrefix == 'select') {
        $opt = '<option value="">Silakan Pilih</option>';
    }
    
    foreach ($satkers as $row) {
        if ($typeId == 'kode_satker') {
            $id = $row->kode_satker;
        } else {
            $id = $row->id_satker;
        }
        $opt .= '<option value="'.$id.'" data-kodesatker="'.$row->kode_satker.'">'.$row->nama_satker.'</option>';
    }
    return $opt;
}

function optJenisKelamin($value = null)
{
    $categories = ['Laki-Laki','Perempuan'];
    $opt = '<option value=""></option>';
    foreach ($categories as $category) {
        $opt .= '<option value="' . $category . '" ' . ($value == $category ? 'selected' : '') . '>' . $category . '</option>';
    }

    return $opt;
}

function tipeIndentitas()
{
    return [
        'NIK/KTP',
        'NPWP',
        'NIP'
    ];
}

function numberToIndonesianWord($number) {
    $words = [
        1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat', 
        5 => 'Lima', 6 => 'Enam', 7 => 'Tujuh', 8 => 'Delapan', 
        9 => 'Sembilan', 10 => 'Sepuluh', 11 => 'Sebelas', 
        12 => 'Dua Belas', 13 => 'Tiga Belas', 14 => 'Empat Belas', 
        15 => 'Lima Belas', 16 => 'Enam Belas', 17 => 'Tujuh Belas', 
        18 => 'Delapan Belas', 19 => 'Sembilan Belas', 20 => 'Dua Puluh', 
        21 => 'Dua Puluh Satu', 22 => 'Dua Puluh Dua', 23 => 'Dua Puluh Tiga', 
        24 => 'Dua Puluh Empat', 25 => 'Dua Puluh Lima', 26 => 'Dua Puluh Enam', 
        27 => 'Dua Puluh Tujuh', 28 => 'Dua Puluh Delapan', 29 => 'Dua Puluh Sembilan', 
        30 => 'Tiga Puluh', 31 => 'Tiga Puluh Satu'
    ];

    return $words[$number] ?? 'Number out of range';
}