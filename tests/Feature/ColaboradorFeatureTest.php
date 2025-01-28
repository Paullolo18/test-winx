<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ColaboradorFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    /**
     * Configuração inicial antes de cada teste.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Cria um usuário e realiza o login
        $user = $this->postJson('/api/register', [
            "name" => "Admin Empresa",
            'email' => 'admin@empresa.com',
            'password' => "123456",
            "password_confirmation"=> "123456"
        ]);
    }

    /**
     * Testa o login de um usuário.
     */
    /**
     * Testa o login e captura do token.
     */
    public function test_login_user()
    {
        $loginData = [
            'email' => 'admin@empresa.com',
            'password' => '123456',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);

        $this->token = $response->json('access_token');
        $this->assertNotEmpty($this->token, 'O token de acesso não foi capturado.');
    }

    /**
     * Testa a criação de uma empresa.
     */
    public function test_create_company()
    {
        // Realiza login e captura o token
        $loginData = [
            'email' => 'admin@empresa.com',
            'password' => '123456',
        ];

        $loginResponse = $this->postJson('/api/login', $loginData);
        $this->token = $loginResponse->json('access_token');

        $empresaData = [
            'nome' => 'Minha Empresa',
            'email' => 'empresa@example.com',
            'cnpj' => '12345678000195',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/empresas', $empresaData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'nome',
                'email',
                'cnpj',
                'created_at',
                'updated_at',
            ]);

        $this->assertDatabaseHas('empresas', [
            'nome' => 'Minha Empresa',
            'email' => 'empresa@example.com',
            'cnpj' => '12345678000195',
        ]);
    }

    /**
     * Testa o upload de um CSV e a inserção de colaboradores.
     */
    public function test_upload_csv()
    {
        // Realiza login e captura o token
        $loginData = [
            'email' => 'admin@empresa.com',
            'password' => '123456',
        ];

        $loginResponse = $this->postJson('/api/login', $loginData);
        $this->token = $loginResponse->json('access_token');

        $empresaData = [
            'nome' => 'Minha Empresa',
            'email' => 'empresa@example.com',
            'cnpj' => '12345678000195',
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/empresas', $empresaData);

        $file = new UploadedFile(
            base_path('tests/Fixtures/colaboradores_exemplo.csv'),
            'colaboradores.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/upload-csv', [
                'file' => $file,
            ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Upload recebido! O processamento será feito em background.']);
    }

    /**
     * Testa a listagem de colaboradores.
     */
    public function test_list_colaboradores()
    {
        // Realiza login e captura o token
        $loginData = [
            'email' => 'admin@empresa.com',
            'password' => '123456',
        ];

        $loginResponse = $this->postJson('/api/login', $loginData);
        $this->token = $loginResponse->json('access_token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/colaboradores');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'nome', 'email', 'cargo', 'data_admissao'],
                ],
            ]);
    }
}
