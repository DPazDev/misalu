<?php
include ("../../lib/jfunciones.php");

$banco=$_REQUEST['banco'];
$ente=$_REQUEST['ente'];
$honorarios1=$_REQUEST['honorarios1'];
$codigo1=$_REQUEST['codigo1'];
$proceso1=$_REQUEST['proceso1'];
$conexa=$_REQUEST['conexa'];
$cedula=$_REQUEST['cedula'];
$numcheque=$_REQUEST['numcheque'];
$servicio1=$_REQUEST['servicio1'];
$factura1=$_REQUEST['factura1'];
$factura2=split("@",$factura1);
$servicio2=split("@",$servicio1);
$honorarios2=split("@",$honorarios1);
$proceso2=split("@",$proceso1);

if ($banco==8)
{

	$numcheque=0;
	}
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
/* **** busco el usuario admin **** */
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin="select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$admin' and admin.id_sucursal=sucursales.id_sucursal";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
/* **** fin de buscar el usuario admin **** */
$codigo=time();
/* **** busco el ultimo comprobante **** */
$q_facturap="select * from facturas_procesos order by facturas_procesos.id_factura_proceso desc limit 1;";
$r_facturap=ejecutar($q_facturap);

if(num_filas($r_facturap)==0){
	$no_comprobante="1";
}
else
{
	$f_facturap=asignar_a($r_facturap);
	$no_comprobante=$f_facturap[comprobante];
	$no_comprobante++;
}
/* **** fin buscar el ultimo comprobante **** */

/* **** busco el ultimo recibo segun la sucursal que pertenezca el admin **** */
if ($banco==8)
{
$q_facturap1="select * from facturas_procesos,admin where facturas_procesos.id_admin=admin.id_admin and admin.id_sucursal=$f_admin[id_sucursal] and facturas_procesos.id_banco=8 order by num_recibo desc limit 1;";
$r_facturap1=ejecutar($q_facturap1);
	
if(num_filas($r_facturap1)==0){
	$no_factura1="1";
	$descripcion="Recibo numero $no_factura1";
}
else
{
		
	$f_facturap1=asignar_a($r_facturap1);
	$no_factura1=$f_facturap1[num_recibo];
	$no_factura1++;
	$descripcion="Recibo numero $no_factura1";
}
}

else
{
	$descripcion="Cheque numero $numcheque";
	$no_factura1=0;
	}
   
/* **** fin de buscar el ultimo recibo segun la sucursal que pertenezca el admin **** */
$q="
begin work;
";

for($i=0;$i<=$conexa;$i++){
   
   
   
   
	$honorarios=$honorarios2[$i];
	$proceso=$proceso2[$i];
	$servicio=$servicio2[$i];
	$factura=$factura2[$i];
    
    /* 
      echo $proceso;
        echo "****   ****";
       echo      $fechacreado;
        echo "****   ****";
       echo  $hora;
echo "****   ****";
echo         $admin;
echo "****   ****";
echo         $servicio;
echo "****   ****";
echo         $codigo;
echo "****   ****";
echo         "0";
echo "****   ****";
echo         $numcheque;
echo "****   ****";
echo         $no_comprobante;
echo "****   ****";
echo         "0";
echo "****   ****";
echo         "0";
echo "****   ****";
echo         "0";
echo "****   ****";
echo         "0";
echo "****   ****";
echo         $honorarios;
echo "****   ****";
echo         $banco;
echo "****   ****";
echo         $cedula;
echo "****   ****";
echo         0;
echo "****   ****";
echo         $factura;
echo "****   ****";
echo         $no_factura1;
echo "****   ****";
echo         "CANCELACION DE REEMBOLSO";
echo "****   ****";
echo         $fechacreado;*/
    
    
	if(!empty($proceso) && $proceso>0){
        
/*   
      */
        
        
		$procesot .=$proceso .",";
	$q.="
insert into facturas_procesos 
                (id_proceso,
                fecha_creado,
                hora_creado,
                id_admin,
                id_servicio,
                codigo,
                id_proveedor,
                numero_cheque,
                comprobante,
                monto_con_retencion,
                retencion,
                descuento,
                iva,
                monto_sin_retencion,
                id_banco,cedula,
                tipo_proveedor,
                factura,
                num_recibo,
                motivo,
                fecha_imp_che,
                id_admin_cheque) 
values 
                ('$proceso',
                '$fechacreado',
                '$hora',
                '$admin',
                '$servicio',
                '$codigo',
                '0',
                '$numcheque',
                '$no_comprobante',
                '0',
                '0',
                '0',
                '0',
                '$honorarios',
                '$banco',
                '$cedula',
                '0',
                '$factura',
                '$no_factura1',
                'CANCELACION DE REEMBOLSO',
                '$fechacreado',
                '$admin');

update procesos set id_estado_proceso=15 where
procesos.id_proceso='$proceso';
";
	}
}
$q.="
commit work;
";
$r=ejecutar($q);


	
/* **** Se registra lo que hizo el usuario**** */


$log="Genero el $descripcion  con las ordenes ($procesot) y codigo numero $codigo";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
?>





<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>		<td colspan=7 class="titulo_seccion">Cheque Generado Con Exito</td>	</tr>
<tr>
		<td class="tdtitulos"><?php
	
			$url="'views04/icheque_reem.php?codigo=$codigo&cedula=$cedula&ente=$ente&banco=$banco'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Cheque o Recibo"> Imprimir</a>
			
			<a href="#" OnClick="che_reembolso();" class="boton" title="Ir a Chueques Reembolsos">Crear Otro Cheque de Reembolso</a><a href="#" OnClick="ir_principal();" class="boton">salir</a>
			</td>
	</tr>

</table>


