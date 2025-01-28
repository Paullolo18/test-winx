<?php

namespace App\Listeners;

use App\Events\ImportacaoConcluida;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarNotificacaoImportacao
{
    /**
     * Handle the event.
     */
    public function handle(ImportacaoConcluida $event): void
    {
        // Log de sucesso
        Log::info("Importação concluída para a empresa: {$event->empresa->nome}");

        // Enviar email de conclusão
        Mail::raw(
            "A importação de colaboradores foi concluída com sucesso para a empresa: {$event->empresa->nome}",
            function ($message) use ($event) {
                $message->to($event->empresa->email)
                    ->subject('Importação Concluída');
            }
        );
    }
}
