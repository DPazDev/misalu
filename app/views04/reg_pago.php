<?php
include ("../../lib/jfunciones.php");
sesion();
$q_ente=("select * from entes  order by entes.nombre");
$r_ente=ejecutar($q_ente);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="reg_pago" id="reg_pago">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=5 class="titulo_seccion">Registrar Pagos a Proveedores Otros</td>	</tr>	
	<tr>
		<td colspan=1 class="tdtitulos">Seleccione el Proveedor</td>
                <td colspan=2 class="tdtitulos"><select id="id_proveedor" name="id_proveedor" class="campos" style="width: 300px;"  >
                  <?php $q_proveedor=("select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                and clinicas_proveedores.prov_compra=2  order by clinicas_proveedores.nombre");
$r_proveedor=ejecutar($q_proveedor);
while($f_proveedor=asignar_a($r_proveedor,NULL,PGSQL_ASSOC)){

			?>
			<option value="<?php echo $f_proveedor[id_proveedor]?>"><?php echo "$f_proveedor[nombre] "?></option>
<?php 
}
?>
</select>
</td>
<td colspan=2 class="tdtitulos">
<a href="#" OnClick="reg_pagos2();" class="boton" title="Bucar Proveedores Otros Para Cargar un Pago">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		</tr>
</table>
<div id="reg_pagos2"></div>

</form>
