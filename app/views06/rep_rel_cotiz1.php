<?php
include ("../../lib/jfunciones.php");
sesion();

list($id_sucursal,$sucursal)=explode("@",$_REQUEST['sucursal']);
if($id_sucursal==0)	$condicion_sucursal="and admin.id_sucursal>0";
else			$condicion_sucursal="and admin.id_sucursal=$id_sucursal  ";

//si la condicion sigue en este punto vacia hay que buscar por cada proceso su proveedor.

$fecha_inicio=$_REQUEST['dateField1'];
$fecha_fin=$_REQUEST['dateField2'];


?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
		<table border=1 cellpadding=0 cellspacing=0 width="100%">
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo">

</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
</td>
<td colspan=3 class="titulo1">
<?php echo "$f_admin[sucursal] $fechaimpreso"?>
</td>
</tr>

<tr>		<td colspan=9 class="titulo3">Relacion de Cotizaciones <?php echo "Desde $fecha_inicio Hasta $fecha_fin"?></td>	</tr>	

		<tr>
			<td class="tdcampos">Numero Cotizacion</td>
			<td class="tdcampos">Nombres</td>
			<td class="tdcampos">Apellidos</td>
			<td class="tdcampos">Rif o Cedula</td>
            <td class="tdcampos">Telefono</td>
            <td class="tdcampos">Correo Electronico</td>
            <td class="tdcampos">Plan</td>
            <td class="tdcampos">Fecha Emision</td>
            <td class="tdcampos">Monto</td>
        </tr>
<?php 
	 $q_cli_coti=("select tbl_cliente_cotizacion.*  
                            from 
                                    tbl_cliente_cotizacion,
                                    admin 
                            where 
                                    tbl_cliente_cotizacion.fecha_creado>='$fecha_inicio' and 
                                    tbl_cliente_cotizacion.fecha_creado<='$fecha_fin' and 
                                    tbl_cliente_cotizacion.id_admin=admin.id_admin 
                                    $condicion_sucursal
                            order by 
                                    tbl_cliente_cotizacion.id_cliente_cotizacion");
	
/* **** fin  de buscar todos los clientes de cotizaciones***** */
	$r_cli_coti=ejecutar($q_cli_coti);

	while($f_cli_coti=pg_fetch_array($r_cli_coti,NULL,PGSQL_ASSOC)){

/* **** buscar todos los planes de  una cotizacion***** */

	 $q_plan_coti=("select 
                                polizas.nombre_poliza,
                                count(polizas.id_poliza) 
                        from 
                                polizas,
                                tbl_cliente_cotizacion,
                                tbl_caract_cotizacion
                        where 
                                polizas.id_poliza=tbl_caract_cotizacion.id_poliza and
                                tbl_caract_cotizacion.id_cliente_cotizacion=tbl_cliente_cotizacion.id_cliente_cotizacion and 
                                tbl_cliente_cotizacion.id_cliente_cotizacion=$f_cli_coti[id_cliente_cotizacion] 
                        group by 
                                polizas.nombre_poliza");
	
/* **** fin  de buscar todos los planes de  una cotizacion***** */
	$r_plan_coti=ejecutar($q_plan_coti);
     $q_prima_coti=("select 
                                    sum(tbl_caract_cotizacion.montoprima) 
                            from 
                                    tbl_caract_cotizacion 
                            where 
                                    tbl_caract_cotizacion.id_cliente_cotizacion=$f_cli_coti[id_cliente_cotizacion] 
                            ");
	

	$r_prima_coti=ejecutar($q_prima_coti);
    $f_prima_coti=asignar_a($r_prima_coti);
    $plan="";
    	while($f_plan_coti=pg_fetch_array($r_plan_coti,NULL,PGSQL_ASSOC)){
            $plan .= $f_plan_coti[nombre_poliza]."  ";
            
            }
    
?>
<tr>
<td class="datos_cliente"><?php echo "$f_cli_coti[no_cotizacion]"?></td>
<td class="datos_cliente"><?php echo "$f_cli_coti[nombres] ";?></td>
<td class="datos_cliente"><?php echo "$f_cli_coti[apellidos] ";?></td>
<td class="datos_cliente"><?php echo "$f_cli_coti[rif_cedula] ";?></td>
<td class="datos_cliente"><?php echo "$f_cli_coti[celular] ";?></td>
<td class="datos_cliente"><?php echo "$f_cli_coti[email] ";?></td>
<td class="datos_cliente"><?php echo "$plan";?></td>
<td class="datos_cliente"> <?php echo "$f_cli_coti[fecha_creado] ";?></td>
<td class="datos_cliente"> <?php echo "$f_prima_coti[sum] ";?></td>
</tr>

<?php
}
?>



</table>



