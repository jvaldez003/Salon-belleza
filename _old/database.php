<?php
$db = mysqli_connect('localhost:3308', 'root', '', 'appsalon');

if(!$db) {
    echo "Hubo un error de conexión";
    exit;
}