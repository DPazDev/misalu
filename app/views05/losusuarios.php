<?
include ("../../lib/jfunciones.php");
sesion();
$busdepen=("select id_dependencia,dependencia from tbl_dependencias order by dependencia;");
$repbusdepen=ejecutar($busdepen);
$admines=("select nombres,apellidos,id_admin from admin order by nombres;");
$repadmines=ejecutar($admines);
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>	
	       <tr>
			<td class="tdtitulos">
			<div id="blinddown_demo" style="display:none; width:250px;">
              <? echo "<select id=\"nomdep\" class=\"campos\" onChange=\"BusUdepn()\" style=\"width: 230px;\" >
                              <option value=\"0\"></option>";
			                   while($dependecias=asignar_a($repbusdepen,NULL,PGSQL_ASSOC)){
			                     echo" <option value=\" $dependecias[id_dependencia]@$dependecias[dependencia]\"> $dependecias[dependencia]</option>";
			             }
		           echo"</select>";?>
			</div>      
			</td>
			<td  title="Buscar usuarios registrados en las dependencias"><label class="boton" style="cursor:pointer" onclick="$('blinddown_demo1').hide(),limp2(),Effect.BlindDown('blinddown_demo'); return false;" >Buscar por dependencia</label>
			</td>  
	       </tr>     
		   <tr>
			<td class="tdtitulos">
			<div id="blinddown_demo1" style="display:none; width:250px;">
              <? echo "<select id=\"nomusuarios\" class=\"campos\" onChange=\"BusUdepn()\" style=\"width: 230px;\" >
                              <option value=\"0\"></option>";
			                   while($usudecias=asignar_a($repadmines,NULL,PGSQL_ASSOC)){
			                     echo" <option value=\"$usudecias[id_admin]@$usudecias[nombres] $usudecias[apellidos]\">$usudecias[nombres] $usudecias[apellidos]</option>";
			             }
		           echo"</select>";?>
			</div>      
			</td>
			<td  title="Buscar usuarios registrados en las dependencias"><label class="boton" style="cursor:pointer" onclick="$('blinddown_demo').hide(),limp1(),Effect.BlindDown('blinddown_demo1'); return false;" >Buscar por usuarios</label>
			</td>  
	       </tr>      
</table>		   
<div id="losusudepn"></div>