<?
include ("../../lib/jfunciones.php");
sesion();
$id_poliza=$_REQUEST['elpoliza'];
$cualpoliza=("select polizas.nombre_poliza from polizas where id_poliza=$id_poliza");
$repcualpoliza=ejecutar($cualpoliza);
$datopoliza=assoc_a($repcualpoliza);
$lapolizaes=$datopoliza['nombre_poliza'];
$versihprima=("select primas.id_prima,primas.descripcion,primas.anual,primas.semestral,primas.trimestral,
                                     primas.mensual,primas.edad_inicio,primas.edad_fin,parentesco.parentesco,primas.id_parentesco 
                                   from primas,parentesco 
                                   where primas.id_poliza=$id_poliza and primas.id_parentesco=parentesco.id_parentesco order by id_parentesco;");
 $repverprima=ejecutar($versihprima);                   
 $losparen=("select parentesco.id_parentesco,parentesco.parentesco from parentesco order by parentesco");
 $replospare=ejecutar($losparen);
 
//////////MONEDA EXPRESIONES////
$SqlMoneda=("select tbl_monedas.id_moneda, tbl_monedas.moneda , tbl_monedas.simbolo  from polizas,tbl_monedas where polizas.id_moneda=tbl_monedas.id_moneda and id_poliza='$id_poliza';");
$MonedaEJ=ejecutar($SqlMoneda);
$Moneda=asignar_a($MonedaEJ,NULL,PGSQL_ASSOC);
$moneda=$Moneda['simbolo'].' ('.$Moneda['moneda'].')'; 
 
 
?>
<input type='hidden' id='lapolizaes' value='<?echo $id_poliza?>'>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Primas asignadas a la poliza <?php echo $lapolizaes?> Montos expresados en <?php echo $moneda; ?></td>  
	</tr>
  </table>
  <table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
                 <th class="tdtitulos">Ln.</th>
                 <th class="tdtitulos">Parentesco.</th>  
			     <th class="tdtitulos">Descripci&oacute;n.</th>  
                 <th class="tdtitulos">Anual.</th>
                 <th class="tdtitulos">Semestral.</th>
                 <th class="tdtitulos">Trimestral.</th>
                 <th class="tdtitulos">Mensual.</th>
                 <th class="tdtitulos">Edad Ini.</th>
                 <th class="tdtitulos">Edad Fin.</th>
                 <th class="tdtitulos">Opc.</th>
			  </tr>
			<? $lin=1;
			   while($sonprimas=asignar_a( $repverprima,NULL,PGSQL_ASSOC)){
                   $ed1="feini$lin";
                   $ed2="fefin$lin";
                   $par="elparen$lin";
                   $ma="moa$lin";
                   $ms="mos$lin";
                   $mt="mot$lin";;
                   $mm="mom$lin";
                   $cm="com$lin";
                   $lap="prim$lin";
                   ?>
                       <tr>
                          <td class="tdcampos"><?echo $lin?></td>
                          <td class="tdcampos">
                              <select id="<?echo $par?>" class="campos" onChange="buslapoliza()" style="width: 160px;">
                                <option value="<?echo $sonprimas['id_parentesco']?>"><?echo $sonprimas['parentesco']?></option>
			                      <?php  while($lasparen=asignar_a($replospare,NULL,PGSQL_ASSOC)){?>
                                      <option value="<?php echo $lasparen[id_parentesco]?>"> <?php echo "$lasparen[parentesco]"?></option>
			                      <?php
			                      }
			                      ?>
			                 </select> 
                          </td>  
                          <input type="hidden" id="<?echo $lap?>" value="<?echo $sonprimas['id_prima']?>">
                          <td class="tdcampos"><TEXTAREA COLS=15 ROWS=2 id="<?echo $cm?>" class="campos"><?echo $sonprimas['descripcion']?></TEXTAREA></td>  
                          <td class="tdcampos"><input type="text" id="<?echo $ma?>" value="<?echo $sonprimas['anual']?>" size=4 class="campos"></td> 
                          <td class="tdcampos"><input type="text" id="<?echo $ms?>" value="<?echo $sonprimas['semestral']?>" size=4 class="campos"></td>  
                          <td class="tdcampos"><input type="text" id="<?echo $mt?>" value="<?echo $sonprimas['trimestral']?>" size=4 class="campos"></td> 
                          <td class="tdcampos"><input type="text" id="<?echo $mm?>" value="<?echo $sonprimas['mensual']?>" size=4 class="campos"></td>  
                          <td class="tdcampos"><input type="text" id="<?echo $ed1?>" value="<?echo $sonprimas['edad_inicio']?>" size=2 class="campos"></td> 
                          <td class="tdcampos"><input type="text" id="<?echo $ed2?>" value="<?echo $sonprimas['edad_fin']?>" size=2 class="campos"></td> 
                          <td class="tdcampos"><label class="boton" style="cursor:pointer" onclick="guardnprimas(document.getElementById('<?echo $ed1?>').value,
                          document.getElementById('<?echo $ed2?>').value,document.getElementById('<?echo $par?>').value
                          ,document.getElementById('<?echo $ma?>').value,document.getElementById('<?echo $ms?>').value
                          ,document.getElementById('<?echo $mt?>').value,document.getElementById('<?echo $mm?>').value
                          ,document.getElementById('<?echo $cm?>').value,document.getElementById('<?echo $lap?>').value)" >Guardar</label></td> 
                          <td class="tdcampos"><label class="boton" style="cursor:pointer" onclick="eliminaprimas('<? echo $sonprimas['id_prima']?>')" >Eliminar</label></td> 
                        </tr>
              <? $lin++; 
                }   
            ?>			
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
<div id='lasprif'></div>
