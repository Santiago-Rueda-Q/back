<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_can_be_created_with_required_fields(): void
    {
        $author = Author::factory()->create();

        $book = Book::factory()->create([
            'titulo' => 'Cien años de soledad',
            'autor_id' => $author->id,
        ]);

        $this->assertDatabaseHas('libros', [
            'titulo' => 'Cien años de soledad',
            'autor_id' => $author->id,
        ]);
    }

    public function test_book_creation_fails_without_title(): void
    {
        $author = Author::factory()->create();

        $this->expectException(QueryException::class);

        Book::create([
            'autor_id' => $author->id,
            // Falta 'titulo' que es obligatorio
        ]);
    }
}
