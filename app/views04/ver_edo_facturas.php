<?php
include ("../../lib/jfunciones.php");
sesion();
$q_serie=("select * from  tbl_series where tbl_series.id_sucursal<>11 order by  tbl_series.nomenclatura");
$r_serie=ejecutar($q_serie);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="cita">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Ver o Modificar Estados de una Factura de una Sucursal</td>	</tr>	
	<tr>
		<td class="tdtitulos">* Numero de Factura</td>
		<td class="tdcampos"><input class="campos" type="text" id="factura" name="factura" maxlength=128 size=20 value=""     onkeypress="return event.keyCode!=13"></td>
		<td class="tdtitulos">Seleccione la Serie</td>
		<td class="tdcampos">
          <select  style="width: 150px;" id="serie" name="serie" class="campos">
               <?php
                                 while($f_serie=asignar_a($r_serie,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_serie[id_serie]?>"> <?php echo "$f_serie[nomenclatura] ($f_serie[nombre])" ?>
                </option>
                <?php
                }
                ?>
                </select>
        <input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#" OnClick="buscarfacturaserie();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		 
		</tr>
</table>
<div id="buscarfacturaserie"></div>

</form>
