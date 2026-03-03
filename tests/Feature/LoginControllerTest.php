<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;

class LoginControllerTest extends TestCase
{
    public function test_pode_realizar_login_com_tipos_corretos()
    {
        $payload = [
            'email' => 'joao@email.com',
            'senha' => '123456'
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->whereType('token', 'string')
                     ->has('usuario', fn (AssertableJson $json) =>
                        $json->whereType('id', 'integer')
                             ->whereType('nome', 'string')
                             ->whereType('tipo', 'string')
                     )
            );
    }
}
