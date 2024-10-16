<?php
include ("../../lib/jfunciones.php");
sesion();

list($id_sucursal,$sucursal)=explode("@",$_REQUEST['sucursal']);

if($id_sucursal==0)	$condicion_sucursal="and admin.id_sucursal>0";
else			$condicion_sucursal="and admin.id_sucursal=$id_sucursal  ";

//si la condicion sigue en este punto vacia hay que buscar por cada proceso su proveedor.
$recibo_che=$_REQUEST['recibo_che'];
$fecha_inicio=$_REQUEST['fechainicio'];
$fecha_fin=$_REQUEST['fechafin'];
$tipo_cheque=$_REQUEST['tipo_cheque'];
$proveedor=$_POST['proveedor'];
$tipo_fecha=$_REQUEST['tipo_fecha'];
$banco=$_REQUEST['banco'];
$banco1=$_REQUEST['banco'];
$fechacreado=date("Y-m-d");

$numcheque=" ";

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
if ($recibo_che==1)
{
if ($banco=='*'){
	$tbanco="and facturas_procesos.id_banco<>9 and facturas_procesos.id_banco<>13 and facturas_procesos.id_banco<>15";
 	}
	else
	{
		$tbanco="and facturas_procesos.id_banco=$banco";
        
		}
}
else
{
  if ($banco=='*'){
	$tbanco="and facturas_procesos.id_banco<>9";
 	}
	else
	{
		$tbanco="and facturas_procesos.id_banco=$banco";
        
		}  
    }
/* fin verifico el tipo de consulta si es por cheque o iva islr o recibo para armar variable $tbanco */

/* verifico que tipo de proveedor voy a buscar en facturas_procesos si medico clinica otros etc  */
    
if ($tipo_cheque==5){
	$ttipo_cheque="and facturas_procesos.tipo_proveedor<>0";
    }
	else
	{
        $ttipo_cheque="and facturas_procesos.tipo_proveedor=$tipo_cheque";
        }
/*fin  verifico que tipo de proveedor voy a buscar en facturas_procesos si medico clinica otros etc  */

/* verifico que tipo de busqueda voy a realizar si por recibo, por cheque de islr o iva  */
if ($recibo_che==2){
	$recibo_che1="and facturas_procesos.corre_retiva_seniat>0";
    $fecha="facturas_procesos.fecha_creado>='$fecha_inicio' and facturas_procesos.fecha_creado<='$fecha_fin' and";
	$order="order by facturas_procesos.corre_retiva_seniat";
   
    }        
