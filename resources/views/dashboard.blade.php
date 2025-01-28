<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1>Dashboard</h1>
        <p id="userInfo"></p>
        <p id="companyInfo"></p>

        <!-- Upload CSV -->
        <h2 class="mt-5">Upload de CSV</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="file" id="file" class="form-control mb-3">
            <button type="submit" class="btn btn-success">Enviar CSV</button>
        </form>

        <!-- Filtros -->
        <div class="mt-4">
            <h2>Filtros</h2>
            <form id="filterForm" class="row g-3">
                <div class="col-md-4">
                    <label for="nome" class="form-label">Nome do Colaborador</label>
                    <input type="text" id="nome" name="nome" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="cargo" class="form-label">Cargo</label>
                    <input type="text" id="cargo" name="cargo" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="data_admissao" class="form-label">Data de Admissão</label>
                    <input type="date" id="data_admissao" name="data_admissao" class="form-control">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary w-100">Aplicar Filtros</button>
                </div>
            </form>
        </div>

        

        <!-- Tabela de Colaboradores -->
        <h2 class="mt-5">Colaboradores</h2>
        <table id="colaboradoresTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Data de Admissão</th>
                    <th>Empresa</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        const token = localStorage.getItem('token');

        if (!token) {
            alert('Você precisa estar logado para acessar o dashboard.');
            window.location.href = '/login';
        }

        /// Fetch user and company info
       

        // Inicializar DataTables
        const table = $('#colaboradoresTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/colaboradores',
                dataSrc: 'data',
                data: function (d) {
                    // Adiciona os filtros como parâmetros
                    d.nome = document.getElementById('nome').value;
                    d.cargo = document.getElementById('cargo').value;
                    d.data_admissao = document.getElementById('data_admissao').value;
                },
                headers: { Authorization: `Bearer ${token}` },
            },
            columns: [
                { data: 'nome' },
                { data: 'email' },
                { data: 'cargo' },
                { data: 'data_admissao' },
                { data: 'empresa.nome' }, // Mostra o nome da empresa associada
            ],
        });

        // Aplicar Filtros
        document.getElementById('filterForm').addEventListener('submit', (e) => {
            e.preventDefault();
            table.ajax.reload();
        });

        // Upload CSV
        document.getElementById('uploadForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch('/api/upload-csv', {
                    method: 'POST',
                    headers: { Authorization: `Bearer ${token}` },
                    body: formData,
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Erro ao enviar CSV.');
                }

                alert('CSV enviado com sucesso!');
                table.ajax.reload();
            } catch (err) {
                alert(err.message);
            }
        });
    </script>
</body>

</html>
