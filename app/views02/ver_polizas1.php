<?php
include ("../../lib/jfunciones.php");
sesion();
$laopcion=$_POST['opcion'];
if($laopcion=='N'){
   $laspolizas=("select polizas.nombre_poliza,polizas.descripcion,polizas.fecha_creado from polizas order by nombre_poliza desc;");
   $relaspolizas=ejecutar($laspolizas);
}else{
   $laspolizas=("select polizas.nombre_poliza,polizas.descripcion,polizas.fecha_creado from polizas order by fecha_creado desc;");
   $relaspolizas=ejecutar($laspolizas);
}
?>
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
	   <td class="tdcampos"><?echo $polizascr['nombre_poliza'];?></td> 
	   <td class="tdcampos"><?echo $polizascr['descripcion'];?></td>      
	   <td class="tdcampos"><?echo $polizascr['fecha_creado'];?></td>      
        </tr>
        <tr><td class="tdcampos" colspan=6><hr></td></tr>
    <? $i++;
        }?>
</table>
