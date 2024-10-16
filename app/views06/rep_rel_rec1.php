<?php
include ("../../lib/jfunciones.php");
sesion();

list($id_sucursal,$sucursal)=explode("@",$_POST['sucursal']);

if($id_sucursal==0)	$condicion_sucursal="and admin.id_sucursal>0";
else			$condicion_sucursal="and admin.id_sucursal=$id_sucursal  ";

//si la condicion sigue en este punto vacia hay que buscar por cada proceso su proveedor.
$recibo_che=$_POST['recibo_che'];
$fecha_inicio=$_POST['dateField1'];
$fecha_fin=$_POST['dateField2'];
$tipo_cheque=$_POST['tipo_cheque'];
$proveedor=$_POST['proveedor'];
$tipo_fecha=$_POST['tipo_fecha'];
$banco=$_POST['banco'];
$banco1=$_POST['banco'];
$numcheque=" ";
//echo "<h2>recibo_che=$recibo_che -- fecha_inicio=$fecha_inicio -- tipo_cheque=$tipo_cheque proveedor=$proveedor -- tipo_fecha=$tipo_fecha -- banco=$banco</h2>";
/* **** verificar si se busca todos los proveedores o uno en especifico **** */
		if ($proveedor==""){
			$proveedores="and facturas_procesos.id_proveedor>0";
			}
			else
		    {
			$proveedores="and facturas_procesos.id_proveedor=$proveedor";
	}
	/* fin de verificar si busca todos los proveedores */


/* se realiza comparacion de datos para armar las variables para ser utilizadas en la consulta de la db tabla $q_cheques*/
		if ($tipo_cheque==0 and $recibo_che==1)
		{
		    $numcheque="and facturas_procesos.numero_cheque>'0'";
		    $proveedores="";
		    }
$order="order by facturas_procesos.fecha_creado";

/* verifico el tipo de consulta si es por cheque o iva islr o recibo para armar variable $tbanco */
	if ($recibo_che==1)//Cheques
	{		if ($banco=='*'){ //todos los bancos
					//id_banco 9<>ANULADO 13<>CHEQUE POR GENERAR 15<>COMPROBANTES A TERCEROS
					$tbanco="and facturas_procesos.id_banco<>9 and facturas_procesos.id_banco<>13 and facturas_procesos.id_banco<>15";
				}
				else
				{ ///banco especifico
					$tbanco="and facturas_procesos.id_banco=$banco";
				}
		}
		else
		{ ///$recibo_che es culaquiera menos Cheques
			if ($banco=='*'){
				$tbanco="and facturas_procesos.id_banco<>9";//menos anulados
				}
			else
				{
					$tbanco="and facturas_procesos.id_banco=$banco"; //banco especifico
				}
		}
/* fin verifico el tipo de consulta si es por cheque o iva islr o recibo para armar variable $tbanco */

/* verifico que tipo de proveedor voy a buscar en facturas_procesos si medico clinica otros etc  */

	if ($tipo_cheque==5){ //todos excepto renvolsos
		$ttipo_cheque="and facturas_procesos.tipo_proveedor<>0";
    }
	else
	{  $ttipo_cheque="and facturas_procesos.tipo_proveedor=$tipo_cheque";
    }
/*fin  verifico que tipo de proveedor voy a buscar en facturas_procesos si medico clinica otros etc  */


/* verifico que tipo de busqueda voy a realizar si por recibo, por cheque de islr o iva  */
if ($recibo_che==2){ //IVA
	$recibo_che1="and facturas_procesos.corre_retiva_seniat>0";
    $fecha="facturas_procesos.fecha_creado>='$fecha_inicio' and facturas_procesos.fecha_creado<='$fecha_fin' and";
	$order="order by facturas_procesos.corre_retiva_seniat";

    }
