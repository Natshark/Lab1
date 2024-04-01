<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getPhpVersion()
    {
        $phpVersion = phpversion();
        return response()->json(['php_version' => $phpVersion]);
    }


    public function getClientInfo(Request $request)
    {
        $ip = $request -> ip();
        $useragent = $request->header('User-Agent');
        return response()->json(['ip' => $ip, 'useragent' => $useragent]);
    }

    public function getDatabaseInfo()
    {
        $databaseInfo = config('database.connections.' . config('database.default'));
        return response()->json(['database_info' => $databaseInfo]);
    }
}
