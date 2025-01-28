<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# API de Gerenciamento de Colaboradores

Esta aplicação é uma API para gerenciar colaboradores de diferentes empresas, desenvolvida em Laravel. A API permite cadastrar empresas, colaboradores, fazer upload de arquivos CSV para adicionar colaboradores em massa, e consultar colaboradores com filtros específicos.

## Requisitos do Projeto

- **PHP**: 8.3 ou superior
- **Composer**: 2.0 ou superior
- **MySQL**: 8.0 ou superior
- **Node.js** (opcional, para compilar assets caso necessário)
- **Redis** (para filas de processamento em background)

---

## Instalação do Projeto

### 1. Clone o Repositório

```bash
git clone git@github.com:Paullolo18/test-winx.git
cd test-winx
```

### 2. Instale as Dependências

#### Instale as dependências do PHP:
```bash
composer install
```

#### Instale as dependências do Node.js (se necessário):
```bash
npm install
```

### 3. Configure o Arquivo `.env`

Copie o arquivo de exemplo `.env.example` para criar seu arquivo `.env`:

```bash
cp .env.example .env
```

Edite o arquivo `.env` para configurar os seguintes detalhes:

- **Banco de Dados**:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=seu_banco_de_dados
  DB_USERNAME=seu_usuario
  DB_PASSWORD=sua_senha
  ```

- **Configuração de Filas (Redis)**:
  ```env
  QUEUE_CONNECTION=redis
  REDIS_HOST=127.0.0.1
  REDIS_PASSWORD=null
  REDIS_PORT=6379
  ```

### 4. Gere a Chave da Aplicação

```bash
php artisan key:generate
```

### 5. Configure as Migrações do Banco de Dados

Execute as migrações para criar as tabelas necessárias:

```bash
php artisan migrate
```

---

## Configuração de Filas

Certifique-se de que o Redis está instalado e rodando. No Linux, você pode instalar com:

```bash
sudo apt update
sudo apt install redis-server
```

Para iniciar o Redis:
```bash
sudo systemctl start redis
```

Para processar filas em background:
```bash
php artisan queue:work
```

---

## Rodando o Sistema Localmente

Inicie o servidor de desenvolvimento do Laravel:

```bash
php artisan serve
```

A aplicação estará acessível em: [http://localhost:8000](http://localhost:8000)

---

## Como Executar os Testes

Execute os testes automatizados com:

```bash
php artisan test
```

Os testes cobrem os seguintes cenários:
- Registro e autenticação de usuários
- Criação de empresas
- Upload de arquivos CSV para colaboradores
- Listagem e filtragem de colaboradores

---

## Rotas Disponíveis

### Rotas Públicas

- **Registrar Usuário e Empresa**: `POST /api/register`
- **Login**: `POST /api/login`

### Rotas Protegidas (Requer Autenticação via Bearer Token)

- **Logout**: `POST /api/logout`
- **Cadastrar Empresa**: `POST /api/empresas`
- **Listar Empresas**: `GET /api/empresas`
- **Fazer Upload de CSV**: `POST /api/upload-csv`
- **Listar Colaboradores**: `GET /api/colaboradores`
- **Filtrar Colaboradores**: `GET /api/colaboradores?nome=...&cargo=...&data_admissao=...`
- **Ver Detalhes do Colaborador**: `GET /api/colaboradores/{id}`

---

## Estrutura do Projeto

### Diretórios Principais

- **`app/Models`**: Contém os modelos `User`, `Empresa` e `Colaborador`.
- **`app/Http/Controllers`**: Contém os controladores responsáveis pelas ações da API.
- **`app/Jobs`**: Contém o job `ProcessCsvJob` para processar uploads CSV em background.
- **`routes/api.php`**: Definição das rotas da API.

### Outros Arquivos Importantes

- **`.env`**: Configurações específicas do ambiente (banco de dados, filas, etc.).
- **`tests/Feature`**: Contém os testes automatizados para as principais funcionalidades.

---

## Exemplo de Uso da API

- **Registro de Usuário e Empresa**:
  ```json
  POST /api/register
  {
      "name": "Admin Empresa",
      "email": "admin@empresa.com",
      "password": "123456",
      "password_confirmation": "123456",
      "empresa": {
          "nome": "Minha Empresa",
          "email": "empresa@example.com",
          "cnpj": "12345678000195"
      }
  }
  ```

- **Upload de CSV**:
  ```
  POST /api/upload-csv
  Authorization: Bearer {token}
  File: colaboradores.csv
  ```

---

## Observações

Certifique-se de que as permissões da pasta `storage/` estão configuradas corretamente:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

