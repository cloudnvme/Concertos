<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Torrent;
use App\Type;

class AddTypeIdToTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('torrents', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->index('type_id')->index('type_id');
        });

        foreach (Torrent::all() as $torrent) {
            $type_id = Type::where('name', $torrent->type)->firstOrFail()->id;
            $torrent->type_id = $type_id;
            $torrent->save();
        }

        Schema::table('torrents', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('torrents', function (Blueprint $table) {
            //
        });
    }
}