if ($recibo_che==3){ //islr
	$recibo_che1=" and facturas_procesos.corre_ret_islr>0";
    $fecha="facturas_procesos.fecha_imp_che>='$fecha_inicio' and facturas_procesos.fecha_imp_che<='$fecha_fin' and";
	$order="order by facturas_procesos.corre_ret_islr";
    }

  if ($recibo_che==1 ){//cheque
    $fecha="facturas_procesos.fecha_imp_che>='$fecha_inicio' and facturas_procesos.fecha_imp_che<='$fecha_fin' and";
    }
    /* para verificar porque no muestra cheques en espera
    if ($recibo_che==1 && $banco==13){
    $fecha="facturas_procesos.fecha_creado>='$fecha_inicio' and facturas_procesos.fecha_creado<='$fecha_fin' and";

    }
    */

    if ($recibo_che==0 ){
    	$fecha="facturas_procesos.fecha_creado>='$fecha_inicio' and facturas_procesos.fecha_creado<='$fecha_fin' and";
    }

    /* fin de verificar que tipo de busqueda voy a realizar si por recibo, por cheque de islr o iva  */
    /* verifico si el id banco es 13 (cheques por generar y cambio la variable $fecha para que busque por fecha creado)*/
    if ($banco==13 || $banco==15){
        $fecha="facturas_procesos.fecha_creado>='$fecha_inicio' and facturas_procesos.fecha_creado<='$fecha_fin' and";
        }
        /*fin de verificar si el id banco es 13 (cheques por generar y cambio la variable $fecha para que busque por fecha creado)*/
     /* fin de  realizar comparacion de datos para armar las variables para ser utilizadas en la consulta de la db tabla $q_cheques*/
?>

<table   border=0 class="tabla_citas "  cellpadding=0 cellspacing=0>

		<tr>
		<td colspan=10>
		<table border=1  class='colortable' cellpadding=0 cellspacing=0 width="100%">

		<tr>
			<td class="tdcamposc">Num.</td>
			<td class="tdcamposc">Numero Recibo</td>
			<td class="tdcamposc">Factura</td>
			<td class="tdcamposc">Num. Control</td>
			<td class="tdcamposc">Banco</td>
			<td class="tdcamposc">Fecha</td>
<?php
if ($tipo_cheque==0)
{
?>
			<td class="tdcamposc">Titular</td>
			<td class="tdcamposc">Cedula</td>
<?php
}
else
{
    if ($recibo_che==2)
{
?>

    <td class="tdcamposc">Compro IVA</td>
    <td class="tdcamposc">Analista</td>

<?php
}
    if ($recibo_che==3)
{
?>

    <td class="tdcamposc">Analista</td>
    <td class="tdcamposc">Compro ISLR</td>

<?php
}
 if ($recibo_che==1)
{
?>

    <td class="tdcamposc">Analista</td>
    <td class="tdcamposc"></td>

<?php
}
}
?>

			<td class="tdcamposc"><?php if ($tipo_cheque==0){
                                                    echo "Analista";
                                                    }
                                                    else
                                                    {
                                                        echo "Proveedor";
                                                    }?></td>
            <?php
if ($tipo_cheque==0)
{
?>
			<td class="tdcamposc">procesos</td>
<?php
}
else
{
?>
			<td class="tdcamposc">Motivo</td>
<?php
}
?>

			<td class="tdcamposc">Monto Cheque</td>
		</tr>
<?php
/* **** buscamos  los recibos de reembolsos pagados por caja chica ***** */

