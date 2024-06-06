<?php

namespace App\DTO;

use App\Models\LogRequest;

class LogRequestDTO
{
    public $full_url;
    public $http_method;
    public $controller_path;
    public $controller_method;
    public $request_body;
    public $request_headers;
    public $user_id;
    public $ip;
    public $user_agent;
    public $response_status_code;
    public $response_body;
    public $response_headers;
    public $called_at;

    public function __construct(
        $full_url,
        $http_method,
        $controller_path,
        $controller_method,
        $request_body,
        $request_headers,
        $user_id,
        $ip,
        $user_agent,
        $response_status_code,
        $response_body,
        $response_headers,
        $called_at
    ) {
        $this->full_url = $full_url;
        $this->http_method = $http_method;
        $this->controller_path = $controller_path;
        $this->controller_method = $controller_method;
        $this->request_body = $request_body;
        $this->request_headers = $request_headers;
        $this->user_id = $user_id;
        $this->ip = $ip;
        $this->user_agent = $user_agent;
        $this->response_status_code = $response_status_code;
        $this->response_body = $response_body;
        $this->response_headers = $response_headers;
        $this->called_at = $called_at;
    }

    public function toArray(): array
    {
        return [
            'full_url' => $this->full_url,
            'http_method' => $this->http_method,
            'controller_path' => $this->controller_path,
            'controller_method' => $this->controller_method,
            'request_body' => $this->request_body,
            'request_headers' => $this->request_headers,
            'user_id' => $this->user_id,
            'ip' => $this->ip,
            'user_agent' => $this->user_agent,
            'response_status_code' => $this->response_status_code,
            'response_body' => $this->response_body,
            'response_headers' => $this->response_headers,
            'called_at' => $this->called_at,
        ];
    }

    public static function fromModelToDTO(LogRequest $logRequest): self
    {
        return new self(
            $logRequest->full_url,
            $logRequest->http_method,
            $logRequest->controller_path,
            $logRequest->controller_method,
            $logRequest->request_body,
            $logRequest->request_headers,
            $logRequest->user_id,
            $logRequest->ip,
            $logRequest->user_agent,
            $logRequest->response_status_code,
            $logRequest->response_body,
            $logRequest->response_headers,
            $logRequest->called_at
        );
    }
}
