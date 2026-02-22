<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('application_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['opened', 'registered', 'confirmed', 'transferred'])->default('opened');
            $table->decimal('transfer_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
