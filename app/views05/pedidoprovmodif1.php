<?php
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$idpedido=$_POST['elidpedido'];
$depenid=$_POST['ladepes'];
$fecha=date("Y-m-d");
$tipoinsumo=("select tbl_tipos_insumos.id_tipo_insumo,tbl_tipos_insumos.tipo_insumo from tbl_tipos_insumos order by tbl_tipos_insumos.tipo_insumo;");
$reptipoinsumo=ejecutar($tipoinsumo);
$cuantosinsumo=num_filas($reptipoinsumo);
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

             <tr>
                 <td colspan=4 class="titulo_seccion">Pedido dependencia</td>
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
	echo"</tr>";		
	    $lineas++;
		}?>
       
	 <br>     
	   <tr>
             <input type="hidden" id="tocajas" value="<?echo $lineas;?>">
			<input type="hidden" id="elpedidoid" value="<?echo $idpedido;?>"> 
			 <input type="hidden" id="eliddepen" value="<?echo $depenid;?>">
	     <td  title="Buscar los art&iacute;culos seleccionados"><label class="boton" style="cursor:pointer" onclick="BusartiDepen1(); return false;" >Buscar</label></td>   
	  </tr>   
</table>