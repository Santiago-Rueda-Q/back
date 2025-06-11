<?php

namespace Tests\Unit;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;

class AuthorTest extends \Tests\TestCase
{
    use RefreshDatabase;

    public function test_author_can_be_created_with_required_fields(): void
    {
        Author::factory()->create([
            'nombre' => 'Julio Cortázar',
        ]);

        $this->assertDatabaseHas('autores', [
            'nombre' => 'Julio Cortázar',
        ]);
    }

    public function test_author_creation_fails_without_name(): void
    {
        $this->expectException(QueryException::class);

        Author::create([
            'email' => 'gola@example',
        ]);
    }
    
}
