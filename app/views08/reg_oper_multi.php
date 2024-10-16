<?php
include ("../../lib/jfunciones.php");
sesion();

$q_oper_multi="select * from tbl_facturas  order by id_factura";
$r_oper_multi=ejecutar($q_oper_multi);

while($f_oper_multi=asignar_a($r_oper_multi,NULL,PGSQL_ASSOC)){
	$monto=0;
$q_proc_clave="select * from tbl_procesos_claves where tbl_procesos_claves.id_factura=$f_oper_multi[id_factura]";
$r_proc_clave=ejecutar($q_proc_clave);

while($f_proc_clave=asignar_a($r_proc_clave,NULL,PGSQL_ASSOC)){
	$monto= $monto + $f_proc_clave[monto];
	}
echo $f_oper_multi[id_factura];
echo "****";
echo $monto;
echo "-----";
$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				      id_banco,
					      id_nom_tarjeta,
                          numero_cheque,
					      monto,
					      condicion_pago) 
					values('$f_oper_multi[id_factura]',
						  '$f_oper_multi[id_banco]',
					      '$f_oper_multi[id_nom_tarjeta]',
						  '$f_oper_multi[numero_cheque]',
					      '$monto',
						  '$f_oper_multi[condicion_pago]'
					      	);";
	$f_facturaom=ejecutar($r_facturaom);
}
?>
