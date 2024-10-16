<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$laletra=strtoupper($_POST['laletra']);
if(empty($laletra)){
    $losentes=("select entes.id_ente,entes.nombre,entes.rif,entes.nombre_contacto,entes.telefonos_contacto,entes.fecha_creado,entes.codente
                         from entes order by entes.nombre;");
$replosentes=ejecutar($losentes);  
}else{
     $losentes=("select entes.id_ente,entes.nombre,entes.rif,entes.nombre_contacto,entes.telefonos_contacto,entes.fecha_creado,entes.codente
                         from entes where entes.nombre like('$laletra%') order by entes.nombre;");
     $replosentes=ejecutar($losentes);  
    }
                      
?>
<div id='losentesca'>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Entes registrados</td>  
         <td class="titulo_seccion"><label  title="Regresar al modulo Ente" class="boton" style="cursor:pointer" onclick="reg_entes()" >Regresar</label></td>
	</tr>
    <tr> 
         <td colspan=3 class="titulo_seccion">Filtrar por la letra:</td>  
         <td class="titulo_seccion"><input type="text" id="letra" class="campos" size="35" onKeyUp="listaentes()"></td> 
	</tr>
  </table>
  <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Num.</th>
                 <th class="tdtitulos">Nombre.</th>
                 <th class="tdtitulos">RIF.</th>
		 <th class="tdtitulos">Contacto.</th>
		 <th class="tdtitulos">Tel&eacute;fono.</th> 				 
                 <th class="tdtitulos">Fecha creado.</th> 				 
                 <th class="tdtitulos">Cod. Ente.</th
            </tr>
    <?
        $i=1;
        while($losenteson=asignar_a($replosentes,NULL,PGSQL_ASSOC)){?>
            <tr>
                    <td class="tdcampos"><?echo $i;?></td>
                    <td class="tdcampos" onclick="modifelente(<?echo $losenteson['id_ente']?>)" style="cursor:pointer"><?echo $losenteson['nombre'];?></td>
                    <td class="tdcampos"><?echo $losenteson['rif'];?></td>
                    <td class="tdcampos"><?echo $losenteson['nombre_contacto'];?></td>
                    <td class="tdcampos"><?echo $losenteson['telefonos_contacto'];?></td>
                    <td class="tdcampos"><?echo $losenteson['fecha_creado'];?></td>
                     <td class="tdcampos"><?echo $losenteson['codente'];?></td>
            <tr> 
      <?  
        $i++;
        } 
      ?>
      <tr>
      
   </td>
      </tr>
    </table> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
 </div>   
