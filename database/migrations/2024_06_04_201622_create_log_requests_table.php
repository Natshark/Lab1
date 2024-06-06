<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('log_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_url')->nullable();
            $table->string('http_method')->nullable();
            $table->string('controller_path')->nullable();
            $table->string('controller_method')->nullable();
            $table->text('request_body')->nullable();
            $table->text('request_headers')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->integer('response_status_code');
            $table->text('response_body')->nullable();
            $table->text('response_headers')->nullable();
            $table->timestamp('called_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_requests');
    }
}
