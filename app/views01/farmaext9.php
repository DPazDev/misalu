<?
include ("../../lib/jfunciones.php");
sesion();
$barra1=$_POST['codba1'];
$elnom1=$_POST['nomb1'];
$pre1=$_POST['costo1'];
$tipoex=4;
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$guardaarti=("insert into examenes_bl(id_tipo_examen_bl,examen_bl,fecha_creado,hora_creado,honorarios,codigo_barra) 
          values($tipoex,upper('$elnom1'),'$fecha','$hora','$pre1','$barra1');");
$repuesta=ejecutar($guardaarti);

/* **** Se registra lo que hizo el usuario**** **/


$log="REGISTRO examenes_bl tipo examen $tipoex, nombre $elnom1 precio $pre1, codigo barra $barra1  ";
logs($log,$ip,$elid);

/* **** Fin de lo que hizo el usuario **** */
?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class="titulo_seccion">El art&iacute;culo se ha registrado existosamente!!!!</td>
         <td class="titulo_seccion" title="Cerrar modulo de inclusi&oacute;n"><label class="boton" style="cursor:pointer" onclick="cerraMOD(); return false;" >Cerrar</label></td>
     </tr>
</table>

