<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

$q_tipo_para=("select 
								* 
						from 
								tbl_tipos_paragrafo 
						order by 
								tbl_tipos_paragrafo.tipo_paragrafo ");
$r_tipo_para=ejecutar($q_tipo_para);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="oa" id="oa">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Registrar o Modificar Paragrafos	 </td>	</tr>	
	
<tr>
<td  class="tdtitulos">* Seleccione  el Tipo de Paragrafo.</td>
<td  class="tdcampos">
		<select id="id_tparagrafo" name="id_tparagrafo" class="campos" style="width: 200px;" OnChange="bus_paragrafos();">
		<option value="0" > 
	
	Seleccione el Tipo
	</option>
		<?php
		while($f_tipo_para = asignar_a($r_tipo_para)){
			?>
			
	<option value="<?php echo $f_tipo_para['id_tipo_paragrafo'];?>" > 
	
	<?php echo $f_tipo_para['tipo_paragrafo'];?> 
	</option>
			
		
<?php
}
?>
		</select>
		</td>
		<td  colspan=2 class="tdtitulos">	</td>
		</tr>

</table>
	<div id="tipo_paragrafo"></div>

</form>
