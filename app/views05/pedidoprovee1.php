<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$tipoinsumo=("select tbl_tipos_insumos.id_tipo_insumo,tbl_tipos_insumos.tipo_insumo from tbl_tipos_insumos order by tbl_tipos_insumos.tipo_insumo;");
$reptipoinsumo=ejecutar($tipoinsumo);
$cuantosinsumo=num_filas($reptipoinsumo);
$_SESSION['pedidodepen']=0;
$espar=$cuantosinsumo%2;
  if ($espar==0){
	 $enfila=$cuantosinsumo/2;
  }else{	 
   $enfila=ceil($cuantosinsumo/2);
  }   
$provecompra=("select  clinicas_proveedores.nombre,clinicas_proveedores.direccion,
clinicas_proveedores.telefonos,clinicas_proveedores.rif, proveedores.id_proveedor 
from clinicas_proveedores,proveedores 
where
  clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
  clinicas_proveedores.prov_compra=1 order by clinicas_proveedores.nombre");
$repprovcompra=ejecutar($provecompra);
$cc=num_filas($repprovcompra);
$_SESSION['pasopedido']=0;
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
 <br>
             <tr>
                 <td colspan=4 class="titulo_seccion">Pedido proveedor</td>
             </tr>
 </table>
<table class="tabla_cabecera5"   cellpadding=0 cellspacing=0>
    <tr>
       <td colspan=2><br><td>
    </tr>
    <tr>
         <td class="tdtitulos" colspan="1">Tipo de insumo(s):</td>
		<?
		 $lineas=1;
		 while($insumos=asignar_a($reptipoinsumo,NULL,PGSQL_ASSOC)){ 
		echo"
<tr><td class=\"tdcampos\"  colspan=\"1\"><input type=\"checkbox\"  id=\"caja$lineas\" value=\"$insumos[id_tipo_insumo]\">$insumos[tipo_insumo]</td>";
        if ($lineas==$enfila){
			echo"<td class=\"tdtitulos\" colspan=\"1\">Proveedor:</td>";?>
			<td class="tdcampos"  colspan="1">
			   <select id="proveedcom" class="campos"  style="width: 230px;" >
			        <option value=""></option>
           <?php  while($haydepend=asignar_a($repprovcompra,NULL,PGSQL_ASSOC)){?>
						<option value="<?php echo $haydepend[id_proveedor]?>"> <?php echo "$haydepend[nombre]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 
			      </td>
			<?}
	echo"</tr>";		
	    $lineas++;
		}?>
       </tr>
	 <br>     
	   <tr>
             <input type="hidden" id="tocajas" value="<?echo $lineas;?>">
	     <td  title="Buscar los art&iacute;culos seleccionados"><label class="boton" style="cursor:pointer" onclick="Busarti(); return false;" >Buscar</label></td>   
	  </tr>   
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 