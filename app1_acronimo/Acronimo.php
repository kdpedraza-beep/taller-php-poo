<?php
class Acronimo
{
    private string $frase;

    public function __construct(string $frase)
    {
        $this->frase = $frase;
    }

    // Es signo todo lo que no sea una letra ni un número
    private function esSigno(string $letra): bool
    {
        return !preg_match('/[\p{L}\p{N}]/u', $letra);
    }

    // Pasa a mayúscula una letra 
    private function mayuscula(string $letra): string
    {
        $minusculas = ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'];
        $mayusculas = ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'];

        for ($i = 0; $i < count($minusculas); $i++) {
            if ($letra == $minusculas[$i]) {
                return $mayusculas[$i];
            }
        }
        return strtoupper($letra);
    }

    public function generar(): string
    {
        $acronimo = '';
        $nuevaPalabra = true;

        // Separa la frase en letras 
        $letras = preg_split('//u', $this->frase, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($letras as $letra) {
            if ($letra == ' ' || $letra == '-') {
                $nuevaPalabra = true;
            } else if ($this->esSigno($letra)) {
                // Los demás signos se ignoran
            } else if ($nuevaPalabra) {
                $acronimo = $acronimo . $this->mayuscula($letra);
                $nuevaPalabra = false;
            }
        }

        return $acronimo;
    }
}