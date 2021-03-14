<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first',
        'last',
        'emails',
        'phone-numbers',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'emails' => 'json',
        'phone-numbers' => 'json',
    ];

    public function getPrimaryEmail() {
        //return the "primary" email address
    }

    public function getPrimaryPhoneNumber() {
        //return the "primary" phone number
    }
}
