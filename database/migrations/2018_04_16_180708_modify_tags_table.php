<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('slug');

            $table->bigIncrements('id')->change();
            $table->string('name')->nullable(false)->change();
            $table->unsignedBigInteger('torrent_id');

            $table->index('torrent_id');
            $table->index('name');
            $table->unique(['torrent_id', 'name']);
            $table->foreign('torrent_id')->references('id')->on('torrents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            //
        });
    }
}
