<?
/*  Nombre del Archivo: r_gastos_cliente1.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación de Gastos del Cliente
*/
	include ("../../lib/jfunciones.php");
	sesion();   

	$q_cliente=("select procesos.id_titular,procesos.id_beneficiario,gastos_t_b.id_gasto_t_b,gastos_t_b.id_organo,gastos_T_b.id_cobertura_t_b from procesos,gastos_t_b where procesos.id_proceso=gastos_t_b.id_proceso and gastos_t_b.fecha_creado>='2005-01-01' and gastos_t_b.id_cobertura_t_b=coberturas_t_b.id_cobertura_t_b and coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and propiedades_poliza.cualidad='PLAN BASICO'");
	$r_cliente=ejecutar($q_cliente);
	$num_filas=num_filas($r_cliente);
	
while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){
	
	$q_organo=("select * from coberturas_t_b where coberturas_t_b.id_titular=$f_cliente[id_titular] and coberturas_t_b.id_beneficiario=$f_cliente[id_beneficiario] and coberturas_t_b.id_organo=1");
	$r_organo=ejecutar($q_organo);
	$f_organo=asignar_a($r_organo);
	$mod_cobertura="update gastos_t_b set id_cobertura_t_b=$f_organo[id_cobertura_t_b] where
gastos_t_b.id_gasto_t_b=$f_cliente[id_gasto_t_b]";
$fmod_cobertura=ejecutar($mod_cobertura);
	$i++;
	}
	echo "paso $i";
?>

