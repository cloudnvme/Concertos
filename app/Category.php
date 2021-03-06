<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    /**
     * Validation rules
     *
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required',
        'position' => 'required',
        'icon' => 'required',
        'meta' => 'required'
    ];

    /**
     * Has many torrents
     *
     *
     */
    public function torrents()
    {
        return $this->hasMany(\App\Torrent::class);
    }

    /**
     * Has many requests
     *
     */
    public function requests()
    {
        return $this->hasMany(\App\TorrentRequest::class);
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getName()
    {
        return $this->name;
    }
}
