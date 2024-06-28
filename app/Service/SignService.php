<?php

namespace App\Service;

use Exception;

/**
 * TODO: QUEDA PARA DESPUES CREAR UN FIRMADOR EN NATIVO
 */
class SignService
{


    private string $pd12FileContent;

    private array $data = [];
    /**
     * Sign a document
     *
     * @param string $document
     * @param string $pd12
     * @param string $password
     * @return void
     */
    public function signDocument(string $document, string $pd12File, string $password)
    {
        $this->getP12File($pd12File);
        $this->password = $password;
    }

    /**
     * Establece los sets
     *
     * @param [type] $name
     * @param [type] $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }


    /**
     * Get a P12 file
     *
     * @param string $pd12File
     * @return void
     * @throws Exception
     */
    private function getP12File($pd12File)
    {
        $path = storage_path('app/certificates' . $pd12File);
        $content = file_get_contents($path);
        if (!$content) throw new Exception("File $pd12File not found");
        $this->pd12FileContent = $content;
    }

    /**
     * Get string hexadecimal to Base64 of a string
     *
     * @param string $txt
     * @throws Exception
     * @return string
     */
    private function hexToBase64($hexString)
    {
        // Verificar si el string hexadecimal es válido
        if (!ctype_xdigit($hexString)) {
            throw new Exception("La cadena proporcionada no es un valor hexadecimal válido.");
        }

        // Convertir el string hexadecimal a su representación binaria
        $binaryData = hex2bin($hexString);
        if ($binaryData === false) {
            throw new Exception("Error al convertir la cadena hexadecimal a binaria.");
        }

        // Convertir la representación binaria a base64
        $base64String = base64_encode($binaryData);
        return $base64String;
    }

    /**
     * Convert a bigInt to Base64
     *
     * @param string $bigint
     * @throws Exception
     *  @return string
     */
    private function bigintToBase64($bigint)
    {
        // Verificar si el valor proporcionado es un número
        if (!is_numeric($bigint)) {
            throw new Exception("El valor proporcionado no es un número válido.");
        }

        // Convertir el bigint a una cadena binaria
        $binaryData = gmp_export(gmp_init($bigint, 10));

        // Convertir la representación binaria a base64
        $base64String = base64_encode($binaryData);
        return $base64String;
    }

    /**
     * Get a random number between min and max
     *
     * @param integer $min
     * @param integer $max
     * @throws Exception
     * @return int
     */
    private function getRandomNumber(int $min = 990, int $max = 9999)
    {
        // Verificar si los valores proporcionados son números
        if (!is_numeric($min) || !is_numeric($max)) {
            throw new Exception("Los valores proporcionados deben ser números válidos.");
        }

        // Convertir los valores a enteros
        $min = (int) $min;
        $max = (int) $max;

        // Verificar si el mínimo es menor o igual al máximo
        if ($min > $max) {
            throw new Exception("El valor mínimo no puede ser mayor que el valor máximo.");
        }

        // Generar un número aleatorio entre min y max (inclusive)
        $randomNumber = random_int($min, $max);
        return $randomNumber;
    }
}
