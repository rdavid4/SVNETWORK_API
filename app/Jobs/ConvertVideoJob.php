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
class ConvertVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video;

    public function __construct($video)
    {
        $this->video = $video;
    }

    public function handle()
    {
        // Comando de ffmpeg
        $comando = [
            'ffmpeg',
            '-i', $this->video,
            '-c:v', 'libx264',
            '-crf', '30',
            '-preset', 'medium',
            '-vf', 'scale=-2:720',
            '-c:a', 'aac',
            '-b:a', '128k',
            '-movflags', '+faststart',
            'temp_video.mp4'
        ];

        // Crear el proceso
        $process = new Process($comando);

        try {
            // Ejecutar el comando
            $process->mustRun();

            // Reemplazar el archivo original con el convertido
            rename('temp_video.mp4', $this->video);

        } catch (ProcessFailedException $exception) {
            // Manejar excepciones si el comando falla
            Log::error("Error al ejecutar el comando ffmpeg: {$exception->getMessage()}");
        }
    }
}
