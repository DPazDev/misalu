<?php

include ("../../lib/jfunciones.php");

sesion();

$id_poliza=$_REQUEST['id_poliza'];

?>



<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>





<tr>		<td colspan=6 class="titulo_seccion">Registrar Caracteristicas del Plan </td>	</tr>	

	

<tr>

<td  class="tdtitulos">* Caracterisitica</td>

<td  class="tdcampos">

<textarea class="campos" name="caract" id="caract" cols=80 rows=3></textarea>

</td>

</tr>

<tr>

<td  class="tdtitulos">* No. Orden</td>

<td   colspan=1 class="tdcampos">

<input class="campos" type='text' size='3' id="no_orden" name="no_orden">	

</td>	

</tr>

<tr>

<td  class="tdcampos">

<br>





                <a href="#" OnClick="reg_caract_poliza3();" class="boton">Guardar</a>

                </td>



</tr>



</table>
