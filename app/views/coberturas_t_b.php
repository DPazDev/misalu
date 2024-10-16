<?php
include ("../../lib/jfunciones.php");
sesion();


$q_organo = "select coberturas_t_b.id_cobertura_t_b,count(gastos_t_b.id_cobertura_t_b) from gastos_t_b,coberturas_t_b where gastos_t_b.id_cobertura_t_b=coberturas_t_b.id_cobertura_t_b and coberturas_t_b.id_organo>0  and gastos_t_b.fecha_creado>='2010-01-01' group by coberturas_t_b.id_cobertura_t_b";

$r_organo = ejecutar($q_organo);

		while($f=pg_fetch_assoc($r_organo)){	
		
		$q_cober = "select * from coberturas_t_b where coberturas_t_b.id_cobertura_t_b=$f[id_cobertura_t_b]";
		$r_cober = ejecutar($q_cober);
		$f_cober=asignar_a($r_cober);
		
		$q_cober1 = "select * from coberturas_t_b where coberturas_t_b.id_organo=1 and coberturas_t_b.id_titular=$f_cober[id_titular] and coberturas_t_b.id_beneficiario=$f_cober[id_beneficiario]";
		$r_cober1 = ejecutar($q_cober1);
		$f_cober1=asignar_a($r_cober1);
		?>
	<table>
	<tr>
	<td>
	<?php
	echo 	$f_cober1[id_titular];
		echo 	"*" ;
		echo 	$f_cober1[id_beneficiario];
			echo 	"**" ;
		echo 	$f_cober1[id_cobertura_t_b];
		echo 	"****** ******" ;
		?>
		<td>
		</tr>
	</table>
	
	<?
/*$mod_gastos="update gastos_t_b set id_organo=$f[id_organo] where gastos_t_b.id_cobertura_t_b=$f[id_cobertura_t_b] and gastos_t_b.fecha_creado>='2005-01-01' and gastos_t_b.id_organo=0";
$fmod_gastos=ejecutar($mod_gastos);*/


	
		}
?>
