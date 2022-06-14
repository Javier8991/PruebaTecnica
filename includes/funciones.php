<?php

include 'app.php';

function incluirTemplate(string $nombre, bool $inicio=false) {
    include TEMPLATES_URL . "/${nombre}.php";
}

function autenticado(): bool {
    session_start();

    if($_SESSION['login']) {
        return true;
    }
    return false;
}