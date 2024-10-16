<?php
include ("../../lib/jfunciones.php");
include_once ("../../lib/Excel/reader.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");

?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Archivos cargados en el sistema</td>  
     </tr>
</table>
<?php 
  $nadahay = 0;
  
  $directory = "../../files/";
  $cuantarch = 0;
  $files = glob($directory . "*");
  if ($files){
     $filecount = count($files);
  }
  
  if($filecount >= 1){
    $nadahay = 1;
  }
  if($nadahay == 1){
?>
 <table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
     <tr>
		 <th class="tdtitulos">Archivos Cargado al Sistema</th>  
		 
    </tr>	
		<tr>
	       <td class="tdcampos"><?echo mostrar_archivo("../../files"); ?></td>
	   </tr>
		
</table>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Ingresar nombre del archivo:</td>  
     </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>

     <tr>
        <td class="tdtitulos">Nombre:</td>
        <td class="tdcampos"><input type="text" id="nombarchivo" class="campos" size="35"></td>
     </tr>
     </tr> 
     <tr>
     <br>
        <td  title="Procesar Facturas por Lote"> <br><label class="boton" style="cursor:pointer" onclick="ProcefacLote(); return false;" >Procesar</label></td>
        <td> <br><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
     </tr>
 
</table>   
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<br><br>
<div id='facturaslote'></div>

<?php }else{?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">No hay Archivos cargados en el sistema</td>  
     </tr>
</table>
		
</table>
<?php }?>
