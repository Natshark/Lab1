<?php

namespace App\Http\Controllers;

use App\DTO\LogRequestDTO;
use App\Models\LogRequest;
use Illuminate\Http\Request;

class LogRequestController extends Controller
{
    public function getAll(Request $request)
    {
        $query = LogRequest::query();

        if ($filters = $request->input('filter'))
        {
            foreach ($filters as $filter)
            {
                if (in_array($filter['key'], ['user_id', 'response_status_code', 'ip', 'user_agent', 'controller_path']))
                {
                    $query->where($filter['key'], $filter['value']);
                }
            }
        }

        if ($sorts = $request->input('sortBy'))
        {
            foreach ($sorts as $sort)
            {
                $query->orderBy($sort['key'], $sort['order']);
            }
        }

        $page = $request->input('page', 1);
        $count = $request->input('count', 10);

        $logs = $query->paginate($count, ['*']);

        return response()->json($logs->map(function ($log) {
            return [
                'full_url' => $log->full_url,
                'controller' => $log->controller_path . '@' . $log->controller_method,
                'response_status_code' => $log->response_status_code,
                'called_at' => $log->called_at,
            ];
        }));
    }

    public function getById($id)
    {
        $log = LogRequest::find($id);

        if ($log)
        {
            return response()->json($log);
        }

        return response()->json(['Лог с таким id не найден'], 404);
    }

    public function hardDelete($id)
    {
        $log = LogRequest::find($id);

        if ($log)
        {
            LogRequest::create(LogRequestDTO::fromModelToDTO($log)->toArray());
            $log->forceDelete();
            return response()->json('Лог был жёстко удалён');
        }

        return response()->json(['Роль с таким id не найдена'], 404);
    }
}
