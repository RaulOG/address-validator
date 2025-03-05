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
        if(!Schema::hasTable('csv_uploads')) {
            Schema::create('csv_uploads', function (Blueprint $table) {
                $table->id();
                $table->string('file_name');
                $table->string('file_path');
                $table->unsignedBigInteger('uploaded_by');
                $table->timestamp('uploaded_at');
                $table->json('field_mapping')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('csv_uploads')) {
            Schema::dropIfExists('csv_uploads');
        }
    }
};
