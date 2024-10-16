<?php
include ("../../lib/jfunciones.php");
sesion();
$usuario=("select admin.id_admin,admin.nombres,admin.apellidos from admin,tbl_cliente_cotizacion where
admin.id_admin=tbl_cliente_cotizacion.id_admin
group by 
admin.id_admin,admin.nombres,admin.apellidos
order by admin.nombres;");
$repusuario=ejecutar($usuario);
?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Reimprimir cotizaci&oacute;n</td>          
	</tr>
  </table>
  <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<br>
    <tr>
      <td class="tdtitulos">Por usuario:</td>
      <td class="tdtitulos">
                     <select id="idadmin" class="campos"  style="width: 230px;" >
			        <option value=""></option>
                    <?php  
			             while($losusu=asignar_a($repusuario,NULL,PGSQL_ASSOC)){
				      ?>
					    <option value="<?php echo $losusu[id_admin]?>"> <?php echo "$losusu[nombres] $losusu[apellidos]"?></option>
			      <?}?>
		        </select>  
    </tr>
    <tr>
       <td class="tdtitulos">Por n&uacute;mero de c&eacute;dula:</td>
       <td class="tdcampos" ><input type="text" id="cedulusu" class="campos" size="35">
       </td>
     </tr>
    <tr>
     <td title="Busquedad de cotizaciones"><label class="boton" style="cursor:pointer" onclick="BusquedadCoti(); return false;" >Buscar</label></td>      
   </tr>
</table>  
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
<div id='lascotizacion'></div>  