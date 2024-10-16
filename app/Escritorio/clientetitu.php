<?php
include ("../../lib/jfunciones.php");
sesion();
$cedclien=$_POST["lacedulaclien"];
$elid=$_SESSION['id_usuario_'.empresa];
$cuantpermiso=0;
$buscarpermiso=("select permisos.permiso from permisos where permisos.id_admin=$elid and permisos.id_modulo=7;");
$repbuscarpermis=ejecutar($buscarpermiso);
$cuantpermiso=asignar_a($repbuscarpermis);
$cuantpermiso=$cuantpermiso[permiso];
if($cuantpermiso==1){
$losentes=("select entes.id_ente,entes.nombre,tbl_tipos_entes.tipo_ente from entes,tbl_tipos_entes where entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente order by entes.nombre");
$reslosentes=ejecutar($losentes);

}else{
    
  $losentes=("select entes.id_ente,entes.nombre,tbl_tipos_entes.tipo_ente from entes,tbl_tipos_entes where (entes.id_tipo_ente=4 or entes.id_tipo_ente=6 or entes.id_tipo_ente=8) and  entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente  order by entes.nombre");
  $reslosentes=ejecutar($losentes);
}
$loscargos=("select cargos.id_cargo,cargos.cargo from cargos order by cargo");
$resloscargo=ejecutar($loscargos);
$losestatus=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente from estados_clientes order by estado_cliente;");
$resestatus=ejecutar($losestatus);
$lossubdiv=("select subdivisiones.id_subdivision,subdivisiones.subdivision from subdivisiones order by subdivision");
$ressubdiv=ejecutar($lossubdiv);
$lospaises=("select pais.id_pais,pais.pais from pais order by pais");
$respaises=ejecutar($lospaises);
$partidas=("select tbl_partidas.id_partida,tbl_partidas.tipo_partida from tbl_partidas order by tipo_partida");
$repartida=ejecutar($partidas);
?>
<input type="hidden" id="idclienteid" value="0" > 
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Cliente como titular</td>  
     </tr>
