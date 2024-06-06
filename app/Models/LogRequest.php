<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_url',
        'http_method',
        'controller_path',
        'controller_method',
        'request_body',
        'request_headers',
        'user_id',
        'ip',
        'user_agent',
        'response_status_code',
        'response_body',
        'response_headers',
        'called_at',
    ];

    protected $dates = [
        'called_at',
    ];
}
