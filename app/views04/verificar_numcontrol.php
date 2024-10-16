<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$controlfactura=$_REQUEST['controlfactura'];
$ult_controlfactura=$_REQUEST['ult_controlfactura'];
$serie=$_REQUEST['serie'];
if  ($controlfactura==0 || ($controlfactura<=$ult_controlfactura)){
echo "EL NUMERO DE CONTROL NO PUEDE SER VACIO o MENOR o IGUAL  AL ANTERIOR";
    }
    else
    {
	

/* **** busco el numero de control para ver si esta asignado **** */
$q_control=("select 
							* 
					from 
							tbl_facturas,
							tbl_series
					where 
							tbl_facturas.numcontrol='$controlfactura' and
							tbl_facturas.id_serie=tbl_series.id_serie");
$r_control=ejecutar($q_control);
$num_filas=num_filas($r_control);

if  ($num_filas==0)

{

echo " NO ESTA ASIGNADO EL NUMERO DE CONTROL ";
}
  else
  {
$f_control=asignar_a($r_control);
$q_factura="select * from tbl_facturas where tbl_facturas.id_serie=$serie order by tbl_facturas.id_serie desc limit 1;";
$r_factura=ejecutar($q_factura);
$f_factura=asignar_a($r_factura);

echo "NUMERO DE CONTROL ESTA ASIGNADO A LA SIGUIENTE FACTURA $f_control[numero_factura] SERIE $f_control[nomenclatura]";	

}
?>


<?php
}
?>
