<?
 include ("../../lib/jfunciones.php");
  sesion();
$usuario=$_REQUEST['quien'];
list($eltitular,$elbenefi)=explode('@',$usuario);
if($elbenefi==0){
	$histinforme=("select tbl_informedico.id_infomedico,tbl_informedico.id_proceso,procesos.id_titular,tbl_informedico.fechacreado 
	                 from procesos,tbl_informedico where
                     tbl_informedico.id_proceso=procesos.id_proceso and
                     procesos.id_titular=$eltitular order by tbl_informedico.fechacreado desc;");
    $rephistinform=ejecutar($histinforme);   
    $busdattitu=("select clientes.nombres,clientes.apellidos,clientes.sexo,clientes.fecha_nacimiento,clientes.cedula,entes.nombre 
	                   from 
	                     clientes,titulares,entes 
	                   where
							clientes.id_cliente=titulares.id_cliente and
							titulares.id_ente=entes.id_ente and titulares.id_titular=$eltitular");
	    $repdatatitu=ejecutar($busdattitu);						
	    $dataprintitu=assoc_a($repdatatitu);
	    $nombretitu=$dataprintitu['nombres'];
	    $apellittitu=$dataprintitu['apellidos'];
	    $nomcompletotitu="$nombretitu $apellittitu";
}else{
 $histinforme=("select tbl_informedico.id_infomedico,tbl_informedico.id_proceso,procesos.id_beneficiario,tbl_informedico.fechacreado 
	                 from procesos,tbl_informedico where
                     tbl_informedico.id_proceso=procesos.id_proceso and
                     procesos.id_beneficiario=$elbenefi order by tbl_informedico.fechacreado desc;");
 $rephistinform=ejecutar($histinforme);  
 $busdatbeni=("select clientes.nombres,clientes.apellidos,clientes.sexo,clientes.fecha_nacimiento,clientes.cedula,entes.nombre,parentesco.parentesco 
	from 
	   clientes,titulares,entes,beneficiarios,parentesco 
	where
	clientes.id_cliente=beneficiarios.id_cliente and
        beneficiarios.id_titular=titulares.id_titular and
        beneficiarios.id_parentesco=parentesco.id_parentesco and
	titulares.id_ente=entes.id_ente and beneficiarios.id_beneficiario=$elbenefi");
	   $repdatbeni=ejecutar($busdatbeni);
	   $ladatbeni=assoc_a($repdatbeni);
	   $nomcompbeni="$ladatbeni[nombres] $ladatbeni[apellidos]";
}
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <br>
        <tr>  
          <td colspan=8 class="titulo_seccion"><?if($elbenefi==0){?>Historial del Titular: (<?echo $nomcompletotitu?>)<?}else{?>
          Historial del Beneficiario: (<?echo $nomcompbeni?>)<?}?></td>   
       </tr> 
 </table>
 <table class="tabla_citas"  cellpadding=0 cellspacing=0>
    <tr>
	   <th class="tdtitulos">Fecha Creado.</th>  
       <th class="tdtitulos">Proceso.</th>
       <th class="tdtitulos">Servicio.</th>
       <th class="tdtitulos">Opci&oacute;n.</th>       
   </tr> 
   <?while ($infmed=asignar_a($rephistinform)){ 
	   $nproceso=$infmed['id_proceso'];
	   $elservi=("select servicios.servicio,gastos_t_b.id_servicio from gastos_t_b,servicios where 
	              gastos_t_b.id_proceso=$nproceso and gastos_t_b.id_servicio=servicios.id_servicio limit 1");
	   $repelservi=ejecutar($elservi);
	   $dataservi=assoc_a($repelservi);           
	   $servicioes=$dataservi['servicio'];
	   ?>
     <tr>
	    <td class="tdcampos"><?list($a,$m,$d)=explode('-',$infmed['fechacreado']);
	                          echo "$d/$m/$a";?></td>
	    <td class="tdcampos"><?echo $infmed['id_proceso'];?></td>
	    <td class="tdcampos"><?echo $servicioes;?></td>
	    <td class="tdcampos"><label class="boton" style="cursor:pointer" onclick="Verel_Informe('<?echo "$infmed[id_infomedico]"?>')" >Ver Informe</label></td>	    
	 </tr>   
   <?}?>
</table>   
