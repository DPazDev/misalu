<?
include ("../../lib/jfunciones.php");
sesion();
$laplanilla=$_REQUEST['planillauso'];
$buscarplanilla=("select procesos.id_proceso,procesos.id_titular,procesos.id_beneficiario,
                   procesos.id_admin,admin.nombres,admin.apellidos 
                   from procesos,admin where nu_planilla='$laplanilla' and procesos.id_admin=admin.id_admin");
$repbusplanilla=ejecutar($buscarplanilla);                   
$cuantaplanilla=num_filas($repbusplanilla);
if($cuantaplanilla>=1){
?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
      <tr>
        <th class="tdtitulos">Proceso.</th>
        <th class="tdtitulos">Titular.</th>
        <th class="tdtitulos">Ente.</th>
        <th class="tdtitulos">Tipo Ente.</th>
	<th class="tdtitulos">Beneficiario.</th> 
	<th class="tdtitulos">Operador.</th> 
	 </tr>
	 <?php
		 while($lplani=asignar_a($repbusplanilla,NULL,PGSQL_ASSOC)){
            $dtitu=("select clientes.nombres,clientes.apellidos,entes.nombre,tbl_tipos_entes.tipo_ente from clientes,titulares,entes,tbl_tipos_entes where
                      clientes.id_cliente=titulares.id_cliente and
                      titulares.id_titular=$lplani[id_titular] and
                      titulares.id_ente=entes.id_ente and 
                      entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente");
            $reptitu=ejecutar($dtitu);
            $dattiu=assoc_a($reptitu);
            $elnomtitu="$dattiu[nombres] $dattiu[apellidos]"; 
            $elnombenif="-";
            $elentees=$dattiu[nombre];
            $tipoente=$dattiu[tipo_ente];
            if($lplani[id_beneficiario]>0){
				$dbenif=("select clientes.nombres,clientes.apellidos from clientes,beneficiarios where
                      clientes.id_cliente=beneficiarios.id_cliente and
                      beneficiarios.id_beneficiario=$lplani[id_beneficiario]");			
                $repbenif=ejecutar($dbenif);
                $datbenif=assoc_a($repbenif);    
                $elnombenif="$datbenif[nombres] $datbenif[apellidos]";
			}
	 ?>
	      <tr>
			<td class="tdcampos"><?echo $lplani[id_proceso];?></td>
			<td class="tdcampos"><?echo $elnomtitu;?></td>
                        <td class="tdcampos"><?echo $elentees;?></td>
                        <td class="tdcampos"><?echo $tipoente;?></td>
			<td class="tdcampos"><?echo $elnombenif;?></td>      	
			<td class="tdcampos"><?echo "$lplani[nombres] $lplani[apellidos]";?></td>			    
			</tr>

<?
  }?>
</table>
<?}?>
