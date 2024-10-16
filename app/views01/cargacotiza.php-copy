<?
include ("../../lib/jfunciones.php");
sesion();
list($lacotizid,$laspoliza)=explode("-",$_REQUEST['lacotiza']);
$genero=$_REQUEST['elgenero'];
$laci=$_REQUEST['cedula'];
list($cotizaid,$pla)=explode("-",$_REQUEST['idcotizacion']);
$apun=0;
if($genero==0){
            $tipogene=17;
            $vercarfamil=("select tbl_cliente_cotizacion.no_cotizacion,tbl_cliente_cotizacion.id_cliente_cotizacion,
polizas.nombre_poliza,tbl_caract_cotizacion.id_prima,tbl_caract_cotizacion.id_poliza,
tbl_caract_cotizacion.cantidad,tbl_caract_cotizacion.sexo,primas.edad_inicio,primas.edad_fin
 from 
   tbl_cliente_cotizacion,polizas,tbl_caract_cotizacion,primas
 where
       tbl_cliente_cotizacion.id_cliente_cotizacion=tbl_caract_cotizacion.id_cliente_cotizacion and
       tbl_cliente_cotizacion.id_cliente_cotizacion=$lacotizid and
       tbl_caract_cotizacion.id_poliza=polizas.id_poliza and
       tbl_caract_cotizacion.id_prima=primas.id_prima and
       primas.id_parentesco<>17 and  primas.id_parentesco<>18 and 
       primas.id_parentesco<>9 order by nombre_poliza");

        }else{
            $tipogene=18;
             $vercarfamil=("select tbl_cliente_cotizacion.no_cotizacion,tbl_cliente_cotizacion.id_cliente_cotizacion,
polizas.nombre_poliza,tbl_caract_cotizacion.id_prima,tbl_caract_cotizacion.id_poliza,
tbl_caract_cotizacion.cantidad,tbl_caract_cotizacion.sexo,primas.edad_inicio,primas.edad_fin
 from 
   tbl_cliente_cotizacion,polizas,tbl_caract_cotizacion,primas
 where
       tbl_cliente_cotizacion.id_cliente_cotizacion=tbl_caract_cotizacion.id_cliente_cotizacion and
       tbl_cliente_cotizacion.id_cliente_cotizacion=$lacotizid and
       tbl_caract_cotizacion.id_poliza=polizas.id_poliza and
       tbl_caract_cotizacion.id_prima=primas.id_prima and
       primas.id_parentesco<>17 and  primas.id_parentesco<>18 and  primas.id_parentesco<>9 order by nombre_poliza");
            }
        
        $repcargfami=ejecutar($vercarfamil);    
        $cuanfamili=num_filas($repcargfami);
        if($cuanfamili>=1){
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Carga familiar</td>  
     </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
			     <th class="tdtitulos">Rango.</th>  
                 <th class="tdtitulos">CI.</th> 
                 <th class="tdtitulos">Nombre.</th>
                 <th class="tdtitulos">Apellido.</th>
                 <th class="tdtitulos">Fecha de nacimiento.</th>
                 <th class="tdtitulos">Parentesco.</th>
                 <th class="tdtitulos">Genero.</th>
             </tr>
             <?$apun=1;
                 while($lafamilia=asignar_a($repcargfami,NULL,PGSQL_ASSOC)){
                   $lacantida=$lafamilia['cantidad'];
                   $laedadfin=$lafamilia['edad_fin'];
                   //estudiemos el caso para que no salga los abuelos,esposas!!
                   if($laedadfin<=18){
                       $queryparen="id_parentesco<>5 and id_parentesco<>6 and";
                    }
                   for($i=1;$i<=$lacantida;$i++){
                       $posiblemate=substr($lafamilia[nombre_poliza],5,9);
                       if($posiblemate!="MATERNIDA"){
            ?>
                <tr>
                   <td class="tdcampos" ><?echo "$lafamilia[edad_inicio]-$lafamilia[edad_fin]"?></td>
                   <td class="tdcampos" ><input type="text" id="<? echo "cedul$apun"?>" class="campos" size="9" onblur="buscaparenhi(<? echo "cedul$apun"?>,<? echo "$apun"?>)"></td>
                   <td class="tdcampos" ><input type="text" id="<? echo "nomh$apun"?>" class="campos" size="17"></td>
                   <td class="tdcampos" ><input type="text" id="<? echo "apell$apun"?>" class="campos" size="17"></td>
                   <td class="tdcampos" colspan="1"><input  type="text" size="7" id="<? echo "fecha$apun"?>" class="campos" maxlength="8" size="8">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, '<? echo "fecha$apun"?>', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
                  <td class="tdcampos"  colspan="1"><select id="<? echo "parent$apun"?>" class="campos" style="width: 130px;">
                              <option value=""></option>   
                    <?$qpare=$lafamilia[sexo];
                         $buscelparen=("select parentesco.id_parentesco,parentesco.parentesco 
                                                   from parentesco where
                                                   id_parentesco<>17 and id_parentesco<>18 and $queryparen genero=$qpare");
                         $repbusparen=ejecutar($buscelparen);                          
                         while($parentsco=asignar_a($repbusparen,NULL,PGSQL_ASSOC)){?>
                             <option value="<?php echo $parentsco[id_parentesco]?>"> <?php echo "$parentsco[parentesco]"?></option>
                         
                         <?} ?> 
                         </select>  </td>
                    <td class="tdcampos" ><?if($lafamilia[sexo]==0)
                                                                 echo "F";
                                                                else
                                                                  echo "M";?></td>
                    <td class="tdcampos" ><div id='<? echo "cargafamiliar$apun" ?>'></div></td>
                 </tr>  
                 <input type="hidden" id="<? echo "gener$apun"?>"  " value="<?echo $lafamilia[sexo]?>">     
            <?  $apun++;
            }else{?>
                         <td class="tdcampos"></td>
                         <td class="tdcampos">Agregar el plan (<?echo $lafamilia[nombre_poliza];?>)</td>
                         <td class="tdcampos" colspan="3">
                         <input type="radio" name="group1" id="matnoben" value="0" > No
                         <input type="radio" name="group1" id="matsiben" value="<?echo "1-$lafamilia[id_poliza]"?>" checked>Si
                          </td>
                   <?}
                   
                }?>
                </tr>
                
            <?}?>
            </table>
<?}?>
<br>
<input type="hidden" id="cuantohijo"   value="<?echo "$apun"?>"> 
<input type="hidden" id="matnoben"     value="0">
<input type="hidden" id="matsiben"     value="0">
<input type="hidden" id="cotifin"      value="<?echo $cotizaid?>"> 
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
 <td class="tdtitulos">Fecha inicio contrato:</td>
  <td class="tdcampos" colspan="1"><input  type="text" size="10" id="fechainicontrato" class="campos" maxlength="8" size="8">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechainicontrato', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
  <td class="tdtitulos">Fecha fin contrato:</td>                   
  <td class="tdcampos" colspan="1"><input  type="text" size="10" id="fechfincontrato" class="campos" maxlength="8" size="8">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechfincontrato', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
 </tr>                    
 <tr>                    
 <td title="Guardar contrato"><label class="boton" style="cursor:pointer" onclick="guardcontrato()" >Guardar</label></td>
</tr>
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='finalcontrato'></div>
