<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$q_especialidad=("select * from especialidades_medicas order by  especialidades_medicas.especialidad_medica");
$r_especialidad=ejecutar($q_especialidad);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="cita">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Registrar Citas del Cliente</td>	</tr>	
	<tr>
		<td class="tdtitulos">* C&eacute;dula</td>
		<td class="tdcampos"><input class="campos" type="text" name="cedula" maxlength=128 size=20 value=""   onkeypress="return event.keyCode!=13"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""></td>
		<td colspan=1 class="tdtitulos">Especialidad</td>
<td colspan=1 class="tdcampos">

                <select style="width: 100px;" id="especialidad" name="especialidad" class="campos">
                              <option value="0" >Todas </option>
                <?php
                while($f_especialidad=asignar_a($r_especialidad,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_especialidad[id_especialidad_medica]?>"> <?php echo "$f_especialidad[especialidad_medica]" ?>
                </option>
                <?php
                }
                ?>
                </select>
                
		
		</td>
		</tr>
		<tr>
<td  class="tdtitulos">* Seleccione  el Tipo de Cliente.</td>
<td  class="tdcampos">
		<select name="tipo_cliente" class="campos" >
		
		<option value="0"> Afiliado</option>
		<option value="1"> Particular</option>
		
		</select>
	
		</td>
		
<td  class="tdtitulos"></td>
<td  class="tdcampos">
		
		<a href="#" OnClick="buscarcedulac();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
		</tr>
</table>
<div id="buscarcedulac"></div>

</form>
