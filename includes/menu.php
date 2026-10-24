<?php
class Menu
{
    private array $opciones = [
        'app1_acronimo'            => 'Acrónimo',
        'app2_fibonacci_factorial' => 'Fibonacci / Factorial',
        'app3_estadistica'         => 'Promedio, mediana y moda',
        'app4_conjuntos'           => 'Conjuntos',
        'app5_binario'             => 'Decimal a binario',
        'app6_arbol_binario'       => 'Árbol binario',
        'app7_calculadora'         => 'Calculadora',
    ];

    public function render(string $base = ''): string
    {
        $html = '<nav class="menu"><ul>';
        $html .= '<li><a href="' . $base . 'index.php">Inicio</a></li>';
        foreach ($this->opciones as $carpeta => $titulo) {
            $html .= '<li><a href="' . $base . $carpeta . '/index.php">' . $titulo . '</a></li>';
        }
        return $html . '</ul></nav>';
    }

    public function getOpciones(): array
    {
        return $this->opciones;
    }
}
