<?php

namespace App\Jobs;

use App\Models\Colaborador;
use Illuminate\Bus\Queueable;
use App\Events\ImportacaoConcluida;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;
    protected int $empresaId;

    public function __construct(string $filePath, int $empresaId)
    {
        $this->filePath = $filePath;
        $this->empresaId = $empresaId;
    }

    public function handle(): void
    {
        if (!Storage::exists($this->filePath)) {
            return;
        }

        $file = Storage::get($this->filePath);
        $lines = explode("\n", $file);
        unset($lines[0]); // Remove cabeçalho do CSV

        foreach ($lines as $line) {
            $data = str_getcsv($line);

            if (count($data) < 4) continue; // Ignora linhas inválidas

            Colaborador::updateOrCreate(
                ['email' => $data[1]], // Evita duplicatas pelo email
                [
                    'nome' => $data[0],
                    'email' => $data[1],
                    'cargo' => $data[2],
                    'data_admissao' => $data[3],
                    'empresa_id' => $this->empresaId,
                ]
            );
        }

        // Deleta o arquivo após processamento
        Storage::delete($this->filePath);
        event(new ImportacaoConcluida($this->empresaId));
    }
}
