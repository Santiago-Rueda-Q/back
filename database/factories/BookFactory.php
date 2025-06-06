<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\libros>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(),
            'sinopsis' => $this->faker->paragraph(),
            'autor_id' => Author::inRandomOrder()->first()->id,
        ];
    }
}
