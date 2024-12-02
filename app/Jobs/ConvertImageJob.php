<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
class ConvertImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image;

    public function __construct($image)
    {
        $this->image = $image;
    }

    public function handle()
    {
        // Comando de ffmpeg
        $comando = [
            'ffmpeg',
            '-i', $this->image,
            '-vf', 'scale=-2:720',             // Redimensionar la imagen manteniendo la relación de aspecto
            '-q:v', '8',                       // Calidad de la imagen (valor entre 1 y 31, donde 1 es mejor)
            'temp_image.jpg'                   // Nombre del archivo de salida
        ];

        $process = new Process($comando);

        try {
            $process->mustRun();  // Ejecuta el comando
            // Opcionalmente, mueve el archivo comprimido a su destino final
            rename('temp_image.jpg', $this->image);
        } catch (ProcessFailedException $exception) {
            Log::error("Error al comprimir la imagen: {$exception->getMessage()}");
        }
    }
}
