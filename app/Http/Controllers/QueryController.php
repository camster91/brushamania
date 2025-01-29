<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class QueryController extends Controller
{
    public function metaTables($secretKey, $modelName)
    {
        if ($secretKey == "2g1oqktdvyubg") {
            $model = "App\\$modelName";
            $metaLogs = $model::latest()->get();
            return response()->json([
                'metaLogs' => $metaLogs,
            ]);
        } else {
            return response()->json([
                'status' => 'You dont have to access this page',
            ]);
        }
    }

    public function logs($secretKey, $log)
    {
        if ($secretKey == "2g1oqktdvyubgu_logs") {
            $logFilePath = storage_path('logs/' . $log . '.log');
            if (File::exists($logFilePath)) {
                $logs = explode("\n", trim(File::get($logFilePath)));
                $latestLogs = array_slice(array_reverse($logs), 0, 100);
                $result = implode("\n", $latestLogs);
                return htmlspecialchars_decode($result);
            } else {
                return response()->json(['error' => 'Log file not found'], 404);
            }
        } else {
            return response()->json([
                'status' => 'You dont have to access this page',
            ]);
        }
    }
}
