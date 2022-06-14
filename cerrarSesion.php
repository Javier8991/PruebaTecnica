<?php
session_start();
// Se borra el arreglo de $_SESSION para que las validaciones ayuden a que la sesión se cierre
$_SESSION = [];

header('Location: /');