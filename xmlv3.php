<?php

$conexion = new mysqli('localhost', 'root', '');
if ($conexion->connect_errno) {
    die("Fallo al conectar a MySQL: ". $conexion->connect_error);
}

// 1. Crear la BD si no existe
$conexion->query("CREATE DATABASE IF NOT EXISTS ies_xml CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// 2. Usar la BD
$conexion->select_db("ies_xml");

// 3. Crear tablas
$conexion->query("
CREATE TABLE IF NOT EXISTS sigi_programas_estudio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50),
    tipo VARCHAR(100),
    nombre VARCHAR(255)
)");

$conexion->query("
CREATE TABLE IF NOT EXISTS sigi_planes_estudio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_programa_estudios INT,
    nombre VARCHAR(255),
    resolucion VARCHAR(255),
    fecha_registro DATETIME,
    perfil_egresado TEXT NULL, 
    FOREIGN KEY(id_programa_estudios) REFERENCES sigi_programas_estudio(id)
)");

$conexion->query("
CREATE TABLE IF NOT EXISTS sigi_modulo_formativo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_plan INT,
    descripcion TEXT,
    nro_modulo VARCHAR(50),
    FOREIGN KEY(id_plan) REFERENCES sigi_planes_estudio(id)
)");

$conexion->query("
CREATE TABLE IF NOT EXISTS sigi_periodos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_modulo INT,
    descripcion TEXT,
    FOREIGN KEY(id_modulo) REFERENCES sigi_modulo_formativo(id)
)");

$conexion->query("
CREATE TABLE IF NOT EXISTS sigi_unidades_didacticas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_periodo INT,
    nombre VARCHAR(255),
    creditos_teorico INT,
    creditos_practico INT,
    tipo VARCHAR(50),
    horas_semanal INT,
    horas_semestral INT,
    FOREIGN KEY(id_periodo) REFERENCES sigi_periodos(id)
)");


$xml = simplexml_load_file('ies_db.xml') or die ('Error: no se cargo el xml. Escribe correctamente el archivo');
/*
echo $xml->pe_1->nombre."<br>";
echo $xml->pe_2->nombre;
*/
foreach ($xml as $i_pe => $pe) {
    echo 'Codigo: '.$pe->codigo.'<br>';
    echo 'Tipo: '.$pe->tipo.'<br>';
    echo 'Nombre: '.$pe->nombre.'<br>';
    $consulta = "INSERT INTO sigi_programas_estudio ( codigo, tipo, nombre) 
        VALUES ('{$pe->codigo}', '{$pe->tipo}', '{$pe->nombre}')";
        $conexion->query($consulta);
        $id_programa_estudios = $conexion->insert_id;
  
    foreach ($pe->planes_estudio[0] as $i_ple => $plan) {
        echo '--'.$plan->nombre.'<br>';
        echo '--'.$plan->resolucion.'<br>';
        echo '--'.$plan->fecha_registro.'<br>';

        $consulta = "INSERT INTO sigi_planes_estudio (id_programa_estudios, nombre, resolucion, fecha_registro, perfil_egresado) 
        VALUES ('$id_programa_estudios','{$plan->nombre}', '{$plan->resolucion}', '{$plan->fecha_registro}', '{$plan->perfil_egresado}')";
        $conexion->query($consulta);
        $id_plan = $conexion->insert_id;
        foreach ($plan->modulos_formativos[0] as $id_mod => $modulo) {
            echo '---'. $modulo->descripcion.'<br>';
            echo '---'. $modulo->nro_modulo.'<br>';
            $consulta = "INSERT INTO sigi_modulo_formativo (descripcion, nro_modulo) 
            VALUES ('{$modulo->descripcion}', '{$modulo->nro_modulo}')";
            $conexion->query($consulta);
            $id_modulo = $conexion->insert_id;
            foreach ($modulo->periodos[0] as $id_per => $periodo) {
                echo '---'. $periodo->descripcion.'<br>';
                  $consulta = "INSERT INTO sigi_periodos (descripcion) 
                  VALUES ('{$modulo->descripcion}')";
                  $conexion->query($consulta);
                  $id_per = $conexion->insert_id;
                foreach ($periodo->unidades_didacticas[0] as $id_ud => $ud) {
                    echo '---'. $ud->nombre.'<br>';
                    echo '---'. $ud->creditos_teorico.'<br>';
                    echo '---'. $ud->creditos_practico.'<br>';
                    echo '---'. $ud->tipo.'<br>'; 
                    echo '---'. $ud->horas_semanal.'<br>'; 
                    echo '---'. $ud->horas_semestral.'<br>'; 
                    $consulta = "INSERT INTO sigi_unidades_didacticas (nombre, creditos_teorico,creditos_practico,tipo,horas_semanal,horas_semestral) 
                    VALUES ('{$ud->nombre}', '{$ud->creditos_teorico}', '{$ud->creditos_practico}', '{$ud->tipo}', '{$ud->horas_semanal}', '{$ud->horas_semestral}')";
                    $conexion->query($consulta);
                    $id_ud = $conexion->insert_id;
                }
            }
        }
        
    }
}