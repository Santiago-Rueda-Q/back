<?php

namespace App\Models;

use Database\Factories\AuthorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\AutorFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'autores';
    protected $fillable = ['nombre', 'email', 'biografia'];

    public function libros()
    {
        return $this->hasMany(Book::class, 'autor_id');
    }

        use HasFactory;
    
    protected static function newFactory()
    {
        return AuthorFactory::new();
    }
}
