<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->text('notes')->nullable();
            $table->date('exam_date');
            $table->timestamps();

            $table->unique(['student_id', 'exam_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_results');
    }
};
