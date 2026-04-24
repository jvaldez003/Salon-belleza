<?php
function obtener_servicios() {
    try {
        require __DIR__ . '/../database.php';
        $sql = "SELECT * FROM servicios;";
        $consulta = mysqli_query($db, $sql);
        return $consulta;
    } catch (\Throwable $th) {
        var_dump($th);
    }
}