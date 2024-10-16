<?php
include ("../../lib/jfunciones.php");
sesion();
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="factura">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Anexos</td>	</tr>	
<tr> 
		<td  class="tdtitulos"> Nombre</td>
		<td>
                </td>
				<td  class="tdtitulos"> Descripcion
                 </td>
	</tr>
	<tr> 
		<td  class="tdcampos"> <a href="../app/views08/ANEXOS completos.doc">Anexos Completos</a></td>
		<td>
                </td>
				<td  class="tdcampos"> Contiene Varios Formatos de Caja Chica
                 </td>
	</tr>
	<tr> 
		<td  class="tdcampos"> <a href="../app/views08/SOLICITUD DE VIATICOS.doc">Solicitud de Viaticos</a></td>
		<td>
                </td>
				<td  class="tdcampos"> Contiene el Formato para la Solicitud de Viaticos
                 </td>
	</tr>
	<tr> 
		<td  class="tdcampos"> <a href="../app/views08/anexos bienes.doc">Bienes</a></td>
		<td>
                </td>
				<td  class="tdcampos"> Contiene el Formato de Bienes
                 </td>
	</tr>
         <tr> 
		<td  class="tdcampos"> <a href="../app/views08/instructivoviatico.doc">Instructivo Viatico</a></td>
		<td>
                </td><td  class="tdcampos"> Contiene nuevo formato para solicitud y liquidaci&oacute;n de viaticos
                 </td>
	</tr>
         <tr> 
		<td  class="tdcampos"> <a href="../app/views08/instructivoefectivo.doc">Instructivo para el manejo de efectio</a></td>
		<td>
                </td><td  class="tdcampos"> Contiene nuevo formato para el manejo de efectivo
                 </td>
	</tr>	
        <tr>
                <td  class="tdcampos"> <a href="../app/views08/ModuloI.zip">Material de Seguros</a></td>
                <td>
                </td><td  class="tdcampos"> Contiene las laminas del curso de seguros
                 </td>
 <tr> 
                <td  class="tdcampos"> <a href="../app/views08/ANEXO MATERNIDAD CLINISALUD.docx">Anexo Maternidad</a></td>
                <td>
                </td>
                                <td  class="tdcampos"> Contiene  Anexo Maternidad
                 </td>
        </tr>
 <tr> 
                <td  class="tdcampos"> <a href="../app/views08/CONDICIONES PARTICUALRES HCM CLINISALUD.docx">Condiciones Particulares</a></td>
                <td>
                </td>
                                <td  class="tdcampos"> Contiene  Anexo Condiciones Particulares HCM
                 </td>
        </tr>
        <tr> 
                <td  class="tdcampos"> <a href="../app/views08/CONDICIONES GENERALES HCM CLINISALUD.docx">Condiciones Generales HCM</a></td>
                <td>
                </td>
                                <td  class="tdcampos"> Contiene  Anexo Condiciones Generales HCM
                 </td>
        </tr>


        </tr>

		<tr>
		<td class="tdtitulos"></td>
		
		</td>
		<td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		</tr>

</table>

</form>
