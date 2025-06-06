<?php

namespace App\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    
    protected static function newFactory()
    {
        return BookFactory::new();
    }

    protected $table = 'libros';

    protected $fillable = ['titulo', 'sinopsis', 'autor_id'];

    public function autor()
    {
        return $this->belongsTo(Author::class, 'autor_id');
    }
}
