<?php
include ("../../lib/jfunciones.php");

$numchequeasig=$_REQUEST['numchequeasig'];
$bancoasig=$_REQUEST['bancoasig'];
$tipocuentaasig=$_REQUEST['tipocuentaasig'];
$codigo1=$_REQUEST['codigo1'];
$conche=$_REQUEST['conche'];
$motivoasig=$_REQUEST['motivoasig'];
$codigo2=split("@",$codigo1);
$fecha_imp_che=date("Y-m-d");
$id_proveedor=$_REQUEST[proveedor];
/*
echo $numchequeasig;
echo "******";
echo $bancoasig;
echo "******";
echo $tipocuentaasig;
echo "******";
echo $codigo1;
echo "******";
echo $conche;
echo "******";
echo $motivoasig;
echo "******";
echo $codigo2;
echo "******";
echo $id_proveedor;
echo "******";
*/
if ($bancoasig==8)
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


for($i=0;$i<=$conche;$i++){
	$codigo=$codigo2[$i];
	
	if(!empty($codigo) && $codigo>0){
		
	$mod_fproceso="update facturas_procesos 
            set 
                    numero_cheque='$numchequeasig', 
                    id_banco=$bancoasig,
                    motivo='$motivoasig',
                    id_tipo_cuenta='$tipocuentaasig',
                    fecha_imp_che='$fecha_imp_che',
                    id_admin_cheque='$admin' 
            where  
                    facturas_procesos.codigo='$codigo' ";
$fmod_fproceso=ejecutar($mod_fproceso);
	}
}
	
/* **** Se registra lo que hizo el usuario**** */


$log="Genero el $descripcion  codigo numero $codigo";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>		<td colspan=7 class="titulo_seccion">Cheque Generado Con Exito</td>	</tr>
<tr>
		<td class="tdtitulos">

         <?php
				$url="'views04/icheque_prov.php?numcheque=$numchequeasig&prov=3&mod=0&id_proveedor=$id_proveedor&banco=$bancoasig'";
			?> 
        <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Cheque o Recibo"> Imprimir Cheque Compras</a>
			  <?php
				$url="'views04/icheque_prov_islr.php?numcheque=$numchequeasig&prov=3&mod=0&id_proveedor=$id_proveedor&banco=$bancoasig'";
			?> 
        <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Cheque o Recibo"> Imprimir Cheque Otros medicos clinicas comisionados</a>
			
			</td>
	</tr>

</table>


