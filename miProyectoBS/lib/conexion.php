<?php
    //variables de conexión
    $servername = "bestmotors.c9oegoi0eh6x.us-east-1.rds.amazonaws.com";
    $username = "admin";
    $password = "12345678";
    $database = "bestmotors";

    //crear la cadena de conexión
    $conexion = new mysqli($servername, $username, $password, $database)
    or die("fallo en la conexion ".$conexion->connect_error);

    ?>