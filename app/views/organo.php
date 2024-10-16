<?php
include ("../../lib/jfunciones.php");
sesion();


$q_organo = "select gastos_t_b.fecha_creado,gastos_t_b.id_gasto_T_b,gastos_t_b.id_proceso,coberturas_t_b.id_organo,coberturas_t_b.id_cobertura_t_b from gastos_t_b,coberturas_t_b where gastos_t_b.id_cobertura_t_b=coberturas_t_b.id_cobertura_t_b and coberturas_t_b.id_organo>0 and gastos_T_b.id_organo=0 and gastos_t_b.fecha_creado>='2005-01-01'  order by gastos_t_b.fecha_creado";

$r_organo = ejecutar($q_organo);

		while($f=pg_fetch_assoc($r_organo)){
		

$mod_gastos="update gastos_t_b set id_organo=$f[id_organo] where gastos_t_b.id_cobertura_t_b=$f[id_cobertura_t_b] and gastos_t_b.fecha_creado>='2005-01-01' and gastos_t_b.id_organo=0";
$fmod_gastos=ejecutar($mod_gastos);


	
		}
?>
