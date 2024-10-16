<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$elidpoliza=$_POST['lapolizaid'];
$buscarpol=("select polizas.nombre_poliza from polizas where polizas.id_poliza=$elidpoliza;");
$repbuspoli=ejecutar($buscarpol);
$ladata=assoc_a($repbuspoli);
$nompoliza=$ladata['nombre_poliza'];
$lospartentesco=("select parentesco.id_parentesco,parentesco.parentesco from parentesco order by parentesco.parentesco;");
$replosparentes=ejecutar($lospartentesco);
$arregloedadini=array();
$arregloedadinival=array();
$arregloedadfin=array();
$arregloedadfinval=array();

//////////MONEDA EXPRESIONES////
echo$SqlMoneda=("select tbl_monedas.id_moneda, tbl_monedas.moneda , tbl_monedas.simbolo,polizas.id_poliza_rango  from polizas,tbl_monedas where polizas.id_moneda=tbl_monedas.id_moneda and id_poliza='$elidpoliza';");
$MonedaEJ=ejecutar($SqlMoneda);
$Moneda=asignar_a($MonedaEJ,NULL,PGSQL_ASSOC);
$moneda=$Moneda['simbolo'].' ('.$Moneda['moneda'].')';
$idRangosUsados=$Moneda['id_poliza_rango'];//rangos de edades de la poliza

