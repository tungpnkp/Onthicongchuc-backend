<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateDocumentsTable.
 */
class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->tinyInteger('type')->default(1)->comment("1=>Luật | 2=> Tiếng anh");
            $table->tinyInteger("status")->default(1);
            $table->timestamps();
        });
        Schema::create('exams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->json('answer')->nullable()->comment("Đáp án");
            $table->tinyInteger('type')->default(1)->comment("1=>Luật | 2=> Tiếng anh");
            $table->tinyInteger("status")->default(1);
            $table->timestamps();
        });
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('exam_id');
            $table->text('title');
            $table->text('content')->nullable()->comment("Nội dung câu hỏi đoạn văn");
            $table->text('a')->nullable();
            $table->text('b')->nullable();
            $table->text('c')->nullable();
            $table->text('d')->nullable();
            $table->string('answer')->nullable()->comment("Đáp án");
            $table->tinyInteger('type')->default(1)->comment("1=> File 1 | 2=> File 2 | 3=> File 3 | 4=> File 4");
            $table->tinyInteger("status")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documents');
        Schema::drop('questions');
        Schema::drop('exams');
    }
}
