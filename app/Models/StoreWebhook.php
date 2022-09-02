<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreWebhook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];
    /**
     * Get user whom this meta data belongs
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
