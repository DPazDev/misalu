<?php
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$versipuedehacerpedido=("select tbl_admin_dependencias.id_admin,tbl_admin_dependencias.activar from tbl_admin_dependencias where id_admin=$elid");
$repuestasipuedehacer=ejecutar($versipuedehacerpedido);
$cuantosipuede=num_filas($repuestasipuedehacer);
$datasipuede=assoc_a($repuestasipuedehacer);
$usuactivar=$datasipuede['activar'];
$misdependecias=("select tbl_dependencias.dependencia,tbl_dependencias.id_dependencia from tbl_dependencias,tbl_admin_dependencias where
tbl_admin_dependencias.id_admin=$elid and 
tbl_admin_dependencias.id_dependencia=tbl_dependencias.id_dependencia and
(tbl_admin_dependencias.activar=1 or tbl_admin_dependencias.activar=3)");
$repuesmisdepend=ejecutar($misdependecias);
if(($usuactivar==4)){
	   echo"
               <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
                   <br>
                   <tr>
                      <td colspan=4 class=\"titulo_seccion\">Usuario desactivado para realizar esta operaci&oacute;n</td>
                   </tr>
              </table> 
              "; 
	}else{
             if(($cuantosipuede<=0) || ($usuactivar==2) ){
	           echo"
               <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
                   <br>
                   <tr>
                    <td colspan=4 class=\"titulo_seccion\">La opci&oacute;n crear pedido no esta disponible para este usuario</td>
                   </tr>
              </table> 
              "; 
	         }else{

$fecha=date("Y-m-d");
$tipoinsumo=("select tbl_tipos_insumos.id_tipo_insumo,tbl_tipos_insumos.tipo_insumo from tbl_tipos_insumos order by tbl_tipos_insumos.tipo_insumo;");
$reptipoinsumo=ejecutar($tipoinsumo);
$cuantosinsumo=num_filas($reptipoinsumo);
$espar=$cuantosinsumo%2;
  if ($espar==0){
	 $enfila=$cuantosinsumo/2;
  }else{	 
   $enfila=ceil($cuantosinsumo/2);
  }   

?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
             <tr>
                 <td colspan=4 class="titulo_seccion">Articulos</td>
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
<tr><td class=\"tdcampos\"  colspan=\"1\"><input type=\"checkbox\"  id=\"cajamd$lineas\" value=\"$insumos[id_tipo_insumo]\">$insumos[tipo_insumo]</td>";
	echo"</tr>";		
	    $lineas++;
		}?>
       </tr>
	 <br>     
	   <tr>
             <input type="hidden" id="tocajasmodf" value="<?echo $lineas-1;?>">
	     <td  title="Buscar los art&iacute;culos seleccionados"><label class="boton" style="cursor:pointer" onclick="BusartiDepenModif(); return false;" >Buscar</label></td>   
	  </tr>   
</table>
<? }
   }
?>
<hr>