if ($recibo_che==3){
	$recibo_che1=" and facturas_procesos.corre_ret_islr>0";
    $fecha="facturas_procesos.fecha_imp_che>='$fecha_inicio' and facturas_procesos.fecha_imp_che<='$fecha_fin' and";    
	$order="order by facturas_procesos.corre_ret_islr";
    }
    
  if ($recibo_che==1 ){
	
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

 /* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
?>

<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=2 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=8 class="titulo">

</td>
</tr>
<tr>
<td colspan=2 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=6 class="titulo">

</td>
<td colspan=4 class="titulo1">
<?php echo "$f_admin[sucursal] $fechacreado"?>
</td>
</tr>
<tr>
<td >
<br>
</br>
</td>
</tr>

		<tr>
		<td colspan=10>
		<table border=1 cellpadding=0 cellspacing=0 width="100%">
	
		<tr>
			<td class="titulo3">Numero Recibo o Cheque</td>
			<td class="titulo3">Banco</td>
			<td class="titulo3">Fecha</td>
<?php 
if ($tipo_cheque==0)
{
?>
			<td class="titulo3">Titular</td>
			<td class="titulo3">Cedula</td>
<?php 
}
else
{
    if ($recibo_che==2)
{
?>
			<td class="titulo3">Compro IVA</td>
			<td class="titulo3">Analista</td>

<?php 
}
    if ($recibo_che==3)
{
?>
	
    <td class="titulo3">Analista</td>
    <td class="titulo3">Compro ISLR</td>

<?php 
}
 if ($recibo_che==1)
{
?>
	
    <td class="titulo3">Analista</td>
    <td class="titulo3"></td>

<?php 
}
}
?>

	<td class="titulo3"><?php if ($tipo_cheque==0){ 
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
			<td class="titulo3">procesos</td>
<?php 
}
else
{
?>
			<td class="titulo3">Motivo</td>
<?php 
}
?>
            
			<td class="titulo3">Monto Cheque</td>
		</tr>
<?php 
/* **** buscamos  los recibos de reembolsos pagados por caja chica ***** */

if ($recibo_che==0){
   
	$q_cheques=("select admin.nombres,admin.apellidos,facturas_procesos.comprobante,
                             facturas_procesos.num_recibo,facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor,facturas_procesos.codigo,count(facturas_procesos.codigo) 
                           from facturas_procesos,admin
                     where $fecha 
                    facturas_procesos.num_recibo>0 and facturas_procesos.id_admin=admin.id_admin  
                    $condicion_sucursal  $tbanco $ttipo_cheque  group by 
                         admin.nombres,admin.apellidos,facturas_procesos.comprobante,
                        facturas_procesos.num_recibo,facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor,
                        facturas_procesos.codigo  order by facturas_procesos.comprobante ");
}
/* **** fin de buscar  los recibos de reembolsos pagados por caja chica ***** */

  /* **** buscamos  los cheque de acuerdo a la seleccion de un tipo proveedor ***** */
if ($recibo_che==1 and $proveedor>1)
  {
	$q_cheques=("select 
                      admin.nombres,admin.apellidos,facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor,facturas_procesos.numero_cheque,
                    count(facturas_procesos.numero_cheque) 
                    from facturas_procesos,admin 
                  where $fecha facturas_procesos.id_admin_cheque=admin.id_admin  
                 $condicion_sucursal  $tbanco $ttipo_cheque  $numcheque $proveedores group by 
                 admin.nombres,admin.apellidos,facturas_procesos.numero_cheque,facturas_procesos.id_proveedor,
                facturas_procesos.tipo_proveedor order by facturas_procesos.numero_cheque");
	}
    /* **** fin de buscar los cheque de acuerdo a la seleccion de un tipo proveedor ***** */

    /* **** buscamos  los cheque de acuerdo a la seleccion de todos los tipo proveedor ***** */
    if ($recibo_che==1 and $proveedor==0)
  {
      
	$q_cheques=("select 
                      admin.nombres,admin.apellidos,facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor,facturas_procesos.numero_cheque,facturas_procesos.fecha_imp_che,
                    count(facturas_procesos.numero_cheque) 
                    from facturas_procesos,admin 
                  where $fecha facturas_procesos.id_admin_cheque=admin.id_admin  
                 $condicion_sucursal  $tbanco $ttipo_cheque  $numcheque  group by 
                 admin.nombres,admin.apellidos,facturas_procesos.numero_cheque,facturas_procesos.id_proveedor,
                facturas_procesos.tipo_proveedor,facturas_procesos.fecha_imp_che order by facturas_procesos.numero_cheque");
	}
      /* **** fin de buscar  los cheque de acuerdo a la seleccion de todos los tipo proveedor ***** */
    /* **** buscamos las retenciones ya sea de iva o islr de acuerdo a la seleccion de un tipo proveedor especifico***** */
    if ($recibo_che==2 || $recibo_che==3)
{
      
	 $q_cheques=("select admin.nombres,admin.apellidos,facturas_procesos.comprobante,
                      facturas_procesos.numero_cheque,facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor,facturas_procesos.codigo,
count(facturas_procesos.codigo)
                    from facturas_procesos,admin 
                  where $fecha facturas_procesos.id_admin=admin.id_admin  
                 $condicion_sucursal  $tbanco $ttipo_cheque  $recibo_che1 $numcheque $proveedores  group by admin.nombres,admin.apellidos,facturas_procesos.comprobante,
                 facturas_procesos.numero_cheque,facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor,facturas_procesos.codigo 
                 order by facturas_procesos.comprobante");

	}
     /* **** fin de buscar las retenciones ya sea de iva o islr de acuerdo a la seleccion de un tipo proveedor especifico***** */
    /* **** buscamos todos las retenciones (proveedores de comporas medico otros clinicas etc)ya sea de iva o islr de acuerdo a la seleccion***** */
    if (($recibo_che==2 || $recibo_che==3) and $proveedor==0)
{
      
	 $q_cheques=("select admin.nombres,admin.apellidos,facturas_procesos.comprobante,
                      facturas_procesos.numero_cheque,facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor,facturas_procesos.codigo,
count(facturas_procesos.codigo)
                    from facturas_procesos,admin 
                  where $fecha facturas_procesos.id_admin=admin.id_admin  
                 $condicion_sucursal  $tbanco $ttipo_cheque  $recibo_che1 $numcheque  group by admin.nombres,admin.apellidos,facturas_procesos.comprobante,
                 facturas_procesos.numero_cheque,facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor,facturas_procesos.codigo 
                 order by facturas_procesos.comprobante");

	}
/* **** fin  de buscar todos las retenciones ya sea de iva o islr de acuerdo a la seleccion***** */
  /* **** buscamos las retenciones ya sea de iva o islr de acuerdo a la seleccion de un tipo proveedor especifico***** */
    if ($banco==13 || $banco==15)
{

     
	 $q_cheques=("select admin.nombres,admin.apellidos,facturas_procesos.comprobante,
                      facturas_procesos.numero_cheque,facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor,facturas_procesos.codigo,
count(facturas_procesos.codigo)
                    from facturas_procesos,admin 
                  where $fecha facturas_procesos.id_admin=admin.id_admin  
                 $condicion_sucursal  $tbanco $ttipo_cheque  $recibo_che1 $numcheque $proveedores  group by admin.nombres,admin.apellidos,facturas_procesos.comprobante,
                 facturas_procesos.numero_cheque,facturas_procesos.id_proveedor,facturas_procesos.tipo_proveedor,facturas_procesos.codigo 
                 order by facturas_procesos.comprobante");
         
	}
     /* **** fin de buscar las retenciones ya sea de iva o islr de acuerdo a la seleccion de un tipo proveedor especifico***** */

	$r_cheques=ejecutar($q_cheques);

	while($f_cheques=pg_fetch_array($r_cheques,NULL,PGSQL_ASSOC)){
                /* **** verifico que tipo de servicios es para activar la condicion que me dice si consulto por numero cheque o codigo **** */
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
                                    facturas_procesos.fecha_imp_che
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
                                    facturas_procesos.id_proveedor  
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
                                    $condicion");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nomcheque]";
$rifpro=$f_proveedor[rifcheque];
$direccionpro=$f_proveedor[direccioncheque];
$telefonospro=$f_proveedor[telefonos];
$sustraendo=$f_proveedor[sustraendo];
$motivo=$f_proveedor[motivo];
$tipo_cheque1=$f_proveedor[tipo_proveedor];
}
/* **** FIN DE BUSCAR PROVEEDOR **** */

?>
<tr>
<td class="tdcamposac"><?php echo "$f_cheques[num_recibo] $f_cheques[numero_cheque]"?></td>
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
    if ($recibo_che==1 and $proveedor==0 and $tipo_cheque>=1 and $tipo_cheque<=5)
  {
      echo "$f_cheques[nombres] $f_cheques[apellidos]";
      }
      else
      {
echo "$f_proveedor[compro_retiva_seniat] $f_clientes[nombres] $f_clientes[apellidos]";


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
	$q_cheques1=("select * from facturas_procesos,admin where numero_cheque='$f_cheques[numero_cheque]'  and facturas_procesos.id_admin=admin.id_admin  $condicion_sucursal $tbanco");
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
<tr>
<td colspan=6 class="tdcamposac"></td>

<td class="tdcamposc">
Total
</td>
<td class="tdcamposc"> <?php echo montos_print($montott) ?></td>
</tr>
<tr>
<td colspan=6 class="tdcamposac"></td>

<td class="tdcamposc">

</td>
<td class="tdcamposc"> <?php
                        $url="'views06/irep_rel_rec1.php?fechainicio=$fecha_inicio&fechafin=$fecha_fin&sucursal=$id_sucursal&recibo_che=$recibo_che&tipo=$tipo&tipo_cheque=$tipo_cheque&banco=$banco'";
                        ?> </td>
</tr>

</table>



