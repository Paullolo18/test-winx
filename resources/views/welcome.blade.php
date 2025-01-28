<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API - Bem-vindo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Bem-vindo à API de Colaboradores</h1>
        <p class="text-center">Gerencie suas empresas e colaboradores de forma eficiente!</p>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-primary">Registrar</a>
            <a href="{{ route('login') }}" class="btn btn-success">Login</a>
        </div>

        <hr class="my-5">

        <h2 class="text-center">Como consumir nossa API</h2>
        <pre class="bg-dark text-white p-3 rounded">
POST /api/register
{
  "name": "Admin Empresa",
  "email": "admin@empresa.com",
  "password": "123456",
  "password_confirmation": "123456"
}

POST /api/login
{
  "email": "admin@empresa.com",
  "password": "123456"
}

GET /api/colaboradores
Authorization: Bearer {access_token}
        </pre>
    </div>
</body>
</html>
