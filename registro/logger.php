<?php

class logger {
    // Variable para habilitar/deshabilitar el logging
    public static $enabled = true;

    // Directorio donde se guardarán los logs
    private static $logDirectory;

    // Inicializar la propiedad estática en un método estático
    private static function initialize() {
      if (!self::$logDirectory) {
          self::$logDirectory = dirname(__FILE__) . '/logs';
      }
    }

    // Función para escribir una línea en el log
    public static function log($message) {
        // Comprobar si el logging está habilitado
        if (!self::$enabled) {
            return;
        }

        self::initialize();

        // Asegurarse de que el directorio existe
        if (!is_dir(self::$logDirectory)) {
            mkdir(self::$logDirectory, 0777, true);
        }

        // Nombre del archivo (un archivo por día)
        $filename = self::$logDirectory . '/' . date('Y-m-d') . '.log';

        // Crear el mensaje con la fecha y hora actual
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        $separator = "##############################################################################" . PHP_EOL;

        // Leer el contenido existente del archivo si existe
        $existingContent = file_exists($filename) ? file_get_contents($filename) : '';

        // Agregar el nuevo mensaje al principio
        $newContent = $logMessage . $separator . $existingContent;

        // Escribir el contenido actualizado al archivo
        file_put_contents($filename, $newContent);
    }
}
