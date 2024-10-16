<?php
include ("../../lib/jfunciones.php");
sesion();
$laspolizas=("select polizas.id_poliza,polizas.nombre_poliza,polizas.descripcion,polizas.fecha_creado,polizas.intermediario 
              from polizas order by nombre_poliza;");
$relaspolizas=ejecutar($laspolizas);
?>
<div id='laspolizas'>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">P&oacute;lizas registradas</td>  
     </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
     <tr>
            <th class="tdtitulos">No.</th>
            <th class="tdtitulos"  title="Ordenar por Nombre" style="cursor:pointer" onclick="OrdenporNom('N')">Nombre.</th>
            <th class="tdtitulos">Descripci&oacute;n.</th> 
	   <th class="tdtitulos" title="Ordenar por Fecha" style="cursor:pointer" onclick="OrdenporNom('F')">Fecha creada.</th> 				 
    </tr>
   <?php 
	$i=1; 
	while($polizascr=asignar_a($relaspolizas,NULL,PGSQL_ASSOC)){
    ?>
         <tr>
	   <td class="tdcampos"><?echo $i;?></td>
           <td class="tdcampos" onclick="modifpoliza('<?echo $polizascr[id_poliza]?>','<?echo $polizascr[nombre_poliza]?>','<?echo $polizascr[intermediario]?>')" style="cursor:pointer"><?echo $polizascr['nombre_poliza'];?></td> 
	   <td class="tdcampos"><?echo $polizascr['descripcion'];?></td>      
	   <td class="tdcampos"><?echo $polizascr['fecha_creado'];?></td>      
        </tr>
        <tr><td class="tdcampos" colspan=6><hr></td></tr>
    <? $i++;
        }?>
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
</div>
