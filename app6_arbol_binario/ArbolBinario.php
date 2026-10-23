<?php
class ArbolBinario
{
    private string $raiz = '';
    private array $izquierda = [];   // $izquierda['A'] = 'B' significa que B es hijo izquierdo de A
    private array $derecha = [];     // igual, para el hijo derecho

    // Agrega un nodo nuevo, todavía sin hijos 
    private function agregarNodo(string $valor): void
    {
        $this->izquierda[$valor] = '';
        $this->derecha[$valor] = '';
    }

    public function getIzquierda(string $nodo): string
    {
        return $this->izquierda[$nodo];
    }

    public function getDerecha(string $nodo): string
    {
        return $this->derecha[$nodo];
    }



    private function insertar(array $orden, array $posicion): void
    {
        $this->raiz = $orden[0];
        $this->agregarNodo($orden[0]);

        for ($i = 1; $i < count($orden); $i++) {
            $nuevo = $orden[$i];
            $this->agregarNodo($nuevo);

            $actual = $this->raiz;
            $colocado = false;

            while (!$colocado) {
                if ($posicion[$nuevo] < $posicion[$actual]) {
                    if ($this->izquierda[$actual] === '') {
                        $this->izquierda[$actual] = $nuevo;
                        $colocado = true;
                    } else {
                        $actual = $this->izquierda[$actual];
                    }
                } else {
                    if ($this->derecha[$actual] === '') {
                        $this->derecha[$actual] = $nuevo;
                        $colocado = true;
                    } else {
                        $actual = $this->derecha[$actual];
                    }
                }
            }
        }
    }

    //el preorden ya pone cada padre antes que sus hijos
    public function desdePreIn(array $pre, array $in): bool
    {
        $posicion = [];
        for ($i = 0; $i < count($in); $i++) {
            $posicion[$in[$i]] = $i;
        }

        $this->insertar($pre, $posicion);
        return true;
    }

    // el postorden leído al revés pone cada padre antes que sus hijos
    public function desdePostIn(array $post, array $in): bool
    {
        $posicion = [];
        for ($i = 0; $i < count($in); $i++) {
            $posicion[$in[$i]] = $i;
        }

        $alReves = [];
        for ($i = count($post) - 1; $i >= 0; $i--) {
            $alReves[] = $post[$i];
        }

        $this->insertar($alReves, $posicion);
        return true;
    }

    // Preorden + postorden: solo se obtiene un árbol único si cada nodo tiene 0 o 2 hijos.
    // La pila guarda los nodos cuyo subárbol todavía no está completo.
    public function desdePrePost(array $pre, array $post): bool
    {
        $this->raiz = $pre[0];
        $this->agregarNodo($pre[0]);

        $pila = [$pre[0]];
        $tope = 1;      // cuántos nodos hay en la pila
        $j = 0;         // posición actual dentro del postorden

        for ($i = 1; $i < count($pre); $i++) {
            $nuevo = $pre[$i];
            $this->agregarNodo($nuevo);

            // Un nodo está completo cuando aparece en el postorden: se saca de la pila
            while ($tope > 0 && $j < count($post) && $pila[$tope - 1] === $post[$j]) {
                $tope--;
                $j++;
            }

            if ($tope == 0) {
                return false;
            }

            // El nuevo nodo es hijo del que quedó arriba de la pila
            $padre = $pila[$tope - 1];
            if ($this->izquierda[$padre] === '') {
                $this->izquierda[$padre] = $nuevo;
            } else if ($this->derecha[$padre] === '') {
                $this->derecha[$padre] = $nuevo;
            } else {
                return false;
            }

            $pila[$tope] = $nuevo;
            $tope++;
        }
        return true;
    }

    

    public function preorden(): array
    {
        $resultado = [];
        if ($this->raiz === '') {
            return $resultado;
        }

        $pila = [$this->raiz];
        $tope = 1;
        while ($tope > 0) {
            $tope--;
            $nodo = $pila[$tope];
            $resultado[] = $nodo;

 
            if ($this->derecha[$nodo] !== '') {
                $pila[$tope] = $this->derecha[$nodo];
                $tope++;
            }
            if ($this->izquierda[$nodo] !== '') {
                $pila[$tope] = $this->izquierda[$nodo];
                $tope++;
            }
        }
        return $resultado;
    }

    public function inorden(): array
    {
        $resultado = [];
        $pila = [];
        $tope = 0;
        $actual = $this->raiz;

        while ($actual !== '' || $tope > 0) {
            // Se baja por la izquierda guardando el camino
            while ($actual !== '') {
                $pila[$tope] = $actual;
                $tope++;
                $actual = $this->izquierda[$actual];
            }
            $tope--;
            $actual = $pila[$tope];
            $resultado[] = $actual;
            $actual = $this->derecha[$actual];
        }
        return $resultado;
    }

    public function postorden(): array
    {
        $resultado = [];
        if ($this->raiz === '') {
            return $resultado;
        }

        // derecha izquierda y luego se lee al revés
        $visitados = [];
        $pila = [$this->raiz];
        $tope = 1;
        while ($tope > 0) {
            $tope--;
            $nodo = $pila[$tope];
            $visitados[] = $nodo;

            if ($this->izquierda[$nodo] !== '') {
                $pila[$tope] = $this->izquierda[$nodo];
                $tope++;
            }
            if ($this->derecha[$nodo] !== '') {
                $pila[$tope] = $this->derecha[$nodo];
                $tope++;
            }
        }

        for ($i = count($visitados) - 1; $i >= 0; $i--) {
            $resultado[] = $visitados[$i];
        }
        return $resultado;
    }

    // la raíz es 0, sus hijos 1, y así sucesivamente
    public function niveles(): array
    {
        $niveles = [];
        if ($this->raiz === '') {
            return $niveles;
        }

        $pila = [$this->raiz];
        $profundidad = [0];
        $tope = 1;
        while ($tope > 0) {
            $tope--;
            $nodo = $pila[$tope];
            $nivel = $profundidad[$tope];
            $niveles[$nodo] = $nivel;

            if ($this->derecha[$nodo] !== '') {
                $pila[$tope] = $this->derecha[$nodo];
                $profundidad[$tope] = $nivel + 1;
                $tope++;
            }
            if ($this->izquierda[$nodo] !== '') {
                $pila[$tope] = $this->izquierda[$nodo];
                $profundidad[$tope] = $nivel + 1;
                $tope++;
            }
        }
        return $niveles;
    }
}