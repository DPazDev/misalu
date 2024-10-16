<?php
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$versipuedehacerpedido=("select tbl_admin_dependencias.id_admin,tbl_admin_dependencias.activar from tbl_admin_dependencias where id_admin=$elid");
$repuestasipuedehacer=ejecutar($versipuedehacerpedido);
$cuantosipuede=num_filas($repuestasipuedehacer);
$datasipuede=assoc_a($repuestasipuedehacer);
$usuactivar=$datasipuede['activar'];
$_SESSION['pasopedido']=0;
$_SESSION['pedidodepen']=0;
$misdependecias=("select tbl_dependencias.dependencia,tbl_dependencias.id_dependencia from tbl_dependencias where
tbl_dependencias.esalmacen=1 order by tbl_dependencias.dependencia ;");
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
                    <td colspan=4 class=\"titulo_seccion\">La opci&oacute;n crear donativo no esta disponible para este usuario</td>
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
$autorizados=("select * from tbl_responsables_donativos order by responsable;");
$repautoriza=ejecutar($autorizados);
$_SESSION['pasopedido']=0;
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
             <tr>
                 <td colspan=4 class="titulo_seccion">Pedido donativos</td>
             </tr>
 </table>
<table class="tabla_cabecera5"   cellpadding=0 cellspacing=0>
     
    <tr>
       <td class="tdtitulos" colspan="1">Autorizado por:</td>
	   <td class="tdcampos"  colspan="1">
	     <select id="autorizados" class="campos"  style="width: 230px;" >
			        <option value=""></option>
              <?php  
			         while($losautoriz=asignar_a($repautoriza,NULL,PGSQL_ASSOC)){
				?>
					<option value="<?php echo $losautoriz[id_responsable_donativo]?>"> 
													<?php echo "$losautoriz[responsable]"?>
						</option>
			      <?}?>
		</select>  
	</td> 
    </tr>
	<tr>
	   <td class="tdtitulos" colspan="1">Dependencia:</td>
	   <td class="tdcampos"  colspan="1">
	     <select id="misdependendona" class="campos"  style="width: 230px;" >
			        <option value=""></option>
           <?php  while($lasdependona=asignar_a($repuesmisdepend,NULL,PGSQL_ASSOC)){?>
						<option value="<?php echo $lasdependona[id_dependencia]?>"> <?php echo "$lasdependona[dependencia]"?></option>
			      <?php
			             }
						 
		              ?>
		              </select></td> 
	</td>    
	</tr>
	<tr>
            <td class="tdtitulos">C&eacute;dula del cliente:</td>
	    <td class="tdtitulos" colspan="1"><input type="text" id="ceducliendona" class="campos" ></td>
            <td  title="Buscar cliente"><label class="boton" style="cursor:pointer" onclick="DonaCliente(); return false;" >Buscar</label></td>   
	</tr>
         <tr>
	   <td class="tdtitulos">Comentario:</td>  
	   <td class="tdtitulos"><TEXTAREA COLS=65 ROWS=3 id="comentdona" class="campos"></TEXTAREA></td>         
          </tr>
	</table>
        <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />   
	<div id='datoscliendon' style='display: none'></div>		   
	<table class="tabla_citas"   cellpadding=0 cellspacing=0>
   
    <tr>
         <td class="tdtitulos" colspan="1">Tipo de insumo(s):</td>
		<?
		 $lineas=1;
		 while($insumos=asignar_a($reptipoinsumo,NULL,PGSQL_ASSOC)){ 
		echo"
<tr><td class=\"tdcampos\"  colspan=\"1\"><input type=\"checkbox\"  id=\"caja$lineas\" value=\"$insumos[id_tipo_insumo]\">$insumos[tipo_insumo]</td>";
        $lineas++;
        }?>
       </tr>
	 <br>     
	   <tr>
             <input type="hidden" id="tocajas" value="<?echo $lineas;?>">
	     <td  title="Buscar los art&iacute;culos seleccionados"><label class="boton" style="cursor:pointer" onclick="BusartiDonat(); return false;" >Buscar</label></td>   
            <td  title="Imprimir solicitud de donativo"><label class="boton" style="cursor:pointer" onclick="ImprimirDonat(); return false;" >Imprimir Solicitud</label></td>   
	  </tr>   
</table>
<? }
   }
?>
