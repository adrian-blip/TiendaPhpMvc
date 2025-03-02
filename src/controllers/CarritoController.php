<?php

require_once 'models/producto.php';

class CarritoController
{
    // Muestra el contenido del carrito
    public function index()
    {
        $carrito = $_SESSION['carrito'] ?? [];
        require_once 'views/carrito/index.php';
    }

    // Agrega un producto al carrito
    public function add()
    {
        if (!isset($_GET['id'])) {
            header('Location:' . base_url);
            exit();
        }

        $producto_id = $_GET['id'];
        $encontrado = false;

        // Verifica si el producto ya está en el carrito
        if (!empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $indice => $elemento) {
                if ($elemento['id_producto'] == $producto_id) {
                    $_SESSION['carrito'][$indice]['unidades']++;
                    $encontrado = true;
                    break;
                }
            }
        }

        // Si el producto no está en el carrito, lo agregamos
        if (!$encontrado) {
            $producto = new Producto();
            $producto->setId($producto_id);
            $producto = $producto->getProduct();

            if ($producto) {
                $_SESSION['carrito'][] = [
                    "id_producto" => $producto->id,
                    "precio" => $producto->precio,
                    "unidades" => 1,
                    "producto" => $producto
                ];
            }
        }

        header('Location:' . base_url . "carrito/index");
    }

    // Incrementa la cantidad de un producto en el carrito
    public function up()
    {
        if (isset($_GET['index'])) {
            $_SESSION['carrito'][$_GET['index']]['unidades']++;
        }
        header('Location:' . base_url . "carrito/index");
    }

    // Reduce la cantidad de un producto en el carrito
    public function down()
    {
        if (isset($_GET['index'])) {
            $index = $_GET['index'];
            $_SESSION['carrito'][$index]['unidades']--;
            
            // Si las unidades llegan a 0, eliminamos el producto del carrito
            if ($_SESSION['carrito'][$index]['unidades'] <= 0) {
                unset($_SESSION['carrito'][$index]);
            }
        }
        header('Location:' . base_url . "carrito/index");
    }

    // Elimina un producto específico del carrito
    public function remove()
    {
        if (isset($_GET['index'])) {
            unset($_SESSION['carrito'][$_GET['index']]);
        }
        header('Location:' . base_url . "carrito/index");
    }

    // Vacía completamente el carrito
    public function delete_all()
    {
        $_SESSION['carrito'] = [];
        header('Location:' . base_url . "carrito/index");
    }
}
