<?php
include ("../../lib/jfunciones.php");
sesion();
$conexa=$_POST['conexa'];
$idfactura1=$_POST['idfactura1'];
$honorarios1=$_POST['honorarios1'];
$forma_pago=$_POST['forma_pago'];
$nom_tarjeta=$_POST['nom_tarjeta'];
$banco=$_POST['banco'];
$no_cheque=$_POST['no_cheque'];
$estado_fac=$_POST['estado_fac'];
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];
/* **** Busqueda delo Admin **** */
$q_admin="select * from admin where id_admin=$admin";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$idfactura2=split("@",$idfactura1);
$honorarios2=split("@",$honorarios1);

$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$fecha=date("Y-m-d");
$hora=date("H:i:s");

for($i=0;$i<=$conexa;$i++){
	$idfactura=$idfactura2[$i];
	if(!empty($idfactura) && $idfactura>0){
		
		//Actualizo el estado de la factura en la bd.
$mod_factura="update 
									tbl_facturas 
							set  
									id_estado_factura='$estado_fac',
									id_banco='$banco',
									numero_cheque='$no_cheque',
									condicion_pago='$forma_pago'
							where 
									tbl_facturas.id_factura='$idfactura'	      
";
	$fmod_factura=ejecutar($mod_factura);
}
}

  //Guardar los datos en la tabla logs;
$mensaje="El usuario $elus con id_admin=$elid Actualizo Las Facturas a Pagadas y Numero de cheques $no_cheque";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);

/* **** Fin de lo que hizo el usuario **** */
?>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		
<td colspan=4 class="titulo_seccion">
Se Actualizo Las Facturas a Pagadas Con Exito 
 </td>	
</tr>

</table>