</table>	 
<div id='noesparticular'>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
      <td class="tdtitulos">C&eacute;dula:</td>
      <td class="tdcampos"><? echo "$cedclien"?></td>
	  <td class="tdtitulos">C&oacute;digo:</td>
      <td class="tdcampos"><input type="text" id="clientcodig" class="campos" size="15"></td>  
	<input type="hidden" id="cliencedula" value="<? echo $cedclien?>" >  
     </tr>
      <tr>
        <td class="tdtitulos">Partida:</td>
        <td class="tdcampos"  colspan="1"><select id="clienpartida" class="campos" style="width: 260px;">
          <option value="0"></option>
			<?php  while($verpartida=asignar_a($repartida,NULL,PGSQL_ASSOC)){?>
                <option value="<?php echo $verpartida[id_partida]?>"> <?php echo "$verpartida[tipo_partida]"?></option>
			<?php
			}
			?>
		   </select> 
       </td>
     </tr>

	 <tr>
	   <td class="tdtitulos" colspan="1">Ente:</td>
       <td class="tdcampos"  colspan="1"><select id="clienente" class="campos" onChange="verpoliza()" style="width: 260px;">
                              <option value=""></option>
			   <?php  while($verente=asignar_a($reslosentes,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $verente[id_ente]?>"> <?php echo "$verente[nombre] -- $verente[tipo_ente]"?></option>
			   <?php
			  }
			  ?>
			 </select> 
             <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
            </td> 
	   <td class="tdtitulos" colspan="1">Fecha de ingreso a la empresa:</td>
       <td class="tdcampos"  colspan="1"><input  type="text" size="10" id="inempre" class="campos" maxlength="10">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'inempre', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>  
	 </tr> 
	 <tr>
	   <td class="tdtitulos" colspan="1">Fecha de inclusi&oacute;n:</td>
       <td class="tdcampos"  colspan="1"><input  type="text" size="10" id="feinc" class="campos" maxlength="10" value="<?echo date("Y-m-d")?>">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'feinc', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>  
	   <td class="tdtitulos" colspan="1">Cargo:</td>
       <td class="tdcampos"  colspan="1"><select id="cliencarg" class="campos" style="width: 260px;">
                              <option value=""></option>
			   <?php  while($vercargo=asignar_a($resloscargo,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $vercargo[id_cargo]?>"> <?php echo "$vercargo[cargo]"?></option>
			   <?php
			  }
			  ?>
			 </select>  </td> 			 
	 </tr>  
	<tr>
	   <td class="tdtitulos" colspan="1">Sub-divisi&oacute;n:</td>
       <td class="tdcampos"  colspan="1"><select id="cliensubd" class="campos" style="width: 260px;">
                              <option value=""></option>
			   <?php  while($versubdi=asignar_a($ressubdiv,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $versubdi[id_subdivision]?>"> <?php echo "$versubdi[subdivision]"?></option>
			   <?php
			  }
			  ?>
			 </select>  </td>
	   <td class="tdtitulos" colspan="1">Estado del Cliente:</td>
       <td class="tdcampos"  colspan="1"><select id="clienestatu" class="campos" style="width: 160px;">
                              <option value=""></option>
			   <?php  while($verestatu=asignar_a($resestatus,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $verestatu[id_estado_cliente]?>"> <?php echo "$verestatu[estado_cliente]"?></option>
			   <?php
			  }
			  ?>
			 </select>  </td> 			 
	 </tr>   
	<tr>
	   <td class="tdtitulos" colspan="1">Nombre:</td>
        <td class="tdcampos"  colspan="1"><input type="text" id="cliennombre" class="campos" size="30"></td>
       <td class="tdtitulos" colspan="1">Apellido:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="clienapellido"  class="campos" size="30"></td>
	 </tr>   
	<tr>
	   <td class="tdtitulos" colspan="1">Genero:</td>
        <td class="tdcampos"  colspan="1"><select name="cliengenero" id="cliengenero" class="campos" onChange="vergenero()" style="width: 100px;">
							<option value=""></option>
                            <option value="0">Femenino</option>
							<option value="1">Masculino</option>
						</select>	
		<div id='genero'></div>				
		</td>  				
       <td class="tdtitulos" colspan="1">Correo:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="cliencorre"  class="campos" size="30"></td>
	 </tr>    
	<tr>
	    <td class="tdtitulos" colspan="1">Tel&eacute;fono habitaci&oacute;n:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="clientf1"  class="campos" size="15" onkeypress="return SoloNumeros(event)"></td>				
       <td class="tdtitulos" colspan="1">Tel&eacute;fono celular:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="clientf2"  class="campos" size="15" onkeypress="return SoloNumeros(event)"></td>
	 </tr>  
	 <tr>
	    <td class="tdtitulos" colspan="1">Fecha de nacimiento:</td>
       <td class="tdcampos" colspan="1"><input  type="text" size="10" id="fechanaci" class="campos" maxlength="10">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechanaci', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>				
       <td class="tdtitulos" colspan="1">Estado civil:</td>
       <td class="tdcampos" colspan="1"><select id="clienestcivil" class="campos" style="width: 130px;">
	                           <option value=""></option>  
                              <option value="SOLTERO">Soltero(a)</option>
							  <option value="CASADO">Casado(a)</option>
							  <option value="DIVORCIADO">Divorciado(a)</option>  
							  <option value="VIUDO">Viudo(a)</option>    
							  <option value="CONCUBINO">Concubino(a)</option>      
						</select>	</td>
	 </tr>   
     <tr>
	   				
       <td class="tdtitulos" colspan="1">Pa&iacute;s:</td>
       <td class="tdcampos" colspan="1"><select id="paicli" class="campos" onChange="$('prue1').hide(),paises(); return false"  style="width:130px;" >
                              <option value="0"></option>
			      <?php  while($verpais=asignar_a($respaises,NULL,PGSQL_ASSOC)){?>
			      <option value="<?php echo $verpais[id_pais]?>"> <?php echo "$verpais[pais]"?></option>
			      <?php
			             }
		              ?>
			 </select> </td>
		<td class="tdtitulos" colspan="1">Estado:</td>
	 <td class="tdcampos" colspan="1"><div id="prue1"><select disabled="disabled" class="campos" style="width: 130px;" >
	                               <option value="0">
				       </select>
	</div> <div id="laciudad"></div></td>	 
	 </tr>  
	<tr>
	      
	<td class="tdtitulos" colspan="1">Ciudad:</td>
      <td class="tdcampos" colspan="1"><div id="prue2"><select disabled="disabled" class="campos" style="width: 130px;" >
                                       <option value="0">
	                               </select>
       </div> <div id="laciudad2"></div></td>
	</tr>
	<tr> 
	   <td class="tdtitulos" colspan="1">Direcci&oacute;n:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="cliendirr" class="campos"></TEXTAREA></td>
	</tr> 
	<tr>
	   <td class="tdtitulos" colspan="1">Comentario:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=2 id="cliencoment" class="campos"></TEXTAREA></td>
	</tr>
         <br>
       </table>
  
        <div id='losente'></div>
	<br>
          <table class="tabla_citas"  cellpadding=0 cellspacing=0>
	     <tr>
	       <td title="Guardar cliente"><label id="titularboton" class="boton" style="cursor:pointer" onclick="validcliente()" >Guardar</label>
	      </tr>
          </table>	 
</div>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
<div id='siesparticular'></div>
