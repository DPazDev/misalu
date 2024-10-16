<?
include ("../../lib/jfunciones.php");
sesion();
$factura=$_REQUEST['factunum'];
$control=$_REQUEST['faccontr'];
$fecha=$_REQUEST['facfecha'];
$numorden=$_REQUEST['laorden'];
$proveeid=$_REQUEST['elprove'];
$lafactura=("update tbl_ordenes_compras set no_factura='$factura',no_control_fact='$control',
                                 fecha_emi_factura='$fecha' where tbl_ordenes_compras.id_orden_compra=$numorden;");
$replafac=ejecutar($lafactura);                                 
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificado la factura con el id_orden_compra=$numorden";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<h1>La factura se ha modificado exitosamente</h1>
