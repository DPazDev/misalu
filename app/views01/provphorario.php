<?php
include ("../../lib/jfunciones.php");
$IdProveedorPersona=$_REQUEST['id_proveedorp'];
//echo"<h1>idproveedor: $IdProveedorPersona </h1>";

$DataProveedor="select
especialidades_medicas.especialidad_medica,
s_p_proveedores.*
from especialidades_medicas,s_p_proveedores,proveedores
where
proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and s_p_proveedores.activar=1 and proveedores.id_proveedor=$IdProveedorPersona;";
$ResulDataProveedor=ejecutar($DataProveedor);
$DataProv=asignar_a($ResulDataProveedor);
$data=$DataProv['especialidad_medica'];
$horario=trim($DataProv['horario']);
$NumCaracter=strlen($horario);
$exiteHorario=1;
  if($horario=='' || $NumCaracter=='0')
    {
      $horario='Horario indefinido';
      $exiteHorario=0;
    }

$ResticionesDias=0;
$ExiteHorarioActivo=0;
$lunes='';
$martes='';
$miercoles='';
$jueves='';
$viernes='';
$sabado='';
$domingo='';

//LUNES
    if ($DataProv['lunes']==1) {
        if($DataProv['nplunes']>'0'){
            $npacinetes=$DataProv['nplunes']." Paciente/Estudio";
            $ResticionesDias++;
          }
        else {$npacinetes='Indefinido';  }
        $lunes="<div id='dlunes'>Lunes ($npacinetes)</div>";
        $ExiteHorarioActivo++;
      }

//MARTES
    if ($DataProv['martes']==1) {
          if($DataProv['npmartes']>'0'){
              $npacinetes=$DataProv['npmartes']." Paciente/Estudio";
              $ResticionesDias++;
                }
              else {$npacinetes='Indefinido';  }
            $martes="<div id='dmartes'>Martes ($npacinetes)</div>";
            $ExiteHorarioActivo++;
        }

//Miercoles
    if ($DataProv['miercoles']==1) {
          if($DataProv['npmiercole']>'0'){
              $npacinetes=$DataProv['npmiercole']." Paciente/Estudio";
              $ResticionesDias++;
                }
          else {$npacinetes='Indefinido';  }
          $miercoles="<div id='dmiercoles'>Miercoles ($npacinetes)</div>";
          $ExiteHorarioActivo++;
        }

//JUEVES
    if ($DataProv['jueves']==1) {
          if($DataProv['npjueves']>'0'){
              $npacinetes=$DataProv['npjueves']." Paciente/Estudio";
              $ResticionesDias++;
                }
          else {$npacinetes='Indefinido';  }
          $jueves="<div id='djueves'>Jueves ($npacinetes)</div>";
          $ExiteHorarioActivo++;
        }

//VIERNES
    if ($DataProv['viernes']==1) {
          if($DataProv['npviernes']>'0'){
              $npacinetes=$DataProv['npviernes']." Paciente/Estudio";
              $ResticionesDias++;
                }
          else {$npacinetes='Indefinido';  }
          $viernes="<div id='dviernes'>Viernes ($npacinetes)</div>";
          $ExiteHorarioActivo++;
        }

//SABADO
    if ($DataProv['sabado']==1) {
          if($DataProv['npsabado']>'0'){
              $npacinetes=$DataProv['npsabado']." Paciente/Estudio";
              $ResticionesDias++;
                }
          else {$npacinetes='Indefinido';  }
          $sabado="<div id='dsabado'>Sabado ($npacinetes)</div>";
          $ExiteHorarioActivo++;
        }

//DOMINGO
    if ($DataProv['domingo']==1) {
          if($DataProv['npdomingo']>'0'){
              $npacinetes=$DataProv['npdomingo']." Paciente/Estudio";
              $ResticionesDias++;
                }
          else {$npacinetes='Indefinido';  }
          $domingo="<div id='ddomingo'> Domingo ($npacinetes)</div>";
          $ExiteHorarioActivo++;
        }



if($ExiteHorarioActivo>0){
    echo "<div id='semana' class=\"horarios\"> $lunes $martes $miercoles $jueves $viernes $sabado $domingo </div>";
}else {
  $styloPositivo='style="background: #e2fdf4; color: #0ebd84; font-weight: bold; padding: 15px; border: 2px solid #bdfae6; border-radius: 6px;"';
  $styloAlerta='style="background: #fcdbe6; color: #f15c8e; font-weight: bold; padding: 15px; border: 2px solid #fac9d9; border-radius: 6px;"';

if($exiteHorario==0)
    {
      $stylo=$styloAlerta;
    }else{
      $stylo=$styloPositivo;
    }
    $mesaje="<div $styloAlerta >No existe una restricción horario definida para este proveedor</div>";
    $horario="<div $stylo id='horario'>$horario</div>";
    echo " $mesaje <br> $horario";
}

////////////////////////////////////DEFINIR BLOQUEO DE HORARIO SEGUN USUARIO////////////////////////
////////////////////////////////////DEFINIR BLOQUEO DE HORARIO SEGUN USUARIO////////////////////////


?>
