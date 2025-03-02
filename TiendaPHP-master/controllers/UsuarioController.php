<?php

require_once 'models/usuario.php';

class UsuarioController
{
    public function index()
    {
        echo 'Controlador Usuario, Acción Index';
    }

    // Muestra el formulario de registro
    public function register()
    {
        require_once 'views/usuario/register.php';
    }

    // Procesa el registro de un nuevo usuario
    public function save()
    {
        if (isset($_POST)) {
            $nombre = $_POST['name'] ?? false;
            $apellidos = $_POST['surname'] ?? false;
            $email = $_POST['email'] ?? false;
            $password = $_POST['password'] ?? false;

            $errors = [];

            // Validación del nombre
            if (empty($nombre) || is_numeric($nombre) || preg_match('/[0-9]/', $nombre)) {
                $errors['name'] = 'El nombre no es válido';
            }

            // Validación de los apellidos
            if (empty($apellidos) || is_numeric($apellidos) || preg_match('/[0-9]/', $apellidos)) {
                $errors['surname'] = 'Los apellidos no son válidos';
            }

            // Validación del email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'El email no es válido';
            }

            // Validación de la contraseña
            if (empty($password)) {
                $errors['password'] = 'La contraseña no puede estar vacía';
            }

            if (empty($errors)) {
                $usuario = new Usuario();
                $usuario->setNombre($nombre);
                $usuario->setApellidos($apellidos);
                $usuario->setEmail($email);
                $usuario->hashPassword($password);
                
                $save = $usuario->save();
                $_SESSION['register'] = $save ? 'completed' : 'failed';
            } else {
                $_SESSION['register'] = 'failed';
                $_SESSION['errors'] = $errors;
            }
        }
        
        header('Location:' . base_url . 'usuario/register');
    }

    // Procesa el inicio de sesión
    public function login()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $usuario = new Usuario();
            $usuario->setEmail($_POST['email']);
            $usuario->setPassword($_POST['password']);
            $identity = $usuario->login();

            if (!empty($identity)) {
                $_SESSION['identity'] = $identity;
                if ($identity->rol == 'admin') {
                    $_SESSION['admin'] = true;
                }
            } else {
                $_SESSION['error_login'] = '¡Ha habido un error al iniciar sesión!';
            }
        }
        
        header('Location: ' . base_url);
    }

    // Cierra la sesión del usuario
    public function logout()
    {
        unset($_SESSION['identity'], $_SESSION['admin']);
        header('Location: ' . base_url);
    }
}
