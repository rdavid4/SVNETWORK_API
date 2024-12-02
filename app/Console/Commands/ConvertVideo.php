<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
class ConvertVideo extends Command
{
   // La firma del comando, puede incluir parámetros como el nombre del archivo de video
   protected $signature = 'video:convert {video}';

   // Descripción del comando
   protected $description = 'Convierte un video usando ffmpeg y lo reemplaza';

   public function __construct()
   {
       parent::__construct();
   }

   public function handle()
   {
       // Obtener el nombre del video desde los argumentos
       $video = $this->argument('video');

       // Preparar el comando ffmpeg
       $comando = [
           'ffmpeg',
           '-i', $video,
           '-c:v', 'libx264',
           '-crf', '30',
           '-preset', 'medium',
           '-vf', 'scale=-2:720',
           '-c:a', 'aac',
           '-b:a', '128k',
           '-movflags', '+faststart',
           'temp_video.mp4'
       ];

       // Crear una nueva instancia del objeto Process
       $process = new Process($comando);

       try {
           // Ejecutar el proceso
           $process->mustRun();

           // Si el comando se ejecuta correctamente, mover el archivo convertido
           rename('temp_video.mp4', $video);

           $this->info("El video fue convertido y reemplazado correctamente.");
       } catch (ProcessFailedException $exception) {
           // Si el comando falla, mostrar el error
           $this->error("Hubo un error al ejecutar el comando: {$exception->getMessage()}");
       }
   }
}
