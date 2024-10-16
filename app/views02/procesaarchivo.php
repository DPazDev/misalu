<?
include ("../../lib/jfunciones.php");
sesion();
//definimos el directorio donde se guadan los archivos
$path = "../../files/";
//abrimos el directorio
$dir = opendir($path);
//guardamos los archivos en un arreglo
$img_total=0;
while ($elemento = readdir($dir))
{
if (strlen($elemento)>3)
{
$img_array[$img_total]=$elemento;
}

$img_total++;
}?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Archivos Cargados al Servidor</td>  
     </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Nombre del Archivo.</th>
                 <th class="tdtitulos">Opci&oacute;n</th>
		     </tr>		
<?
for ($i=0;$i<=$img_total; $i++){
  $imagen = $img_array[$i];
  if(!empty($imagen)){?>
  <tr>
    <td class="tdcampos"><?echo $imagen;?></td>
    <td><label title="Procesar Archivo" class="boton" style="cursor:pointer" onclick="ArchivoPro('<?echo $imagen?>')" >Procesar</label>
 </tr>
 <tr>
   <td class="tdcampos" colspan=6><hr></td>
 </tr>
    
<?}
}
?>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
