<?php

function conectarDb(): mysqli {
    $db = mysqli_connect('127.0.0.1','root','','pruebatecnica');

    if(!$db) {
        echo "Error: No se pudo conectar a MySQL.";
        echo "errno de depuración: " . mysqli_connect_errno();
        echo "error de depuración: " . mysqli_connect_error();
        exit;
    }

    return $db;
}