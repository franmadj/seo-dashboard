<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contact_id')->unsigned()->index()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->string('sender');
            $table->string('recipient');
            $table->string('subject');
            $table->text('body');
            $table->enum('type', ['outbox', 'inbox']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('emails');
    }

}
