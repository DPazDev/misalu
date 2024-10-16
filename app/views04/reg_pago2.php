<?php
include ("../../lib/jfunciones.php");
sesion();
$id_proveedor=$_REQUEST['id_proveedor'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$num_filas=0;
$num_filas1=0;


$q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                and clinicas_proveedores.prov_compra=2 and proveedores.id_proveedor=$id_proveedor order by clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
             	$f_pc=asignar_a($r_pc);
$nombrepro="$f_pc[nombre]";
$rifpro=$f_pc[rif];
$direccionpro=$f_pc[direccion];
$telefonospro=$f_pc[telefonos];
?>
<table cellpadding=0 cellspacing=0 width="100%">
<tr>		
<td colspan=9 class="titulo_seccion">Datos Del Proveedor</td>	
</tr>
<tr>	
			<td colspan=2 class="tdtitulos">	A Nombre de: 
			</td>
				<td colspan=4 class="tdcampos"><?php echo $nombrepro ?>
			</td>
			<td colspan=1 class="tdtitulos">	C.I. o Rif: 
			</td>
				<td colspan=2 class="tdcampos"> <?php echo $rifpro ?>
			</td>
		
		</tr>
		<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>
	     <tr>
		<td colspan=2 class="tdtitulos"> Num Factura </td>
	<td colspan=1 class="tdcampos"> <input class="campos" type="text" id="numcheque_<?php echo $i ?>"  name="numcheque_<?php echo $i ?>" maxlength=128 size=10 value="" > </td> 
	<td colspan=2 class="tdtitulos">Num Control</td>
	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="numcheque_<?php echo $i ?>"  name="numcheque_<?php echo $i ?>" maxlength=128 size=10 value="" > </td>
	<td colspan=2 class="tdtitulos"> Fecha Emision Factura </td>
	<td colspan=1 class="tdcampos">
 <input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value="" > 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
	
	
	</tr>
<tr>
		
<tr>
	<td colspan=2 class="tdtitulos"> Monto </td>
	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="numcheque_<?php echo $i ?>"  name="numcheque_<?php echo $i ?>" maxlength=128 size=10 value="" ></td>
	<td colspan=2 class="tdtitulos"> Base Imponible </td>
	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="numcheque_<?php echo $i ?>"  name="numcheque_<?php echo $i ?>" maxlength=128 size=10 value="" >  </td>
	<td colspan=2 class="tdtitulos">Iva</td>
	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="numcheque_<?php echo $i ?>"  name="numcheque_<?php echo $i ?>" maxlength=128 size=10 value="" > </td>
	</tr>
<tr>
	
	<td colspan=2 class="tdtitulos"><b>Iva Retenido Descuento</b></td>
	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="numcheque_<?php echo $i ?>"  name="numcheque_<?php echo $i ?>" maxlength=128 size=10 value="" ></td>

	<td colspan=2 class="tdtitulos"><b>ISLR </b></td>
	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="numcheque_<?php echo $i ?>"  name="numcheque_<?php echo $i ?>" maxlength=128 size=10 value="" ></td>

	<td colspan=2 class="tdtitulos">Monto Neto a Pagar </td>
	<td colspan=1 class="tdcamposr">
	</td></tr>
	<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>
<tr>
<td colspan=2 class="tdtitulos">
Numero Cheque
</td>
<td colspan=1 class="tdtitulos">
<input class="campos" type="text" id="numcheque_<?php echo $i ?>"  name="numcheque_<?php echo $i ?>" maxlength=128 size=10 value="" >
</td>
<td colspan=2 class="tdcamposc">Del Banco </td>
                <td colspan=3 class="tdcamposc"><select id="banco_<?php echo $i ?>" name="banco_<?php echo $i ?>" class="campos" style="width: 250px;"  >
                  <?php $q_banco=("select tbl_bancos.*,bancos.* from tbl_bancos,bancos where tbl_bancos.id_ban=bancos.id_ban and bancos.id_ban<>7 and bancos.id_ban<>10");
$r_banco=ejecutar($q_banco);
while($f_banco=asignar_a($r_banco,NULL,PGSQL_ASSOC)){

			?>
			<option value="<?php echo $f_banco[id_banco]?>"><?php echo "$f_banco[nombanco] $f_banco[numero_cuenta] "?></option>
<?php 
}
?>
</select>
</td>
<td colspan=1 class="tdtitulos">
</td>
</tr>
	<tr>
<td colspan=2 class="tdtitulos">Tipo de Cuenta </td>
                <td colspan=1 class="tdcamposc"><select id="tipocuenta_<?php echo$i?>" name="tipocuenta_<?php echo$i?>" class="campos" style="width: 150px;"  >
                  <?php $q_tipocuenta=("select * from tbl_tiposcuentas order by tipo_cuenta");
$r_tipocuenta=ejecutar($q_tipocuenta);
while($f_tipocuenta=asignar_a($r_tipocuenta,NULL,PGSQL_ASSOC)){

			?>
			<option value="<?php echo $f_tipocuenta[id_tipocuenta]?>"><?php echo "$f_tipocuenta[tipo_cuenta] "?></option>
<?php 
}
?>
</select>
</td>
<td colspan=2 class="tdtitulos">
Motivo
</td>
<td colspan=3 class="tdtitulos">
<input class="campos" type="text" id="motivo_<?php echo$i?>"  name="motivo_<?php echo$i?>" maxlength=128 size=30 value="" >
<input class="campos" type="hidden" id="codigo_<?php echo$i?>"  name="codigo_<?php echo$i?>" maxlength=128 size=30 value="<?php echo $f_facturas[codigo]?>" >
<input class="campos" type="hidden" id="nombreprov_<?php echo$i?>"  name="nombreprov_<?php echo$i?>" maxlength=128 size=30 value="<?php echo $nombrepro?>" >
<input class="campos" type="hidden" id="cedula_<?php echo$i?>"  name="cedula_<?php echo$i?>" maxlength=128 size=30 value="<?php echo $f_pc[rif]?>" >
<input class="campos" type="hidden" id="id_proveedor_<?php echo$i?>"  name="id_proveedor_<?php echo$i?>" maxlength=128 size=30 value="<?php echo $f_facturas[id_proveedor]?>" >
<input class="campos" type="hidden" id="personaprov_<?php echo$i?>"  name="personaprov_<?php echo$i?>" maxlength=128 size=30 value="0" >
</td>
<td colspan=1 class="tdtitulos">
<a href="#" OnClick="imp_che_gemco(<?php echo $i?>);" class="boton" title="Imprimir Cheques">Imprimir</a>
</td>
</tr>
</table>


	