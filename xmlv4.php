<?php
$conexion = new mysqli('localhost', 'root', '', 'para_xml');
if ($conexion->connect_errno) {
    echo "Falllo al conectar a MySQL: (". $conexion->connect_error . ")" . $conexion->connect_error;
}

$xml = simplexml_load_file('ies_db.xml') or die ('Error: no se cargo el xml. Escribe correctamente el archivo');
/*
echo $xml->pe_1->nombre."<br>";
echo $xml->pe_2->nombre;
*/
foreach ($xml as $i_pe => $pe) {
    echo 'Codigo: '.$pe->codigo.'<br>';
    echo 'Tipo: '.$pe->tipo.'<br>';
    echo 'Nombre: '.$pe->nombre.'<br>';
    $consulta = "INSERT INTO sigi_programa_estudios ( codigo, tipo, nombre) 
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
            $consulta = "INSERT INTO sigi_modulo_formativo (id_plan_estudio,descripcion, nro_modulo) 
            VALUES ('{$id_plan}','{$modulo->descripcion}', '{$modulo->nro_modulo}')";
            $conexion->query($consulta);
            $id_modulo = $conexion->insert_id;

            foreach ($modulo->periodos[0] as $id_per => $periodo) {
                echo '---'. $periodo->descripcion.'<br>';
                  $consulta = "INSERT INTO sigi_semestre (id_modulo_formativo, descripcion) 
                  VALUES ('$id_modulo','{$modulo->descripcion}')";
                  $conexion->query($consulta);
                  $id_per = $conexion->insert_id;

                foreach ($periodo->unidades_didacticas[0] as $id_ud => $ud) {
                    echo '---'. $ud->nombre.'<br>';
                    echo '---'. $ud->creditos_teorico.'<br>';
                    echo '---'. $ud->creditos_practico.'<br>';
                    echo '---'. $ud->tipo.'<br>'; 
                    echo '---'. $ud->horas_semanal.'<br>'; 
                    echo '---'. $ud->horas_semestral.'<br>'; 
                    $consulta = "INSERT INTO sigi_unidad_didactica (id_semestre, nombre, creditos_teorico,creditos_practico,tipo) 
                    VALUES ('$id_per','{$ud->nombre}', '{$ud->creditos_teorico}', '{$ud->creditos_practico}', '{$ud->tipo}')";
                    $conexion->query($consulta);
                    $id_ud = $conexion->insert_id;
                }
            }
        }
        
    }
}