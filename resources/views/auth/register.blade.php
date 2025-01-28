<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Registrar</h1>
        <form id="registerForm" class="mt-4">
            <h2>Dados do Usuário</h2>
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>

            <h2>Dados da Empresa</h2>
            <div class="mb-3">
                <label for="company_name" class="form-label">Nome da Empresa</label>
                <input type="text" name="company_name" id="company_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="company_email" class="form-label">E-mail da Empresa</label>
                <input type="email" name="company_email" id="company_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cnpj" class="form-label">CNPJ</label>
                <input type="text" name="cnpj" id="cnpj" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrar</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            const userData = {
                name: formData.get('name'),
                email: formData.get('email'),
                password: formData.get('password'),
                password_confirmation: formData.get('password_confirmation'),
            };

            const companyData = {
                nome: formData.get('company_name'),
                email: formData.get('company_email'),
                cnpj: formData.get('cnpj'),
            };

            try {
                // Etapa 1: Registrar o usuário
                const registerResponse = await fetch('/api/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(userData),
                });

                if (!registerResponse.ok) {
                    const error = await registerResponse.json();
                    throw new Error(error.message || 'Erro no registro');
                }

                alert('Usuário registrado com sucesso!');

                // Etapa 2: Login do usuário
                const loginResponse = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        email: userData.email,
                        password: userData.password,
                    }),
                });

                if (!loginResponse.ok) {
                    const error = await loginResponse.json();
                    throw new Error(error.message || 'Erro ao fazer login');
                }

                const loginData = await loginResponse.json();
                const token = loginData.access_token;

                alert('Login realizado com sucesso!');

                // Etapa 3: Criar empresa
                const companyResponse = await fetch('/api/empresas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                    body: JSON.stringify(companyData),
                });

                if (!companyResponse.ok) {
                    const error = await companyResponse.json();
                    throw new Error(error.message || 'Erro ao cadastrar empresa');
                }

                alert('Empresa cadastrada com sucesso! Redirecionando para o dashboard...');
                window.location.href = '/dashboard';
            } catch (err) {
                alert(err.message);
            }
        });
    </script>
</body>
</html>
