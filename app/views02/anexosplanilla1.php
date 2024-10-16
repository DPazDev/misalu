<?php
include ("../../lib/jfunciones.php");
sesion();
$cedulaclien=$_REQUEST['cliente'];
$buscoidclien=("select clientes.id_cliente,clientes.nombres,clientes.apellidos from clientes where cedula='$cedulaclien';");
$repbuscodclien=ejecutar($buscoidclien);
$cuantoclien=num_filas($repbuscodclien);
if($cuantoclien<=0){?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">No existe al menos una persona con la c&eacute;dula: <?echo $cedulaclien;?></td>  
     </tr>
</table>
<?}else{
	$dataclien=assoc_a($repbuscodclien);
	$idclientes=$dataclien['id_cliente'];
	$nomcompletot="$dataclien[nombres] $dataclien[apellidos]";
	$versiestitu=("select titulares.id_titular,entes.nombre 
           from
              titulares,estados_t_b,entes
           where 
			  titulares.id_cliente=$idclientes and
			  titulares.id_titular=estados_t_b.id_titular and
			  estados_t_b.id_beneficiario=0 and
			  estados_t_b.id_estado_cliente=4 and
			  titulares.id_ente=entes.id_ente;");
    $repvertitu=ejecutar($versiestitu);
    $cuantostitu=num_filas($repvertitu); 			  
    //es un titular!!!!
    if($cuantostitu>=1){?>
    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
              <tr> 
                 <td colspan=4 class="titulo_seccion">Datos del cliente <?echo $nomcompletot?> como titular</td>  
              </tr>
     </table>
	 <table class="tabla_citas"  cellpadding=0 cellspacing=0>
         <tr>
            <th class="tdtitulos">Ente.</th>
            <th class="tdtitulos">Opci&oacute;n.</th>
		</tr>
	<?while($lostitula=asignar_a($repvertitu,NULL,PGSQL_ASSOC)){?>
	    <tr>
	          <td class="tdcampos"><?echo $lostitula['nombre'];?></td>
	          <td class="tdcampos"><label title="Procesar cambio" id="titularente" class="boton" style="cursor:pointer" onclick="GeneraAnexo('<?echo "$lostitula[id_titular]-T"?>')" >Generar Anexo</label></td> 
		</tr>      
		<tr>
		  <td colspan=6><hr></td>
		</tr>      
	<?}?>	         
	</table>
	<?}
	//ver si es un beneficiario
	$versiesbene=("select titulares.id_titular,entes.nombre,beneficiarios.id_beneficiario 
                  from
                    titulares,estados_t_b,entes,beneficiarios
                 where 
				  beneficiarios.id_cliente=$idclientes and
				  titulares.id_titular=beneficiarios.id_titular and
				  estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and
				  estados_t_b.id_estado_cliente=4 and
				  titulares.id_ente=entes.id_ente");
	$repversibene=ejecutar($versiesbene);		
	$cuantosbenefi=num_filas($repversibene);	  
	//es un beneficiario
	if($cuantosbenefi>=1){?>
	 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
              <tr> 
                 <td colspan=4 class="titulo_seccion">Datos del cliente <?echo $nomcompletot?> como beneficiario</td>  
              </tr>
     </table>
	 <table class="tabla_citas"  cellpadding=0 cellspacing=0>
         <tr>
            <th class="tdtitulos">Titular.</th>
            <th class="tdtitulos">Ente.</th>
            <th class="tdtitulos">Opci&oacute;n.</th>
		</tr>
	<?while($losbeni=asignar_a($repversibene,NULL,PGSQL_ASSOC)){
	        $cualtitu=$losbeni['id_titular'];
	        $busnomtitu=("select clientes.nombres,clientes.apellidos from
	                       clientes,titulares
	                      where
	                        clientes.id_cliente=titulares.id_cliente and
	                        titulares.id_titular=$cualtitu");
	       $repbusmon=ejecutar($busnomtitu);                 
	       $datonomt=assoc_a($repbusmon);
	       $nomcompletot="$datonomt[nombres] $datonomt[apellidos]";
	?>
	    <tr>
	          <td class="tdcampos"><?echo $nomcompletot?></td>
	          <td class="tdcampos"><?echo $losbeni['nombre'];?></td>
	          <td class="tdcampos"><label title="Procesar cambio" id="titularente" class="boton" style="cursor:pointer" onclick="GeneraAnexo('<?echo "$losbeni[id_beneficiario]-B"?>')" >Generar Anexo</label></td> 
		</tr>      
		<tr>
		  <td colspan=6><hr></td>
		</tr>      
	<?}?>	         
	</table>	
	<?}
	?>



<?}?>
<div id='lasplanillas'></div>
