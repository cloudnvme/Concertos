<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\FeaturedTorrent;
use App\Torrent;

class featureRandom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'featureRandom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make randomly-select low-seeders torrents freelech';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function removeRandom()
    {
        $featured = FeaturedTorrent::where('user_id', 1)->get();
        foreach ($featured as $f) {
            $torrent = $f->torrent;
            $torrent->free = 0;
            $torrent->doubleup = 0;
            $torrent->featured = 0;
            $torrent->save();

            $f->delete();
        }
    }

    public function featureRandom()
    {
        $torrent_max = Torrent::orderBy('id', 'desc')->first();
        if ($torrent_max === null) {
            return;
        }

        $max_id = $torrent_max->id;
        for ($i = 0; $i < 3; ++$i) {
            $id = mt_rand(0, $max_id);
            $torrent = Torrent::where('id', '>=', $id)->where('seeders', '1')->where('featured', '0')->first();
            if ($torrent === null) {
                continue;
            }

            $torrent->free = 1;
            $torrent->doubleup = 1;
            $torrent->featured = 1;
            $torrent->save();

            $featured = new FeaturedTorrent();
            $featured->user_id = 1;
            $featured->torrent_id = $torrent->id;
            $featured->save();
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->removeRandom();
        $this->featureRandom();
    }
}