if ($recibo_che==0){//recibo
	 			$q_cheques=("select
												admin.nombres,
												admin.apellidos,
												facturas_procesos.comprobante,
												facturas_procesos.num_recibo,
												facturas_procesos.id_proveedor,
												facturas_procesos.tipo_proveedor,
												facturas_procesos.codigo,
												count(facturas_procesos.codigo)
                    from facturas_procesos,admin
                    where $fecha facturas_procesos.num_recibo>0 and facturas_procesos.id_admin=admin.id_admin
                    		$condicion_sucursal  $tbanco $ttipo_cheque
										group by
                        admin.nombres,
												admin.apellidos,
												facturas_procesos.comprobante,
                        facturas_procesos.num_recibo,
												facturas_procesos.id_proveedor,
												facturas_procesos.tipo_proveedor,
												facturas_procesos.codigo
												order by facturas_procesos.comprobante ");

}
/* **** fin de buscar  los recibos de reembolsos pagados por caja chica ***** */

  /* **** buscamos  los cheque de acuerdo a la seleccion de un tipo proveedor ***** */
if ($recibo_che==1 and $proveedor>1) ///cheques y proveedores mayores a 1
  {
	 	$q_cheques=("select
                      admin.nombres,
											admin.apellidos,
											facturas_procesos.id_proveedor,
											facturas_procesos.tipo_proveedor,
											facturas_procesos.numero_cheque,
                    	count(facturas_procesos.numero_cheque)
                    from facturas_procesos,admin
                  	where
											$fecha facturas_procesos.id_admin_cheque=admin.id_admin
                 			$condicion_sucursal  $tbanco $ttipo_cheque  $numcheque $proveedores
										group by
                 			admin.nombres,
											admin.apellidos,
											facturas_procesos.numero_cheque,
											facturas_procesos.id_proveedor,
                			facturas_procesos.tipo_proveedor
										order by facturas_procesos.numero_cheque");

	}
    /* **** fin de buscar los cheque de acuerdo a la seleccion de un tipo proveedor ***** */

    /* **** buscamos  los cheque de acuerdo a la seleccion de todos los tipo proveedor ***** */
    if ($recibo_che==1 and $proveedor==0)
  { //cheque mas rembolso y todos excepto rembolsos
		$q_cheques=("select
                      admin.nombres,
											admin.apellidos,
											facturas_procesos.id_proveedor,
											facturas_procesos.tipo_proveedor,
											facturas_procesos.numero_cheque,
											facturas_procesos.fecha_imp_che,
                    	count(facturas_procesos.numero_cheque)
                 from
										facturas_procesos,admin
                 where
									 	$fecha facturas_procesos.id_admin_cheque=admin.id_admin
                 		$condicion_sucursal  $tbanco $ttipo_cheque  $numcheque
								 group by
	                 admin.nombres,
									 admin.apellidos,
									 facturas_procesos.numero_cheque,
									 facturas_procesos.id_proveedor,
	                 facturas_procesos.tipo_proveedor,
									 facturas_procesos.fecha_imp_che
								order by facturas_procesos.numero_cheque");
		}
/* **** fin de buscar  los cheque de acuerdo a la seleccion de todos los tipo proveedor ***** */


/* **** buscamos las retenciones ya sea de iva o islr de acuerdo a la seleccion de un tipo proveedor especifico***** */
    if ($recibo_che==2 || $recibo_che==3)
			{ //iva y islr

	 $q_cheques=("select
	 							admin.nombres,
								admin.apellidos,
								facturas_procesos.comprobante,
								facturas_procesos.numero_cheque,
								facturas_procesos.id_proveedor,
								facturas_procesos.tipo_proveedor,
								facturas_procesos.codigo,
								count(facturas_procesos.codigo)
              from facturas_procesos,admin
              where
								$fecha facturas_procesos.id_admin=admin.id_admin
                $condicion_sucursal  $tbanco $ttipo_cheque  $recibo_che1 $numcheque $proveedores
							group by
								admin.nombres,
								admin.apellidos,
								facturas_procesos.comprobante,
                facturas_procesos.numero_cheque,
								facturas_procesos.id_proveedor,
								facturas_procesos.tipo_proveedor,
								facturas_procesos.codigo
              order by facturas_procesos.comprobante");

	}
     /* **** fin de buscar las retenciones ya sea de iva o islr de acuerdo a la seleccion de un tipo proveedor especifico***** */

	  /* **** buscamos todos las retenciones (proveedores de comporas medico otros clinicas etc)ya sea de iva o islr de acuerdo a la seleccion***** */
    if (($recibo_che==2 || $recibo_che==3) and $proveedor==0)
		{ //iva y islr + todos eceptos rembolsos y rembolso
	$q_cheques=("select
													admin.nombres,
													admin.apellidos,
													facturas_procesos.comprobante,
                      		facturas_procesos.numero_cheque,
													facturas_procesos.id_proveedor,
													facturas_procesos.tipo_proveedor,
													facturas_procesos.codigo,
													count(facturas_procesos.codigo)
                    from facturas_procesos,admin
                  	where
												$fecha facturas_procesos.id_admin=admin.id_admin
                 				$condicion_sucursal  $tbanco $ttipo_cheque  $recibo_che1 $numcheque
										group by
												admin.nombres,
												admin.apellidos,
												facturas_procesos.comprobante,
												facturas_procesos.numero_cheque,
												facturas_procesos.id_proveedor,
												facturas_procesos.tipo_proveedor,
												facturas_procesos.codigo
                 	order by facturas_procesos.comprobante");
	}
/* **** fin  de buscar todos las retenciones ya sea de iva o islr de acuerdo a la seleccion***** */
/* **** buscamos las retenciones ya sea de iva o islr de acuerdo a la seleccion de un tipo proveedor especifico***** */
		if ($banco==13 || $banco==15)
		{	 	$q_cheques=("select admin.nombres,
												admin.apellidos,
												facturas_procesos.comprobante,
                      	facturas_procesos.numero_cheque,
												facturas_procesos.id_proveedor,
												facturas_procesos.tipo_proveedor,
												facturas_procesos.codigo,
												count(facturas_procesos.codigo)
                    from facturas_procesos,admin
                  	where $fecha facturas_procesos.id_admin=admin.id_admin
                 				$condicion_sucursal  $tbanco $ttipo_cheque  $recibo_che1 $numcheque $proveedores
										group by
												admin.nombres,
												admin.apellidos,
												facturas_procesos.comprobante,
                 				facturas_procesos.numero_cheque,
												facturas_procesos.id_proveedor,
												facturas_procesos.tipo_proveedor,
												facturas_procesos.codigo
                 	order by facturas_procesos.comprobante");

	}
     /* **** fin de buscar las retenciones ya sea de iva o islr de acuerdo a la seleccion de un tipo proveedor especifico***** */

	$r_cheques=ejecutar($q_cheques);
$numeroregistro=0;
	while($f_cheques=pg_fetch_array($r_cheques,NULL,PGSQL_ASSOC)){
        /* **** verifico que tipo de servicios es para activar la condicion que me dice si consulto por numero cheque o codigo **** */
				/////contador de numero de registros
				$numeroregistro+=1;
				if ($recibo_che==0){
            $condicion="facturas_procesos.numero_cheque='$f_cheques[numero_cheque]' $tbanco";
            }

  if ($recibo_che==1 and $proveedor>1)
  {
      $condicion="facturas_procesos.numero_cheque='$f_cheques[numero_cheque]' $tbanco";
      }

        if ($recibo_che==1 and $proveedor==0)
  {
      $condicion="facturas_procesos.numero_cheque='$f_cheques[numero_cheque]' $tbanco";
      }
          if ($recibo_che==2 || $recibo_che==3)
			{
    $condicion="facturas_procesos.codigo='$f_cheques[codigo]'";
	    }

        if (($recibo_che==2 || $recibo_che==3) and $proveedor==0)
{
    $condicion="facturas_procesos.codigo='$f_cheques[codigo]'";
    }

            if ($banco==13 || $banco==15)
{
    $condicion="facturas_procesos.codigo='$f_cheques[codigo]'";
    }

    /* **** fin de verificar la condicion**** */
if ($tipo_cheque==0){
		if ($recibo_che==0){
		$q_clientes=("select  clientes.nombres,
                                         clientes.apellidos,
                                         clientes.cedula,
                                         bancos.nombre_banco,
                                         facturas_procesos.comprobante,
                                         facturas_procesos.fecha_creado
                                from
                                        clientes,
                                        bancos,
                                        facturas_procesos,
                                        admin
                                where
                                        clientes.cedula=facturas_procesos.cedula and
                                        facturas_procesos.id_banco=bancos.id_banco and
                                        facturas_procesos.num_recibo='$f_cheques[num_recibo]' and
                                        facturas_procesos.codigo='$f_cheques[codigo]' and
                                        facturas_procesos.id_admin=admin.id_admin
                                        $condicion_sucursal");
		}
		else
		{
			$q_clientes=("select  clientes.nombres,
                                             clientes.apellidos,
                                             clientes.cedula,
                                             bancos.nombre_banco,
                                             facturas_procesos.comprobante,
                                             facturas_procesos.fecha_creado
                                    from
                                            clientes,
                                            bancos,
                                            facturas_procesos,
                                            admin
                                    where
                                            clientes.cedula=facturas_procesos.cedula and
                                            facturas_procesos.id_banco=bancos.id_banco and
                                            facturas_procesos.numero_cheque='$f_cheques[numero_cheque]'  and
                                            facturas_procesos.id_admin=admin.id_admin
                                            $condicion_sucursal");
			}

	$r_clientes=ejecutar($q_clientes);
	$f_clientes=pg_fetch_array($r_clientes,NULL,PGSQL_ASSOC);
	}


/* **** compraro si busco proveedor persona o proveedor clinica **** */

/* **** BUSCO EL PROVEEDOR  persona **** */
if ($f_cheques[tipo_proveedor]==1){
$q_proveedor=("select   personas_proveedores.celular_prov,
                                    personas_proveedores.nomcheque,
                                    personas_proveedores.rifcheque,
                                    personas_proveedores.direccioncheque,
                                    actividades_pro.codigo,
                                    actividades_pro.porcentaje,
                                    actividades_pro.sustraendo,
                                    facturas_procesos.tipo_proveedor,
                                    facturas_procesos.id_proveedor,
                                    facturas_procesos.motivo,
                                    facturas_procesos.compro_retiva_seniat,
                                    facturas_procesos.corre_compr_islr,
                                    bancos.nombre_banco,
                                    facturas_procesos.fecha_creado,
                                    facturas_procesos.fecha_creado,
                                    facturas_procesos.fecha_imp_che,
																		facturas_procesos.factura,
																		facturas_procesos.no_control_fact,
																		facturas_procesos.id_banco
                            from
                                    personas_proveedores,
                                    actividades_pro,
                                    facturas_procesos,
                                    bancos
                            where
                                    personas_proveedores.id_persona_proveedor='$f_cheques[id_proveedor]' and
                                    personas_proveedores.id_act_pro=actividades_pro.id_act_pro and
                                    facturas_procesos.id_banco=bancos.id_banco and
                                    $condicion");
$r_proveedor=ejecutar($q_proveedor);
$num_filas2=num_filas($r_proveedor);
if ($num_filas2>0)
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nomcheque]";
$rifpro=$f_proveedor[rifcheque];
$direccionpro=$f_proveedor[direccioncheque];
$telefonospro=$f_proveedor[celular_pro];
$sustraendo=$f_proveedor[sustraendo];
$motivo=$f_proveedor[motivo];
$tipo_cheque1=$f_proveedor[tipo_proveedor];
$factura=$f_proveedor['factura'];
$controlfactura=$f_proveedor['no_control_fact'];
$id_banco=$f_proveedor['id_banco'];

}
else
{
/* **** BUSCO EL PROVEEDOR  clinica**** */
$q_proveedor=("select clinicas_proveedores.*,
                                    actividades_pro.codigo,
                                    actividades_pro.porcentaje,
                                    actividades_pro.sustraendo,
                                    bancos.nombre_banco,
                                    facturas_procesos.comprobante,
                                    facturas_procesos.motivo,
                                    facturas_procesos.fecha_creado,
                                    facturas_procesos.fecha_imp_che,
                                    facturas_procesos.compro_retiva_seniat,
                                    facturas_procesos.corre_compr_islr,
                                    facturas_procesos.ret_individual,
                                    facturas_procesos.tipo_proveedor,
                                    facturas_procesos.id_proveedor,
																		facturas_procesos.factura,
																		facturas_procesos.no_control_fact,
																		facturas_procesos.id_banco
                            from
                                    clinicas_proveedores,
                                    proveedores,actividades_pro,
                                    facturas_procesos,
                                    bancos
                            where
                                    clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
                                    proveedores.id_proveedor='$f_cheques[id_proveedor]' and
                                    clinicas_proveedores.id_act_pro=actividades_pro.id_act_pro and
                                    facturas_procesos.id_proveedor=proveedores.id_proveedor and
                                    facturas_procesos.id_banco=bancos.id_banco and
                                    $condicion ");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nomcheque]";
$rifpro=$f_proveedor[rifcheque];
$direccionpro=$f_proveedor[direccioncheque];
$telefonospro=$f_proveedor[telefonos];
$sustraendo=$f_proveedor[sustraendo];
$motivo=$f_proveedor[motivo];
$tipo_cheque1=$f_proveedor[tipo_proveedor];
$factura=$f_proveedor['factura'];
$controlfactura=$f_proveedor['no_control_fact'];
$id_banco=$f_proveedor['id_banco'];
}
/* **** FIN DE BUSCAR PROVEEDOR **** */

?>
<tr>
<td class="tdcamposac"><?php echo $numeroregistro;?></td>
<td class="tdcamposac"><?php echo "$f_cheques[num_recibo] $f_cheques[numero_cheque]";?></td>
<td class="tdcamposac"><?php echo "$factura";?></td>
<td class="tdcamposac"><?php echo "$controlfactura";?></td>
<td class="tdcamposac"><?php if ($banco==9){

    $q_banco="select * from bancos,facturas_procesos where
bancos.id_banco=facturas_procesos.id_banco_anulado and facturas_procesos.numero_cheque='$f_cheques[numero_cheque]' ";
$r_banco=ejecutar($q_banco);
$f_banco=asignar_a($r_banco);

  echo "$f_banco[nombre_banco] $f_clientes[nombre_banco]";
    }
    else
    {
        echo "$f_proveedor[nombre_banco] $f_clientes[nombre_banco]";
    }


?></td>
<td class="tdcamposac"><?php if ($recibo_che==3 || $recibo_che==1){
    echo "$f_proveedor[fecha_imp_che]";
    }
    else
    {
        echo "$f_proveedor[fecha_creado] $f_clientes[fecha_creado]";

        }
		if ($tipo_cheque=='0')
		{
			echo $f_cheques[fecha_imp_che];
			}
		?></td>
<td class="tdcamposac"><?php
 if ($recibo_che==3)
  {
      echo "$f_cheques[nombres] $f_cheques[apellidos]";
      }
      else
      {
    if ($recibo_che==1  and $tipo_cheque>=1 and $tipo_cheque<=5)
  {
      echo "$f_cheques[nombres] $f_cheques[apellidos]";
      }
      else
      {
echo "$f_proveedor[compro_retiva_seniat] $f_clientes[nombres] $f_clientes[apellidos]";

if ($f_proveedor[compro_retiva_seniat]>0){
	if ($f_cheques[id_banco]=='' or empty($f_cheques[id_banco])) {
		$id_banco=$id_banco;
	}else{$id_banco=$f_cheques[id_banco];}

    if ($f_cheques[tipo_proveedor]==4 || $f_cheques[tipo_proveedor]==2 || $f_cheques[tipo_proveedor]==1){
$url="'views04/icom_ret_iva2.php?codigo=$f_cheques[codigo]&banco=$id_banco&nombreprov=$nombreprov&cedula=$f_pc[rif]&prov=$tipo_cheque1&fecha_emision=$f_proveedor[fecha_creado]&compro_retiva_seniat=$f_proveedor[compro_retiva_seniat]&id_proveedor=$f_cheques[id_proveedor]&direccionpro=$direccionpro'";
?> <a href="javascript: imprimir(<?php echo $url; ?>);"  title="Imprimir Comprobante Retencion IVA Otros">F 1</a>
<?php
}
else
{

$url="'views04/icom_ret_iva.php?codigo=$f_cheques[codigo]&banco=$id_banco&nombreprov=$nombreprov&cedula=$f_pc[rif]&prov=$tipo_cheque1&fecha_emision=$f_proveedor[fecha_creado]&compro_retiva_seniat=$f_proveedor[compro_retiva_seniat]&id_proveedor=$f_cheques[id_proveedor]&direccionpro=$direccionpro'";
?> <a href="javascript: imprimir(<?php echo $url; ?>);"  title="Imprimir  Comprobante Retencion IVA Compras ">F 2</a>
<?
}
}
}
}
?>
</td>
<td class="tdcamposac"><?php
 if ($recibo_che==2)
  {
      echo "$f_cheques[nombres] $f_cheques[apellidos]";
      }
      else
      {
  if ($recibo_che==1 and $proveedor==0 and $tipo_cheque>=1 and $tipo_cheque<=5)
  {

      }
      else
      {
echo "$f_proveedor[corre_compr_islr] $f_clientes[cedula]";
if ($f_proveedor[corre_compr_islr]>0){
	if ($f_cheques[id_banco]=='' or empty($f_cheques[id_banco])) {
		$id_banco=$id_banco;
	}else{$id_banco=$f_cheques[id_banco];}
    $url="'views04/icom_ret_islr.php?codigo=$f_cheques[codigo]&banco=$id_banco&nombreprov=$nombreprov&cedula=$f_pc[rif]&prov=$tipo_cheque1&fecha_emision=$f_proveedor[fecha_creado]&compro_retiva_islr=$f_proveedor[corre_compr_islr]&direccionpro=$direccionpro&id_proveedor=$f_cheques[id_proveedor]&personaprov=2&ret_indi=$f_proveedor[ret_individual]'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);"  title="Imprimir Comprobante Retencion ISLR con Honorarios Medicos y Clinicos"> F 1</a>
<?php
$url="'views04/icom_ret_islr2.php?codigo=$f_cheques[codigo]&banco=$id_banco&nombreprov=$nombreprov&cedula=$f_pc[rif]&prov=$tipo_cheque1&fecha_emision=$f_proveedor[fecha_creado]&compro_retiva_islr=$f_proveedor[corre_compr_islr]&direccionpro=$direccionpro&id_proveedor=$f_cheques[id_proveedor]&personaprov=2&ret_indi=$f_proveedor[ret_individual]'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);"  title="Imprimir Comprobante Retencion ISLR para otros y medicos"> F 2</a>
<?php
}
}
}
?>
</td>
<td class="tdcamposac"><?php
 if ($tipo_cheque==0){
                                                    echo "$f_cheques[nombres] $f_cheques[apellidos]";
                                                    }
                                                    else
                                                    {
                                                        echo $nombrepro;
                                                    }
?>
</td>
<td class="tdcamposac">
<?
$montot=0;

if ($recibo_che==0){
$q_cheques1=("select * from facturas_procesos,admin where num_recibo='$f_cheques[num_recibo]'  and facturas_procesos.codigo='$f_cheques[codigo]' and facturas_procesos.id_admin=admin.id_admin  $condicion_sucursal $tbanco");
}
if ($recibo_che==1){
	$q_cheques1=("select * from facturas_procesos,admin where numero_cheque='$f_cheques[numero_cheque]'  and facturas_procesos.id_admin_cheque=admin.id_admin  $condicion_sucursal $tbanco");
	}
    if ($recibo_che==2 || $recibo_che==3){
	$q_cheques1=("select * from facturas_procesos,admin where codigo='$f_cheques[codigo]'  and facturas_procesos.id_admin=admin.id_admin  $condicion_sucursal");
	}
    if ($banco==13 || $banco==15){
	$q_cheques1=("select * from facturas_procesos,admin where codigo='$f_cheques[codigo]'  and facturas_procesos.id_admin=admin.id_admin  $condicion_sucursal");
	}
$r_cheques1=ejecutar($q_cheques1);
$gastosclinicos=0;
$gastosmedicos=0;
$totalgastos=0;
$retencion=0;
$monto_final=0;
$iva=0;
$ivaret=0;
$total_ret=0;
$montoexento=0;
$total_neto=0;
while($f_cheques1=pg_fetch_array($r_cheques1,NULL,PGSQL_ASSOC)){
if ($tipo_cheque1==3)
{
$total_neto=$total_neto +  (($f_cheques1[monto_sin_retencion] + $f_cheques1[monto_con_retencion] + $f_cheques1[iva]) - $f_cheques1[iva_retenido]);
}
if ($tipo_cheque==0)
{
$monto= (($f_cheques1[monto_con_retencion] - (($f_cheques1[monto_con_retencion] * $f_cheques1[retencion])/100) ) + $f_cheques1[monto_sin_retencion]);
$montot=$montot + $monto;
$montott= $montott +$monto;

if ($f_cheques1[id_proceso]<>0)
{
echo "$f_cheques1[id_proceso],";
}
}
else
{
    $ret_individual=$f_cheques1[ret_individual];
	$banco=$f_cheques1[id_banco];
	$iva=$iva + $f_cheques1[iva];
	$ivaret=$ivaret + $f_cheques1[iva_retenido];
	$total_ret=	$total_ret + $f_cheques1[retencion];
	if ($tipo_cheque==4 and $f_cheques1[iva]==0)
{
$montoexento= $montoexento + $f_cheques1[monto_sin_retencion];
$montoexentot= $montoexentot + $f_cheques1[monto_sin_retencion];
}
	$gastosclinicos=$gastosclinicos + $f_cheques1[monto_sin_retencion];
    $gastosmedicos=$gastosmedicos + $f_cheques1[monto_con_retencion];


	$totalgastos=$gastosclinicos  + $gastosmedicos;
	$totaldescuento=0;
	$retencion=$retencion + $f_cheques1[retencion];
    $comprobante=$f_cheques1[comprobante];
	$comprobanteislr=$f_cheques1[corre_compr_islr];
	$compro_retiva_seniat=$f_cheques1[compro_retiva_seniat];
	$comprobanteiva=$f_cheques1[corre_retiva_seniat];
	$cheque=$f_cheques1[numero_cheque];
	$recibo=$f_cheques1[num_recibo];
	$nombre=$f_cheques1[anombre];
	$cedula=$f_cheques1[ci];
	$fecha_emision=$f_cheques1[fecha_creado];

}

}
if ($tipo_cheque<>0)
{

if ($retencion==0){
    $retencion=$sustraendo;
        $retencion=$retencion - $sustraendo;
    }
    else
    {
        $retencion=$retencion - $sustraendo;
        }

        if ($ret_individual==1)
        {
            $retencion=$retencion + $sustraendo;
        }
$monto_f= (($gastosclinicos + $gastosmedicos)  - $retencion);
if ($tipo_cheque1==3)
{
$montot=    $total_neto;
    }
    else
    {
$montot= ($monto_f) - ($ivaret);
}
if ($banco==9)
{
    $montot=0;
    }
    if ($f_cheques[tipo_proveedor]==3){
        $montot=number_format($montot,2,'.','');
        }
$montott= $montott +$montot;
$subtotal=$subtotal + $totalgastos - $totaldescuento;

echo $motivo;
}
?>
</td>
<td class="tdcamposac"> <?php echo montos_print($montot) ?></td>
</tr>

<?php
}
?>
<tr class='nomostrar'>
<td colspan=9 class="tdcamposac"></td>

<td class="tdcamposc">
Total
</td>
<td class="tdcamposc"> <?php echo montos_print($montott) ?></td>
</tr>
<tr class='nomostrar'>
<td colspan=9 class="tdcamposac"></td>

<td class="tdcamposc" >
	<br>
	<br>
</td>
<td class="tdcamposc" class='nomostrar'>

												<?php
                        $url="'views06/irep_rel_rec1.php?fechainicio=$fecha_inicio&fechafin=$fecha_fin&sucursal=$id_sucursal&recibo_che=$recibo_che&tipo_cheque=$tipo_cheque&banco=$banco1&tipo_fecha=$tipo_fecha&proveedor=$proveedor'";
                        ?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a></td>
</tr>

</table>
