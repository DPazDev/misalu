<?php
include ("../../lib/jfunciones.php");
sesion();
$proceso=$_REQUEST['proceso'];
$autorizados=$_REQUEST['autorizado'];
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];


/* **** MODIFICO EL ESTADO DEL PROCESO **** */
$mod_proceso=("update procesos set id_estado_proceso='18',no_clave='', nu_planilla='', fecha_modificado='$fechacreado',hora_modificado='$hora' where procesos.id_proceso=$proceso");
$fmod_proceso=ejecutar($mod_proceso); 

/*  ** INSERTAR DATOS EN PROCESO_DONATIVO ** */
echo $i_proceso_donativo=("insert into procesos_donativos (id_proceso, id_admin, id_responsable_donativo, fecha_donativo, hora_donativo) VALUES ('$proceso', '$admin', '$autorizados', '$fechacreado', '$hora' )"); 
$r_proceso_dontivo=ejecutar($i_proceso_donativo); 


$log="CAMBIO EL ESTADO DE LA ORDEN CON NUMERO $proceso A $estado_proceso ";
logs($log,$ip,$admin);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso ?> ha sido enviado a Donativo exitosamente 
	            <a href="#" OnClick="ir_principal();" class="boton">salir</a>	
               <label title="Imprimir acta" class="boton" style="cursor:pointer" onclick="impactadonativo(<?php echo $proceso

              ?>)" >Acta</label></td>   

<td colspan=1 class="tdcamposr">
              <tr>
</tr>	

	
</table>
