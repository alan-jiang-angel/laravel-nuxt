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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->date('pub_date');
            $table->text('description');
            $table->string('image', 1024);
            $table->enum('platform', ['vc', 'habr', 'youtube'])->default('vc');
            $table->string('link', 1024);
            $table->integer('views')->unsigned()->default(0);
            $table->integer('likes')->unsigned()->default(0);
            $table->integer('comments')->unsigned()->default(0);
            $table->integer('favs')->unsigned()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
