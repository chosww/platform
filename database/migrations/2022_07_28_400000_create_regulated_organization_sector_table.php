<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulated_organization_sector', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('regulated_organization_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('sector_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regulated_organization_sector');
    }
};
