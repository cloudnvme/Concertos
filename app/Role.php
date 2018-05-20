<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Role extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getColor()
    {
        if ($this->name == 'Bot') {
            return '#f1c40f';
        }

        if ($this->name == 'Owner') {
            return '#00abff';
        }

        if ($this->name == 'Uploader') {
            return '#2ECC71';
        }

        if ($this->name == 'Trustee') {
            return '#BF55EC';
        }

        if ($this->name == 'Banned') {
            return 'red';
        }

        if ($this->name == 'Validating') {
            return '#95A5A6';
        }

        if ($this->name == 'Moderator') {
            return '#4ECDC4';
        }

        return "#7289DA";
    }

    public function getIcon()
    {
        if ($this->name == 'Bot') {
            return 'fas fa-cogs';
        }

        if ($this->name == 'Owner' || $this->name == 'Moderator' || $this->name == 'Administrator') {
            return 'fa fa-user-secret';
        }

        if ($this->name == 'Uploader') {
            return 'fa fa-upload';
        }

        if ($this->name == 'Trustee') {
            return 'fas fa-shield-alt';
        }

        if ($this->name == 'Banned') {
            return 'fa fa-ban';
        }

        if ($this->name == 'Validating') {
            return 'fa fa-question-circle';
        }

        return "fa fa-user";
    }

    public function getEffect()
    {
        return "none";
    }
}
