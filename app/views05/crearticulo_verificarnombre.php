<?php
include ("../../lib/jfunciones.php");
sesion();

if(!empty($_GET['q']) || $_GET['q']!='') {
$vari=$_GET['q'];
$idInvocacion=$_GET['idcampoinvocaion'];
$consultarinsumo=true;
}else{$consultarinsumo=false;}
if($vari!=''){
		$sqlserial="select id_insumo,insumo from tbl_insumos where upper(insumo) like upper('$vari%') limit 10;";
		$serialinsumos=ejecutar($sqlserial);
		$encontradosinsumos=num_filas($serialinsumos);
		if($encontradosinsumos>=1) {
				echo"<ul id='listaSugerencias' class='allsugerencias'>";
				while($insu=asignar_a($serialinsumos,NULL,PGSQL_ASSOC)){
						$n++; 
 						?>
 						<li id="sugerencia<?php echo $n;?>"   onclick="elementoselecionado(this,'<?php echo $idInvocacion;?>');" ><?php echo $insu['insumo']; ?></li>
						<?php
				}
			echo "</ul>";

		}
}
 ?>
