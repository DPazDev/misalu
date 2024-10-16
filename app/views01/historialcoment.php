<?php
  include ("../../lib/jfunciones.php");
  sesion();
  list($idtitben,$tipclien)=explode('-',$_REQUEST["titular"]);  
  if($tipclien=='T'){
       $buschisclien=("select admin.nombres,admin.apellidos,estados_clientes.estado_cliente,registros_exclusiones.fecha_creado,registros_exclusiones.comentario from
admin,estados_clientes,registros_exclusiones
where
     registros_exclusiones.id_admin=admin.id_admin and
     registros_exclusiones.id_estado_cliente=estados_clientes.id_estado_cliente and
     registros_exclusiones.id_titular=$idtitben and 
     registros_exclusiones.id_beneficiario=0 order by 
     fecha_creado desc;");
     $repsbusclien=ejecutar($buschisclien);
      }else{
         
          $buschisclien=("select admin.nombres,admin.apellidos,estados_clientes.estado_cliente,registros_exclusiones.fecha_creado,registros_exclusiones.comentario from
admin,estados_clientes,registros_exclusiones
where
     registros_exclusiones.id_admin=admin.id_admin and
     registros_exclusiones.id_estado_cliente=estados_clientes.id_estado_cliente and
     registros_exclusiones.id_beneficiario=$idtitben order by 
     fecha_creado desc;");
     echo $buschisclien;
     $repsbusclien=ejecutar($buschisclien);
          }
  ?>
  <table class="tabla_cabecera5" >
     <tr>
        <th class="tdtitulos">Fecha Registro.</th>
        <th class="tdtitulos">Estatus.</th>
        <th class="tdtitulos">Comentario.</th>
        <th class="tdtitulos">Operador.</th>
      </tr>
 <?
    while($losmovi=asignar_a($repsbusclien,NULL,PGSQL_ASSOC)){
    
?>
     <tr>
	    <td class="tdcampos"><?echo $losmovi['fecha_creado'];?></td>
		<td class="tdcampos"><?echo $losmovi['estado_cliente'];?></td>
        <td class="tdcampos"><?echo $losmovi['comentario'];?></td>
		<td class="tdcampos"><?echo "$losmovi[nombres] $losmovi[apellidos]";?></td>
     </tr>   
  <?}?>
  </table>