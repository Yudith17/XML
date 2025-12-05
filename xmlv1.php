<?php
$ies = [];

$udp1 = ['ANALISIS Y DISEÑO DE SISTEMAS',
'INTRODUCCIÓN DE BASES DE DATOS',
'FUNDAMENTOS DE PROGRAMACION', 
'REDES E INTERNET',
'ARQUITECTTURA DE COMPUTADORAS',
'COMUNICACIÓN ORAL',
'APLICACIONES EN INTERNET'];
$udp2 = ['INTERPRETACION Y PRODUCCION DE TEXTO',
'OFIMATICA','PROGRAMACION ORIENTADA A OBJETOS', 
'METODOLOGIA DE DESARROLLO DE SOFTWARE',
'APLICACIONES SISTEMATIZADAS',
'ARQUITECTURA DE SERVIDORES WEB',
'TALLER DE BASE DE DATOS'];
$udp3 = ['ADMINISTRACION DE BASE DE DATOS',
'PROGRAMACION DE APLICACIONES WEB',
'DISEÑO DE APLICACIONES WEB',
'GESTION DE PROYECTOS DE APLICACIONES WEB',
'GESTION DE BASES DE DATOS',
'GESTION DE SERVIDORES WEB',
'GESTION DE APLICACIONES WEB'];
$udp4 = ['PROGRAMACIONDE APLIACCIONES MOVILES',
'PROGRAMACION DE APLICACIONES MOVILES',
'GESTION DE PROYECTOS DE APLICACIONES MOVILES',
'GESTION DE BASES DE DATOS','GESTION DE SERVIDORES MOVILES',
'GESTION DE APLICACIONES MOVILES','GESTION DE APLICACIONES HIBRIDAS'];
$udp5 = ['DISEÑO DE APLICACIONES DE ESCRITORIO',
'PROGRAMACION DE APLICACIONES DE ESCRITORIO',
'GESTION DE PROYECTOS DE APLICACIONES DE ESCRITORIO',
'GESTION DE BASES DE DATOS',
'GESTION DE SERVIDORES DE ESCRITORIO',
'GESTION DE APLICACIONES DE ESCRITORIO',
'GESTION DE APLICACIONES HIBRIDAS'];
$ud6 = ['TALLER DE INTEGRACION I',
'TALLER DE INTEGRACION II',
'TALLER DE INTEGRACION III',
'TALLER DE INTEGRACION IV',
'TALLER DE INTEGRACION V',
'TALLER DE INTEGRACION VI',
'TALLER DE INTEGRACION VII'];


//----------------------COMENTARIOS  -------//
$p1 =[];
$p1['nombre'] = 'I';
$p1['unidades_didacticas'] = $udp1;
 
$p2 =[];
$p2['nombre'] = 'II';
$p2['unidades_didacticas'] = $udp2;

$p3 =[];
$p3['nombre'] = 'III';
$p3['unidades_didacticas'] = $udp3;

$p4 =[];
$p4['nombre'] = 'IV';
$p4['unidades_didacticas'] = $udp4;

$p5 =[];
$p5['nombre'] = 'V';
$p5['unidades_didacticas'] = $udp5;

$p6 =[];
$p6['nombre'] = 'VI';
$p6['unidades_didacticas'] = $ud6;


//----------------------------MODULOS  -------//
$m1 = array();
$m1['nombre'] = 'ANALISIS Y DISEÑO DE SISTEMAS WEB';
$m1['periodos'] = array($p1, $p2);

$m2 = array();
$m2['nombre'] = 'DESARROLLO DE APLIACIONES WEB';
$m2['periodos'] = array($p3, $p4);

$m3 = array();
$m3['nombre'] = 'DISEÑO DE SERVICIOS WEB';
$m3['periodos'] = array($p5, $p6);

//---------------------------PROGRAMAS DE ESTUDIO -------//
$pe1 = array();
$pe1['nombre'] = "DISEÑO Y PROGRAMACION WEB";
$pe1['modulos'] = [$m1, $m2, $m3];

 //-------------Enfermeria----
$pe2 = array();
$pe2['nombre'] = "Enfermería Técnica";
$pe2['modulos'] = [];
//----------- industrias-----
$pe4 = array();
$pe4['nombre'] = "Industrias de Alimentos y Bebidas";
$pe4['modulos'] = [];
 //----------mecatronica-----
$pe3 = array();
$pe3['nombre'] = "Mecatrónica Automotriz";
$pe3['modulos'] = [];

//-----------agropecuaria-----
$pe5 = array();
$pe5['nombre'] = "Producción Agropecuaria";
$pe5['modulos'] = [];


$ies['nombre'] = "IES PÚBLICO HUANTA";
$ies['programas_estudio'] = array($pe1, $pe2, $pe3, $pe4, $pe5);


$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;


$et1 = $xml->createElement('ies');
$xml->appendChild($et1);

//------recorrer las carreras
    $nombre_ies = $xml->createElement("nombre", $ies['nombre']);
    $programas_ies = $xml->createElement("programas_estudio");
    $et1->appendChild($nombre_ies);
    
    foreach ($ies ["programas_estudio"] as $indice => $PEs) {
        $num_pe = $xml->createElement("pe".$indice+1);
        $nombre_pe = $xml->createElement("nombre", $PEs['nombre']);
        $num_pe->appendChild($nombre_pe);
        $programas_ies->appendChild($num_pe);
        foreach ($PEs['modulos'] as $indice_modulo=> $Modulo) {
            $num_mod = $xml->createElement("mod".$indice_modulo+1);
            $nom_mod = $xml->createElement("nombre", $Modulo['nombre']);
            $num_mod->appendChild($nom_mod);
            $num_pe->appendChild($num_mod);
            $periodos = $xml->createElement("periodos");
            foreach ($Modulo['periodos'] as $indice_periodo => $Periodo) {
                $num_per = $xml->createElement("per".$indice_periodo+1);
                $nom_per = $xml->createElement("nombre", $Periodo['nombre']);
                $num_per->appendChild($nom_per);
                $periodos->appendChild($num_per);
                $uds = $xml->createElement("unidades_didacticas");
                foreach ($Periodo['unidades_didacticas'] as $indice_ud => $Ud) {
                    $num_ud = $xml->createElement("per".$indice_ud+1);
                    $nom_ud = $xml->createElement("nombre", $Ud);
                    $num_ud->appendChild($nom_ud);
                    $uds->appendChild($num_ud);
                }
                $num_pe->appendChild($num_per);
            }  
        }
        $et1->appendChild($programas_ies);
    }


//-------crear archivo para ponerle nombre
$archivo = "ies.xml";
$xml->save($archivo);

?>