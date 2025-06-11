<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function registro_de_devoluciones_201_y_estructura_esperada()
    {
        $response = $this->postJson('/api/register', [
            'name'                  => 'Santiago',
            'email'                 => 'santiago@gmail.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                     'token',
                     'message',
                 ]);
    }

    public function registro_con_datos_invalidos_devuelve_422()
    {
        $response = $this->postJson('/api/register', [
            'name'     => '',
            'email'    => 'not-an-email',
            'password' => '123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure(['errors']);
    }

    public function login_con_credenciales_correctas_devuelve_200_y_token()
    {
        $user = User::factory()->create([
            'email'    => 'santiago@gmail.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'santiago@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'token',
                     'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                 ]);
    }

    public function login_con_credenciales_invalidas_devuelve_401()
    {
        $user = User::factory()->create([
            'email'    => 'santiago@gmail.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'santiago@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Credenciales incorrectas',
                 ]);
    }

    public function login_con_formato_invalido_devuelve_422()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'no-es-un-correo',
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'message',
                     'errors' => ['email', 'password'],
                 ]);
    }

    public function logout_devoluciones_mensaje_exito()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Sesión cerrada',
                 ]);
    }
}
