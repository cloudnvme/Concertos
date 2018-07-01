<?php

namespace App;

class Path {
    public static function getTorrentsPath() {
        return base_path() . '/private/files/torrents';
    }

    public static function getTmpPath() {
        return base_path() . '/private/files/tmp';
    }
}