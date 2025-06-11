<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_libro()
    {
        $autor = Author::factory()->create();

        $data = [
            'titulo' => 'Cien Años de Soledad',
            'sinopsis' => 'Una obra maestra del realismo mágico.',
            'autor_id' => $autor->id,
        ];

        $response = $this->postJson('/api/books/store', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['titulo' => 'Cien Años de Soledad']);

        $this->assertDatabaseHas('libros', ['titulo' => 'Cien Años de Soledad']);
    }

    public function test_listar_libros()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['autor_id' => $author->id]);

        $response = $this->getJson('/api/books');
        $response->assertStatus(200);
    }


    public function test_mostrar_libro()
    {
        $libro = Book::factory()->create([
            'titulo' => 'El Aleph',
        ]);

        $response = $this->getJson("/api/books/{$libro->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['titulo' => 'El Aleph']);
    }

    public function test_actualizar_libro()
    {
        $libro = Book::factory()->create();
        $nuevoAutor = Author::factory()->create();

        $datosActualizados = [
            'titulo' => 'Libro Actualizado',
            'sinopsis' => 'Sinopsis actualizada del libro.',
            'autor_id' => $nuevoAutor->id,
        ];

        $response = $this->putJson("/api/books/{$libro->id}", $datosActualizados);

        $response->assertStatus(200)
                 ->assertJsonFragment(['titulo' => 'Libro Actualizado']);

        $this->assertDatabaseHas('libros', ['titulo' => 'Libro Actualizado']);
    }

    public function test_eliminar_libro()
    {
        $libro = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$libro->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['mensaje' => 'Book eliminado']);

        $this->assertDatabaseMissing('libros', ['id' => $libro->id]);
    }
}
