<?php

require 'flight/Flight.php';

//Conexion a la base de datos
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=api','root',''));

//metodo a utilizar - URL a pedir la informacion
Flight::route('GET /alumnos', function () {
    
    //Realizamos la consulta
    $consulta= Flight::db()->prepare("SELECT * FROM alumnos");

    //Ejecutamos la consulta 
    $consulta->execute(); 

    //Devuelve todos los registros encontrados
    $datos=$consulta->fetchAll(); 

    //Pedimos que esos datos recibidos nos lo muestre en formato JSON 
    Flight::json($datos); 
});


Flight::route('POST /llenarAlumnos', function(){

    //Dato nombre recibido por la peticion POST
    $nombres=(Flight::request()->data->nombre); 
    //Dato apellido recibido por la peticion POST
    $apellidos=(Flight::request()->data->apellido); 

    //CONSULTA SQL 
    $sql="INSERT INTO alumnos (nombres,apellidos) VALUES (?,?)"; 

    //Enviamos la consulta SQL por parametro
    $consulta= Flight::db()->prepare($sql);
    //Este primero reemplaza el primer valor "?" de la consulta
    $consulta->bindParam(1, $nombres);

    //Este segundo reemplaza el segundo valor "?" de la consulta
    $consulta->bindParam(2, $apellidos); 

    //Ejecutamos la consulta
    $consulta->execute(); 

    Flight::jsonp(["Alumno agregado"]); 




}); 

Flight::route('DELETE /borrarAlumno', function(){

    $id=(Flight::request()->data->id); 
    //print_r($id); 

    //CONSULTA SQL 
    $sql="DELETE FROM alumnos WHERE id=?"; 

    $consulta= Flight::db()->prepare($sql);

    //Este primero reemplaza el valor id de la consulta
    $consulta->bindParam(1, $id);

    //Ejecutamos la consulta
    $consulta->execute();

    Flight::jsonp(["Alumno borrado"]); 


}); 

Flight::route('PUT /actualizarAlumno', function(){

    $id=(Flight::request()->data->id); 
    $nombres=(Flight::request()->data->nombre); 
    $apellidos=(Flight::request()->data->apellido);

    $sql="UPDATE alumnos SET nombres=?,apellidos=? WHERE id=?"; 

    $consulta= Flight::db()->prepare($sql);

    $consulta->bindParam(1, $nombres); 
    $consulta->bindParam(2, $apellidos); 
    $consulta->bindParam(3, $id);
    

    //Ejecutamos la consulta
    $consulta->execute(); 


    Flight::jsonp(["Alumno Actualizado"]); 
    
    


}); 

Flight::route('GET /alumnos/@id', function ($id) { 

    $sql="SELECT * FROM alumnos WHERE id=? ";
    $consulta= Flight::db()->prepare($sql);
    $consulta->bindParam(1, $id); 

    $consulta->execute(); 
    $dato=$consulta->fetchAll(); 
    Flight::json($dato); 
    






}); 

Flight::start();
