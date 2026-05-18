<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\CloseCase;
use App\Models\CaseCloseProgresses;
use App\Models\CommandCenter\CommandCenterOBD;


class CommandCenterDataHelper
{

    
    public static function getCommandCenterObdDataFirst()
    {
        $obd_data_fist = CommandCenterOBD::orderBy('created_at', 'desc')->first();
        return $obd_data_fist;
    }

       
}
