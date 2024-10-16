<?
include ("../../lib/jfunciones.php");
sesion();
$seguardo=0;
$fecha=date("Y-m-d");
$nombmarca=strtoupper($_POST['nmarca']);
$buscosiexiste=("select tbl_laboratorios.id_laboratorio from tbl_laboratorios where tbl_laboratorios.laboratorio='$nombmarca';");
$repbuscosiexiste=ejecutar($buscosiexiste);
$cuantosexiste=num_filas($repbuscosiexiste);
if($cuantosexiste>=1){
	  echo"
               <table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
       <br>
                   <tr>
                      <td colspan=4 class=\"titulo_seccion\">La marca $nombmarca ya esta registrada en el sistema</td>
                   </tr>
              </table> 
              "; 
	}
else
{
 $regnuevamarca=("insert into tbl_laboratorios(laboratorio,fecha_hora_creado) values('$nombmarca','$fecha');");	
 $repregnuevamarca=ejecutar($regnuevamarca); 
 $seguardo=1;
 }
if($seguardo==1){
	 echo"
               <table class=\"tabla_citas\"  cellpadding=0 cellspacing=0>
       <br>
                   <tr>
<br>
                      <td colspan=4 class=\"titulo_seccion\">La marca $nombmarca se registro exitosamente!!
<label title=\"Recargar la pagina\"class=\"boton\" style=\"cursor:pointer\" onclick=\"crea_articulo()\" >Recargar</label></td> 
                   </tr>
              </table> 
              "; 
} 
?>  