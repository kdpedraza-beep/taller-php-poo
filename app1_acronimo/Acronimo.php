<?php
class Acronimo
{
    private string $frase;

    public function __construct(string $frase)
    {
        $this->frase = $frase;
    }

    // Devuelve true si la letra es un signo de puntuación
    private function esSigno(string $letra): bool
    {
        switch ($letra) {
            case '.':
            case ',':
            case ';':
            case ':':
            case '!':
            case '?':
            case '¡':
            case '¿':
            case "'":
            case '"':
            case '(':
            case ')':
                return true;
            default:
                return false;
        }
    }

    public function generar(): string
    {
        $acronimo = '';
        $nuevaPalabra = true; 
        $total = mb_strlen($this->frase);

        for ($i = 0; $i < $total; $i++) {
            $letra = mb_substr($this->frase, $i, 1);

            if ($letra == ' ' || $letra == '-') {
                
                $nuevaPalabra = true;
            } else if ($this->esSigno($letra)) {
                // Los demás signos se ignoran
            } else if ($nuevaPalabra) {
                $acronimo = $acronimo . mb_strtoupper($letra);
                $nuevaPalabra = false;
            }
        }

        return $acronimo;
    }
}