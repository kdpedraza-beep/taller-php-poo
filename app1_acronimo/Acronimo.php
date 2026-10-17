<?php
class Acronimo
{
    private string $frase;

    public function __construct(string $frase)
    {
        $this->frase = $frase;
    }

    public function generar(): string
    {
        // Los guiones se tratan como separadores de palabras
        $texto = str_replace('-', ' ', $this->frase);

        // Se eliminan los demás signos de puntuación
        $texto = preg_replace('/[^\p{L}\p{N}\s]/u', '', $texto);

        $palabras = preg_split('/\s+/u', trim($texto), -1, PREG_SPLIT_NO_EMPTY);

        $acronimo = '';
        foreach ($palabras as $palabra) {
            $acronimo .= mb_strtoupper(mb_substr($palabra, 0, 1));
        }
        return $acronimo;
    }
}