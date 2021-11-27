<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public function typeArticle() {
        return $this->belongsTo(Article::class, 'type_id', 'id');
    }
}
