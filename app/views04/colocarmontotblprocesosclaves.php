<?php
include ("../../lib/jfunciones.php");
sesion();

	$q_proceso=("select * from tbl_procesos_claves ");
	$r_procesos = ejecutar($q_proceso);
//Busco los procesos que estan afiliados a la clave.
	while($f_proceso = asignar_a($r_procesos)){
				$monto=0;
	$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso=$f_proceso[id_proceso]");
		$r_gastos=ejecutar($q_gastos);
		while($f_gastos = asignar_a($r_gastos)){
				$monto=$monto + $f_gastos[monto_aceptado];
				}
		
		$mod_factura="update tbl_procesos_claves set  
							monto='$monto'
where tbl_procesos_claves.id_proceso='$f_proceso[id_proceso]'				      
";
	$fmod_factura=ejecutar($mod_factura);
		}
		
		?>