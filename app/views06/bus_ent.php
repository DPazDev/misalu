<?php
include ("../../lib/jfunciones.php");
sesion();

list($tipo_ente,$nom_tipo_ente)=explode("@",$_POST['tipo_ente']);
$imprimir=$_POST['imprimir'];
if ($tipo_ente==0)
{
	$comparacion="entes.id_tipo_ente>0";
	}
	else
	{
	$comparacion="entes.id_tipo_ente=$tipo_ente";	
		}

$q_ente = "select * from entes where $comparacion order by nombre ";
$r_ente = ejecutar($q_ente);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="tdtitulos">Seleccione el Ente </td>
	
<td class="tdcampos" colspan="1" >
<select class="campos" id="ente" name="ente">
		<option value="">--Seleccione el Ente--</option>
		<option value="0@Todos los Entes">Todos los Entes</option>
		<?php
		while($f_ente = asignar_a($r_ente)){
		echo "<option value=\"$f_ente[id_ente]@$f_ente[nombre]\" >$f_ente[nombre]</option>";
			
		}
		?>
		</select>
		</td>
        <?php if ($imprimir==1){?>
		 <td class="tdcampos" title="Buscar Gastos del Ente"><label class="boton" style="cursor:pointer" onclick="bus_entes()" >Buscar</label> </td>
         <td class="tdcampos" title="Buscar Gastos del Ente y da el Formato de Impresion"><label class="boton" style="cursor:pointer" onclick="imp_bus_rep_gas_ent()" >Imprimir</label> </td>
<?php }?>
<?php if ($imprimir==2){?>
		 <td class="tdcampos" title="Buscar Gastos del Ente"><label class="boton" style="cursor:pointer" onclick="bus_entes_ser()" >Buscar</label> </td>
 <td class="tdcampos" title="Buscar Gastos de los Entes en Forma individual y da el Formato de Impresion"><label class="boton" style="cursor:pointer" onclick="imp_rep_gas_ent_ser()" >Imprimir</label> </td>
<?php }?>
<?php if ($imprimir==3){?>
		 <td class="tdcampos" title="Buscar El Total de Clientes por Ente"><label class="boton" style="cursor:pointer" onclick="bus_cli_tot_ent()" >Buscar</label> </td>
 <td class="tdcampos" title="Buscar El Total de Clientes por Ente y da el Formato de Impresion"><label class="boton" style="cursor:pointer" onclick="imp_rep_cli_tot_ent()" >Imprimir</label> </td>
<?php }?>
<?php if ($imprimir==5){?>
		 <td class="tdcampos" title="Auditar Coberturas"><label class="boton" style="cursor:pointer" onclick="auditar_cob1()" >Auditar</label> </td>
 <td class="tdcampos" ></td>
<?php }?>
</tr>
</table>
<div id="bus_entes"></div>


