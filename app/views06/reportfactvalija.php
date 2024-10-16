<?php
include ("../../lib/jfunciones.php");
sesion();
$q_serie=("select * from  tbl_series where tbl_series.id_sucursal<>11 order by  tbl_series.nomenclatura");
$r_serie=ejecutar($q_serie);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Buscar Factura en Valija</td>
     </tr>
</table> 
<form action="POST" method="post" name="cita">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>
		<td class="tdtitulos">* Numero de Factura</td>
		<td class="tdcampos"><input class="campos" type="text" id="factura" name="factura" maxlength=128 size=20 value=""     onkeypress="return event.keyCode!=13"></td>
		<td class="tdtitulos">Seleccione la Serie</td>
		<td class="tdcampos">
          <select  style="width: 150px;" id="serie" name="serie" class="campos">
             <option value=""></option>
               <?php
                                 while($f_serie=asignar_a($r_serie,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_serie[id_serie]?>"> <?php echo "$f_serie[nomenclatura] ($f_serie[nombre])" ?>
                </option>
                <?php
                }
                ?>
                </select>
        <input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#" OnClick="buscarfacturavalija();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		 
		</tr>
</table>
<div align=center> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
</div>
<div id="buscarfacturavalija"></div>

</form>
