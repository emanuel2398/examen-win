<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up()
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->string('status');// si bien podria ser enum, se considera que en algun momento se puede agreagar un nuevo estado entonces preferia que sea string
            $table->double('amount');
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('grupos')->onDelete('cascade');
            $table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('ordenes');
    }
};
