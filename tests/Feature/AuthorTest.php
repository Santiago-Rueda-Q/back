<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_autor()
    {
        $data = [
            'nombre' => 'Gabriel García Márquez',
            'email' => 'gabriel@example.com',
            'biografia' => 'Autor colombiano, ganador del Nobel de Literatura.',
        ];

        $response = $this->postJson('/api/authors/store', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['nombre' => 'Gabriel García Márquez']);

        $this->assertDatabaseHas('autores', ['email' => 'gabriel@example.com']);
    }

    public function test_listar_autores()
    {
        Author::factory()->count(3)->create();

        $response = $this->getJson('/api/authors');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => ['id', 'nombre', 'email', 'biografia', 'created_at', 'updated_at'],
                ]);
    }

    public function test_mostrar_autor()
    {
        $autor = Author::factory()->create([
            'nombre' => 'Isabel Allende',
            'email' => 'isabel@example.com',
        ]);

        $response = $this->getJson("/api/authors/{$autor->id}");

        $response->assertStatus(200)
                ->assertJsonFragment(['email' => 'isabel@example.com']);
    }


    public function test_actualizar_autor()
    {
        $autor = Author::factory()->create();

        $datosActualizados = [
            'nombre' => 'Julio Cortázar',
            'email' => 'julio@example.com',
            'biografia' => 'Escritor argentino de cuentos y novelas',
        ];

        $response = $this->putJson("/api/authors/update/{$autor->id}", $datosActualizados);

        $response->assertStatus(200)
                ->assertJsonFragment(['nombre' => 'Julio Cortázar']);

        $this->assertDatabaseHas('autores', ['email' => 'julio@example.com']);
    }


    public function test_eliminar_autor()
    {
        $autor = Author::factory()->create();

        $response = $this->deleteJson("/api/authors/delete/{$autor->id}");

        $response->assertStatus(200)
                ->assertJsonFragment(['mensaje' => 'Author eliminado']);

        $this->assertDatabaseMissing('autores', ['id' => $autor->id]);
    }

    public function test_actualizar_autor_con_email_invalido()
    {
        $autor = Author::factory()->create();

        $response = $this->putJson("/api/authors/update/{$autor->id}", [
            'nombre' => 'César Vallejo',
            'email' => 'no-es-un-email',
            'biografia' => 'Poeta peruano.',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
    public function test_eliminar_autor_inexistente()
    {
        $response = $this->deleteJson("/api/authors/delete/9999");

        $response->assertStatus(404);
    }



}
