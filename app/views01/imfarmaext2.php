<?
include ("../../lib/jfunciones.php");
$operacion=$_POST['operacion'];
$tipelimina=$_SESSION['operaelimina'];
$buscarpro=("select * from procesos where id_proceso=$operacion;");
$repbuscarpro=ejecutar($buscarpro);
$cuantos=num_filas($repbuscarpro);
if($cuantos==0){
  $mensaje1='El proceso no existe!!';
}
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>

       <?  if($cuantos==0){
               echo " <tr>
                        <td colspan=8 class=\"titulo_seccion\">$mensaje1</td>
                     </tr>";
         }else{ ?>
			    <tr>
			        <td class="tdtitulos">Comentario para la anulaci&oacute;n:</td>
			        <td class="tdcampos"><TEXTAREA COLS=40 ROWS=2 id="anularproceso" class="campos"></TEXTAREA></td>
			    </tr>
              <?php echo" 
                 <tr>
                       <td colspan=7 class=\"titulo_seccion\" title=\"Imprimir reporte\">";
                        $url="'views01/farmaext7.php?elproceso=$operacion'";?>
                        <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
                       <?echo"</td>  
                       <td class=\"titulo_seccion\"><label title=\"Anular Proceso\" class=\"boton\" style=\"cursor:pointer\" onclick=\"anularfarma($operacion,'$tipelimina')\" >Anular orden</label></td>  
                         <td class=\"titulo_seccion\"> 
                       <input  type=\"text\" size=\"10\" id=\"inempre\" class=\"campos\" maxlength=\"10\">
	                  <a href=\"javascript:void(0);\" onclick=\"g_Calendar.show(event, 'inempre', 'yyyy-mm-dd')\" title=\"Ver calendario\">
	                 <img src=\"../public/images/calendar.gif\" class=\"cp_img\" alt=\"Seleccione la Fecha\"></a>
                       <label title=\"Cambiar estado en proceso\" class=\"boton\" style=\"cursor:pointer\" onclick=\"estaproorden($operacion)\" >Cambiar Estado</label></td>   
                 </tr>      
                     ";
             
              }?>
    
</table>
<br>
<div id='farmaelim'></div>

