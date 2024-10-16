<?php
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$admines=("select admin.id_admin,admin.nombres,admin.apellidos from admin order by admin.nombres");
$repadmin=ejecutar($admines);
$misdependecias=("select  tbl_dependencias.id_dependencia,tbl_dependencias.dependencia from tbl_dependencias order by dependencia");
$repuesmisdepend=ejecutar($misdependecias);
$fecha=date("Y-m-d");
$tipoinsumo=("select tbl_tipos_insumos.id_tipo_insumo,tbl_tipos_insumos.tipo_insumo from tbl_tipos_insumos order by tbl_tipos_insumos.tipo_insumo;");
$reptipoinsumo=ejecutar($tipoinsumo);
$cuantosinsumo=num_filas($reptipoinsumo);
$espar=$cuantosinsumo%2;
  if ($espar==0){
	 $enfila=$cuantosinsumo/2;
  }else{	 
   $enfila=ceil($cuantosinsumo/2);
   $otrafila=$enfila+1;  
  }   
$provecompra=("select  tbl_dependencias.id_dependencia,tbl_dependencias.dependencia from tbl_dependencias order by dependencia;");
$repprodependencia=ejecutar($provecompra);
$cc=num_filas($repprodependencia);
$_SESSION['pasopedido']=0;
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
 <br>
             <tr>
                 <td colspan=4 class="titulo_seccion">Orden de Bienes</td>
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
        if ($lineas==1){
				echo"<td class=\"tdtitulos\" colspan=\"1\">Dependencia Saliente:</td>";?>
			<td class="tdcampos"  colspan="1">
			   <select id="misdependen" class="campos"  style="width: 230px;" >
			        <option value=""></option>
           <?php  while($lasdepend=asignar_a($repuesmisdepend,NULL,PGSQL_ASSOC)){?>
						<option value="<?php echo $lasdepend[id_dependencia]?>"> <?php echo "$lasdepend[dependencia]"?></option>
			      <?php
			             }
						} 
		              ?>
		              </select></td> 
			<?   

        if ($lineas==$enfila){
			echo"<td class=\"tdtitulos\" colspan=\"1\">Dependencia Entrante:</td>";?>
			<td class="tdcampos"  colspan="1">
			   <select id="proveedcom" class="campos"  style="width: 230px;" >
			        <option value=""></option>
           <?php  while($haydepend=asignar_a($repprodependencia,NULL,PGSQL_ASSOC)){?>
						<option value="<?php echo $haydepend[id_dependencia]?>"> <?php echo "$haydepend[dependencia]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 
			      </td>
			<?}
			if($otrafila==$lineas){
			echo"<td class=\"tdtitulos\" colspan=\"1\">Custodio:</td>";?>
				     <select id="eladmin" class="campos"  style="width: 230px;" >
			        <option value=""></option>
           <?php  while($losadm=asignar_a($repadmin,NULL,PGSQL_ASSOC)){?>
						<option value="<?php echo $losadm[id_admin]?>"> <?php echo "$losadm[nombres] $losadm[apellidos]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 
			      </td>
			<?	}
			
	echo"</tr>";		
	    $lineas++;
		}?>
       </tr>
	 <br>     
	   <tr>
             <input type="hidden" id="tocajas" value="<?echo $lineas;?>">
	     <td  title="Buscar los art&iacute;culos seleccionados"><label class="boton" style="cursor:pointer" onclick="BusartiOBienes(); return false;" >Buscar</label></td>   
	  </tr>   
</table>
<? 
   
?>
