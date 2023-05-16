<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NodeModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nodeOrigem',
        'nodeDestino',
        'isArray'
    ];

}
