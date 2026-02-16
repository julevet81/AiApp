<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->text('idea');
            $table->string('domain');
            $table->enum('status', ['waiting', 'created', 'uploaded', 'verified', 'rejected'])->default('waiting');
            $table->string('site_url');
            $table->string('privacy_url');
            $table->string('delete_url');
            $table->string('files_url');
            $table->string('design_url');
            $table->enum('site_status', ['waiting', 'created', 'uploaded', 'verified'])->default('waiting');
            $table->enum('privacy_status', ['waiting', 'created', 'uploaded', 'verified'])->default('waiting');
            $table->enum('delete_status', ['waiting', 'created', 'uploaded', 'verified'])->default('waiting');
            $table->enum('files_status', ['waiting', 'created', 'uploaded', 'verified'])->default('waiting');
            $table->string('chort_description')->nullable();
            $table->text('long_description')->nullable();
            $table->string('email_access')->nullable();
            $table->text('ksa_instructions')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};