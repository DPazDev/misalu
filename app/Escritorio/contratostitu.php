<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $cedulatitu=$_REQUEST['lacedulacoti'];
  $busdatoscontr=("select clientes.cedula,clientes.id_cliente,clientes.nombres,clientes.apellidos, 
  titulares.id_titular,tbl_contratos_entes.numero_contrato,tbl_contratos_entes.id_ente, 
  tbl_recibo_contrato.num_recibo_prima,polizas.nombre_poliza,tbl_recibo_contrato.id_recibo_contrato 
from 
clientes,titulares,tbl_contratos_entes,tbl_recibo_contrato,
tbl_caract_recibo_prima,polizas,polizas_entes,entes 
where 
clientes.id_cliente=titulares.id_cliente and 
titulares.id_titular=tbl_caract_recibo_prima.id_titular and 
tbl_caract_recibo_prima.id_recibo_contrato=tbl_recibo_contrato.id_recibo_contrato and 
tbl_recibo_contrato.id_contrato_ente=tbl_contratos_entes.id_contrato_ente and 
clientes.cedula='$cedulatitu' and
tbl_contratos_entes.id_ente=entes.id_ente and
entes.id_ente=polizas_entes.id_ente and
polizas_entes.id_poliza=polizas.id_poliza and
tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente 

group by 
clientes.cedula,clientes.id_cliente,clientes.nombres,clientes.apellidos, 
titulares.id_titular,tbl_contratos_entes.numero_contrato,tbl_contratos_entes.id_ente, 
tbl_recibo_contrato.num_recibo_prima,polizas.nombre_poliza,tbl_recibo_contrato.id_recibo_contrato;");
   $repbuscacontr=ejecutar($busdatoscontr);
?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
  <tr>
  <td class="tdtitulos" colspan="1">Contrato:</td>
  <td class="tdcampos"  colspan="1"><select id="cliencontratos" class="campos" style="width: 190px;"> 
  <option value=""></option>
     <?while($loscontra=asignar_a($repbuscacontr,NULL,PGSQL_ASSOC)){?>
          <option value="<?php echo "$loscontra[id_recibo_contrato]"?>"> <?php echo "$loscontra[numero_contrato] -|- $loscontra[nombre_poliza] -|- $loscontra[num_recibo_prima]"?></option>
	 <?}?>
   </select>  
  </td> 
  </tr>
  <tr>
	 <td class="tdcampos" title="Buscar contratos"><label class="boton" style="cursor:pointer" onclick="reimpcuadrecibo()" >Imprimir</label>
  </tr>
</table>
