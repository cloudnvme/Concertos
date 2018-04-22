<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\TorrentRequest;
use App\Type;

class AddTypeIdToRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->index('type_id')->index('type_id');
        });

        foreach (TorrentRequest::all() as $request) {
            $type_id = Type::where('name', $request->type)->firstOrFail()->id;
            $request->type_id = $type_id;
            $request->save();
        }

        Schema::table('requests', function (Blueprint $table) {
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
        Schema::table('requests', function (Blueprint $table) {
            //
        });
    }
}
