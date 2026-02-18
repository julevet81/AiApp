<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        'app_name',
        'idea',
        'domain',
        'status',
        'site_url',
        'privacy_url',
        'delete_url',
        'design_url',
        'files_url',
        'site_status',
        'privacy_status',
        'delete_status',
        'files_status',
        'chort_description',
        'long_description',
        'email_access',
        'note',
    ];

    
}