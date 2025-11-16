<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // null for system actions
            $table->string('action');                          // e.g., 'project.update'
            $table->string('target_type')->nullable();         // e.g., 'project'
            $table->string('target_id')->nullable();           // keep as text (string) to avoid FK sprawl
            $table->string('ip', 45)->nullable();              // supports IPv6
            $table->text('user_agent')->nullable();
            $table->json('details')->nullable();               // JSON on MySQL, JSONB equivalent on Postgres
            $table->timestamp('created_at')->useCurrent();     // only created_at as per your spec

            // Relations & indexes
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['user_id', 'action']);
            $table->index(['target_type', 'target_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

