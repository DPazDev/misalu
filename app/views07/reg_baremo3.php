<?php
include ("../../lib/jfunciones.php");
sesion();
$baremo=$_REQUEST['baremo'];
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=6 class="titulo_seccion">Registrar Examenes </td>	</tr>	
	
<tr>
<td  class="tdtitulos">* Examen.</td>
<td  class="tdcampos">
<input class="campos" type="text" name="examen" 
					maxlength=128 size=20  value=""   >
</td>
	<td  class="tdtitulos">* Monto CliniSalud</td>
<td  class="tdcampos">
<input class="campos" type="text" name="monto" 
					maxlength=128 size=10  value="0"   >
		</td>
 <td  class="tdtitulos">* Monto Privado</td>
<td  class="tdcampos">
<input class="campos" type="text" name="monto2"
                                        maxlength=128 size=10  value="0"   >
                <a href="#" OnClick="reg_baremo4();" class="boton">Guardar</a>
                </td>

		</tr>

</table>
	


