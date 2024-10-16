<?
include ("../../lib/jfunciones.php");
sesion();
$estadona=$_POST['estadodona'];
$ladepen=$_POST['dependencia'];
$buscarlospedidos=("select tbl_ordenes_donativos.id_orden_donativo,
tbl_ordenes_donativos.fecha_donativo,tbl_ordenes_donativos.no_orden_donativo,
tbl_ordenes_donativos.id_titular,tbl_ordenes_donativos.id_beneficiario,admin.nombres,admin.apellidos,
tbl_responsables_donativos.responsable from tbl_ordenes_donativos,admin,tbl_responsables_donativos
 where 
tbl_ordenes_donativos.estatus=$estadona  
and tbl_ordenes_donativos.id_admin=admin.id_admin and
tbl_ordenes_donativos.id_responsable_donativo=tbl_responsables_donativos.id_responsable_donativo and
tbl_ordenes_donativos.id_dependencia=$ladepen
order by tbl_ordenes_donativos.fecha_donativo DESC;");
$repulospedidona=ejecutar($buscarlospedidos);
if($estadona==1){
  $opc='Procesar';
 $mensaje1='Procesar donativo';
  $opc2=1;
}else{
  $opc='Ver'; 
  $mensaje1='Ver donativo creado';
  $opc2=2;
}

?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
 <tr>
   <th class="tdtitulos">No. donativo</th>
   <th class="tdtitulos">Fecha donativio</th>
   <th class="tdtitulos">Realizado por</th> 
   <th class="tdtitulos">Autorizado por</th> 
   <th class="tdtitulos">Cliente</th>
   <th class="tdtitulos">Opci&oacute;n.</th> 
 </tr>
<?
  while($datadelosdonativ=asignar_a($repulospedidona,NULL,PGSQL_ASSOC)){
   $dontitul=$datadelosdonativ['id_beneficiario'];
   if($dontitul>0){
     $buscarnombre=("select clientes.nombres,clientes.apellidos from clientes,beneficiarios where
                     beneficiarios.id_cliente=clientes.id_cliente and beneficiarios.id_beneficiario=$dontitul;");
     $repbuscarnom=ejecutar($buscarnombre);   
     $datanombre=assoc_a($repbuscarnom);
     $nombre=$datanombre[nombres];
     $apellido=$datanombre[apellidos];
   }else{
     $dontitul=$datadelosdonativ['id_titular'];
     $buscarnombre=("select clientes.nombres,clientes.apellidos from clientes,titulares where
                     titulares.id_cliente=clientes.id_cliente and titulares.id_titular=$dontitul;");
     $repbuscarnom=ejecutar($buscarnombre);   
     $datanombre=assoc_a($repbuscarnom);
     $nombre=$datanombre[nombres];
     $apellido=$datanombre[apellidos];
   }
   $controlpedido=$datadelosdonativ[id_orden_donativo];
    $opcamostrar="<label title='Ver donativo' class='boton' style='cursor:pointer' onclick='pedideid($datpedido[id_orden_pedido] )' >Procesar</label><label title='Imprimir donativo' class='boton' style='cursor:pointer' onclick='ModfElPed($datpedido[id_orden_pedido] )' >Modificar</label>"; 
   echo"<tr>
      <td class=\"tdcampos\">$datadelosdonativ[no_orden_donativo]</td>
      <td class=\"tdcampos\">$datadelosdonativ[fecha_donativo]</td>
      <td class=\"tdcampos\">$datadelosdonativ[nombres] $datadelosdonativ[apellidos]</td> 
      <td class=\"tdcampos\">$datadelosdonativ[responsable]</td>
      <td class=\"tdcampos\">$nombre $apellido</td>";
       ?>
        
      <td><label title="<?echo $mensaje1?>"  class="boton" style="cursor:pointer" onclick="ProcesarDona(<?echo $controlpedido?>)" ><?echo $opc?></label></td>
       <?if ($opc2==2){?>   
           <td><label title="Imprimir solicitud" class="boton" style="cursor:pointer" onclick="impsoli(<?echo $controlpedido?>)" >Solicitud</label></td>
           <td><label title="Imprimir acta" class="boton" style="cursor:pointer" onclick="impacta(<?echo $controlpedido?>)" >Acta</label></td>
       <?}?>
  </tr>	
<?}
?>
</table>
