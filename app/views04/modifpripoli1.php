 <?php
include ("../../lib/jfunciones.php");
$laiddpoliza=$_REQUEST['polizaid'];
$verpoliza=("select polizas.nombre_poliza from polizas where polizas.id_poliza=$laiddpoliza;");
$repverpoli=ejecutar($verpoliza);
$datopol=assoc_a($repverpoli);
$nompoliza=$datopol['nombre_poliza'];
$_SESSION['matriz1']=array();
$_SESSION['pasopedido1']=1;
$buscprimas=("select polizas.nombre_poliza,primas.descripcion,primas.anual,
                          primas.semestral,primas.trimestral,primas.mensual,
                          primas.edad_inicio,primas.edad_fin,parentesco.parentesco
                      from
                           polizas,primas,parentesco
                      where
                         primas.id_poliza=polizas.id_poliza and
                         polizas.id_poliza=$laiddpoliza and 
                         primas.id_parentesco=parentesco.id_parentesco
                      order by 
                        parentesco.parentesco;");
 $repprimas=ejecutar($buscprimas);                       
sesion();
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Primas asignadas a la poliza <?echo $nompoliza?></td>  
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
			  </tr>
			<?  
			   $lin=1;
			   while($sonprimas=asignar_a($repprimas,NULL,PGSQL_ASSOC)){?>
                       <tr>
                          <td class="tdcampos"><?echo $lin?></td>
                          <td class="tdcampos"><?echo $sonprimas['parentesco']?></td>  
                          <td class="tdcampos"><?echo $sonprimas['descripcion']?></td>  
                          <td class="tdcampos"><?echo $sonprimas['anual']?></td> 
                          <td class="tdcampos"><?echo $sonprimas['semestral']?></td>  
                          <td class="tdcampos"><?echo $sonprimas['trimestral']?></td> 
                          <td class="tdcampos"><?echo $sonprimas['mensual']?></td>  
                          <td class="tdcampos"><?echo $sonprimas['edad_inicio']?></td> 
                          <td class="tdcampos"><?echo $sonprimas['edad_fin']?></td> 
                        </tr>
              <? $lin++; 
                }   
            ?>			  
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=1 class="titulo_seccion" title="Agregar una nueva prima a la p&oacute;liza"><label class="boton" style="cursor:pointer" onclick="nuevprima('<?echo $laiddpoliza?>')" >Agregar prima</label></td>  
         <td colspan=1 class="titulo_seccion" title="Modifica primas"><label class="boton" style="cursor:pointer" onclick="modif_las_primas('<?echo $laiddpoliza?>')" >Modifica prima</label></td>  
	</tr>	 
 </table>
 <img alt="spinner" id="spinnerPFin" src="../public/images/esperar.gif" style="display:none;" />   
