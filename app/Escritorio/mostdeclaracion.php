<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $ceduclien=$_REQUEST["titular"];     
  list($idusuario,$tipousuar)=explode('-',$ceduclien);
  if($tipousuar=='T'){
    $buscarespuesta=("select declaracion.declaracion,declaracion_t.respuesta from declaracion,declaracion_t 
   where 
  declaracion.id_declaracion=declaracion_t.id_declaracion and
  declaracion_t.id_titular=$idusuario
order by declaracion.id_declaracion;");
   $repbuscarespuesta=ejecutar($buscarespuesta);
   $budatosclien=("select clientes.nombres,clientes.apellidos from clientes,titulares where
                              titulares.id_titular=$idusuario and titulares.id_cliente=clientes.id_cliente");
   $repbusdatclien=ejecutar($budatosclien);    
   $datclien=assoc_a($repbusdatclien);
   $nomcompleto="$datclien[nombres] $datclien[apellidos]";
  }else{
         $buscarespuesta=("select declaracion.declaracion,declaracion_t.respuesta from declaracion,declaracion_t 
   where 
  declaracion.id_declaracion=declaracion_t.id_declaracion and
  declaracion_t.id_beneficiario=$idusuario
order by declaracion.id_declaracion;");
   $repbuscarespuesta=ejecutar($buscarespuesta);
   $budatosclien=("select clientes.nombres,clientes.apellidos from clientes,titulares where
                              beneficiarios.id_beneficiario=$idusuario and beneficiarios.id_cliente=clientes.id_cliente");
   $repbusdatclien=ejecutar($budatosclien);    
   $datclien=assoc_a($repbusdatclien);
   $nomcompleto="$datclien[nombres] $datclien[apellidos]";
      }
       
  ?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Declaraci&oacute;n de Salud para <?echo $nomcompleto?></td>           
     </tr>
</table>
<table class="tabla_cabecera5" >
     <tr>
        <th class="tdtitulos">Pregunta.</th>
        <th class="tdtitulos">Respuesta.</th>
      </tr>  
  <?
    while($lasrepuestas=asignar_a($repbuscarespuesta,NULL,PGSQL_ASSOC)){
    $repuesta=$lasrepuestas['respuesta'];    
     if($repuesta==1) 
       $mostrepuesta="Si";
     else
       $mostrepuesta="No";
?>
     <tr>
	    <td class="tdcampos"><?echo $lasrepuestas['declaracion'];?></td>
		<td class="tdcampos"><?echo $mostrepuesta;?></td>
     </tr>   
  <?}?>
  </table>