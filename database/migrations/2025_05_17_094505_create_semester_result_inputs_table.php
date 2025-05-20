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
        Schema::create('semester_result_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_result_id')->constrained('semester_results');
            $table->foreignId('course_id')->constrained('courses');
            $table->integer('test_score');
            $table->integer('exam_score');
            $table->integer('total_score');
            $table->integer('assignment_score');
            $table->string('grade');
            $table->integer('grade_point');
            $table->string('remark');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_result_inputs');
    }
};