//ver si ya tenemos primas en la poliza
$verprimas=("select primas.id_poliza,parentesco.parentesco,primas.descripcion,primas.anual,primas.semestral,primas.trimestral,
                                primas.mensual,primas.edad_inicio,primas.edad_fin
                              from primas,parentesco where primas.id_poliza=$elidpoliza and parentesco.id_parentesco=primas.id_parentesco order by parentesco;");
$reverprimas=ejecutar($verprimas);
$cuanveprima=num_filas($reverprimas);
for($i=0;$i<=120;$i++){
    $arregloedadini[$i]=$i;
	$arregloedadinival[$i]="$i a&ntilde;os";
	$arregloedadfin[$i]=$i;
    $arregloedadfinval[$i]="$i a&ntilde;os";
}
?>

<input type="hidden" id="elidpoliza" value="<?echo $elidpoliza?>">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=3 class="titulo_seccion">Cargar las primas para la p&oacute;liza <?echo $nompoliza ?></td>
	</tr>
 </table>

<table class="tabla_cabecera5 colortable"  cellpadding=0 cellspacing=0>
<tr>
         <td class="tdtitulos">Parentesco:</td>
         <td class="tdcampos" colspan="6">
			  <select id="elparentesco" class="campos"  style="width: 230px;" focus >
			        <option value=""></option>
              <?php
			         while($parentescos=asignar_a($replosparentes,NULL,PGSQL_ASSOC)){
				?>
					<option value="<?php echo $parentescos[id_parentesco]?>"> <?php echo "$parentescos[parentesco]"?>
					</option>
			      <?}?>
			 </select>
		  </td>
</tr>
<tr>
		 <td class="tdtitulos">Descripci&oacute;n:</td>
     <td class="tdtitulos">Edad inicio:</td>
     <td class="tdtitulos">Edad fin:</td>
     <td class="tdtitulos">Prima anual:</td>
     <td class="tdtitulos">Prima semestral:</td>
     <td class="tdtitulos">Prima trimestral:</td>
     <td class="tdtitulos">Prima mensual:</td>
</tr>
<?php
///cargar rangos de edades
$RangosEdadesSql=("select * from polizas_rangos_edades where polizas_rangos_edades.id_poliza_rango='$idRangosUsados';");
$RangoEdadPoliza=ejecutar($RangosEdadesSql);
$i=0;
while($RangoEdad=asignar_a($RangoEdadPoliza,NULL,PGSQL_ASSOC)){
  $i++;
  $EdadIni=$RangoEdad['edad_inicio'];//edad inicio
  $EdadFin=$RangoEdad['edad_fin'];
  ?>
  <tr>
      <td class="tdtitulos"><TEXTAREA  id="descriprima_<?php echo $i;?>" COLS=15 ROWS=2 class="campos" placeholder="<?php echo "Rangos de $EdadIni a $EdadFin años";?>"></TEXTAREA></td>
      <td class="tdcampos">
        <input type="text" id="edadinicio_<?php echo $i;?>" class="campos" size="4" value="<?php echo $EdadIni;?>">
    	</td>
      <td class="tdcampos">
    		 <input type="text" id="edadfin_<?php echo $i;?>" class="campos" size="4" value="<?php echo $EdadFin;?>">
    	</td>
      <td class="tdcampos">
        <input type="text" id="primaunual_<?php echo $i;?>" class="campos" size="4" value='0' onchange="an=this.value;$('primsemest_<?php echo $i;?>').value=(an/2);$('primtrimes_<?php echo $i;?>').value=(an/4);$('primmes_<?php echo $i;?>').value=(an/12);"><?php echo $moneda;?>
      </td>
      <td class="tdcampos">
        <input type="text" id="primsemest_<?php echo $i;?>" class="campos" size="4" value='0'><?php echo $moneda;?>
      </td>
      <td class="tdcampos">
        <input type="text" id="primtrimes_<?php echo $i;?>" class="campos" size="4" value='0'><?php echo $moneda;?>
      </td>
      <td class="tdcampos">
        <input type="text" id="primmes_<?php echo $i;?>"    class="campos" size="4" value='0'><?php echo $moneda;?>
      </td>
   </tr>
<?php
 }

?>
<input type="hidden" id="cuantosreg" value="<?php echo $i;?>">
<tr colspan=7>
  <td  title="Procesar las primas para la p&oacute;liza"><label class="boton" style="cursor:pointer" onclick="guardaprimas()" >Procesar</label></td>
</tr>
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
<div id="lasprimaspoliza">
<?

if($cuanveprima>=1){
$paso1=$_SESSION['pasopedido1'];
$matriz1=$_SESSION['matriz1'];
$apunta=1;
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=3 class="titulo_seccion">Primas cargadas</td>
	</tr>
  </table>
  <table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
			     <th class="tdtitulos">Lin.</th>
                 <th class="tdtitulos">Parentesco.</th>
                 <th class="tdtitulos">Descripci&oacute;n.</th>
                 <th class="tdtitulos">Edad Ini.</th>
                 <th class="tdtitulos">Edad Fin.</th>
                 <th class="tdtitulos">Anual.</th>
                 <th class="tdtitulos">Semestral.</th>
                 <th class="tdtitulos">Trimestral.</th>
                 <th class="tdtitulos">Mensual.</th>
			  </tr>
 <?
    $linea=1;
    while($lasprimas=asignar_a($reverprimas,NULL,PGSQL_ASSOC)){
        $matriz1[$apunta][0]=$lasprimas['parentesco'];
        $matriz1[$apunta][1]=$lasprimas['descripcion'];
        $matriz1[$apunta][2]=$lasprimas['edad_inicio'];
        $matriz1[$apunta][3]=$lasprimas['edad_fin'];
        $matriz1[$apunta][4]=$lasprimas['anual'];
        $matriz1[$apunta][5]=$lasprimas['semestral'];
        $matriz1[$apunta][6]=$lasprimas['trimestral'];
        $matriz1[$apunta][7]=$lasprimas['mensual'];?>
            <tr>
                    <td class="tdcampos"><?echo $linea?></td>
                    <td class="tdcampos"><?echo $lasprimas['parentesco'];?></td>
                    <td class="tdcampos"><?echo strtoupper($lasprimas['descripcion']);?></td>
                    <td class="tdcampos"><?echo $lasprimas['edad_inicio'];?></td>
                    <td class="tdcampos"><?echo $lasprimas['edad_fin'];?></td>
                    <td class="tdcampos"><?echo $lasprimas['anual'];?></td>
                    <td class="tdcampos"><?echo $lasprimas['semestral'];?></td>
                    <td class="tdcampos"><?echo $lasprimas['trimestral'];?></td>
                    <td class="tdcampos"><?echo $lasprimas['mensual'];?></td>
            </tr>
     <?
         $linea++;
         $apunta++;
       }
      $_SESSION['pasopedido1']=$linea;
      $_SESSION['matriz1']=$matriz1;
     echo"</table>";

}
?>
</div>
