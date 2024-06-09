<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\LogRequest;

class CreateLogRequest
{
    public function handle(Request $request, Closure $next)
    {

        $log = new LogRequest();
        $log->user_id = $request->user() ? $request->user()->id : Null;
        $response = $next($request);
        $log->full_url = $request->fullUrl();
        $log->http_method = $request->method();
        $log->controller_path = class_basename($request->route()?->controller);
        $log->controller_method = $request->route()?->getActionMethod();
        $log->request_body = json_encode($request->all());
        $log->request_headers = json_encode($request->headers->all());
        $log->ip = $request->ip();
        $log->user_agent = $request->userAgent();
        $log->response_status_code = $response->getStatusCode();
        $log->response_body = $response->getContent();
        $log->response_headers = json_encode($response->headers->all());
        $log->called_at = now();
        $log->save();

        LogRequest::where('created_at', '<', now()->subHours(73))->delete();

        return $response;
    }
}
