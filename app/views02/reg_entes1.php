<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$sucursales=("select sucursales.id_sucursal,sucursales.sucursal from sucursales order by sucursales.sucursal;");
$repsucursales=ejecutar($sucursales);
$comisionados=("select comisionados.id_comisionado,comisionados.nombres,comisionados.apellidos from comisionados order by comisionados.nombres;");
$repcomisionados=ejecutar($comisionados);
$buscartpente=("select tbl_tipos_entes.id_tipo_ente,tbl_tipos_entes.tipo_ente from tbl_tipos_entes where (tbl_tipos_entes.id_tipo_ente=4 or tbl_tipos_entes.id_tipo_ente=6 or tbl_tipos_entes.id_tipo_ente=8) order by tbl_tipos_entes.tipo_ente;");
$repbusctpente=ejecutar($buscartpente);
$ciudades=("select ciudad.id_ciudad,ciudad.ciudad from ciudad group by ciudad.ciudad,ciudad.id_ciudad order by ciudad.ciudad;");
$repciudades=ejecutar($ciudades);
$polizashay=("select polizas.id_poliza,polizas.nombre_poliza,polizas.descripcion from polizas order by polizas.nombre_poliza;");
$reppolizas=ejecutar($polizashay);
$_SESSION['esmpresa']=1;
?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Registrar Empresa</td>  
        <td colspan=1 class="titulo_seccion" title="Ver empresas creadas"><label class="boton" style="cursor:pointer" onclick="ver_entes1()" >Ver empresas</label></td> 
	</tr>
  </table>
  <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
           <tr>
            
             <td class="tdtitulos">Nombre del ente:</td>
               <td class="tdcampos"><input type="text" name="nomente" id="nomente" class="campos" size="35" onblur="validarcampo('ente')"><div id='nomdelente'> </div> </td>            
               <td class="tdtitulos">Tipo de ente:</td>  
	       <td class="tdtitulos">  
                 <select class="campos" id="tipo_ente" style="width: 230px">
                           <option value="">--Seleccione un Tipo de Ente--</option>
                           <?php  
			                 while($losentesons=asignar_a($repbusctpente,NULL,PGSQL_ASSOC)){
				           ?>
					        <option value="<?php echo $losentesons[id_tipo_ente]?>"> <?php echo "$losentesons[tipo_ente]"?></option>
			             <?}?>
		        </select>  
              </td>             
             </tr>   
			 <tr>
             <td class="tdtitulos">RIF:</td>
             <td class="tdcampos"><input type="text" id="rifente" class="campos" size="35" onblur="validarcampo('rif')"><div id='rifdelente'> </div></td>
              <td class="tdtitulos">Correo:</td>  
	          <td class="tdtitulos"><input type="text" id="corrente" class="campos" size="35"></td>           
             </tr>  
             <tr>
             <td class="tdtitulos">Tel&eacute;fono:</td>
             <td class="tdcampos"><input type="text" id="entetelef" class="campos" size="35"></td>
              <td class="tdtitulos">Fax:</td>  
	          <td class="tdtitulos"><input type="text" id="entefax" class="campos" size="35"></td>           
             </tr>   
               <tr>
             <td class="tdtitulos">Direcci&oacute;n:</td>
             <td class="tdcampos"><TEXTAREA COLS=45 ROWS=3 id="direccente" class="campos" onblur="validarcampo('direccion')"></TEXTAREA><div id='dirente'></div>
             </td>  
             <td class="tdtitulos">Ciudad:</td>
                <td class="tdcampos">
                   <select id="ciudad" class="campos"  style="width: 230px;" >
			       <option value=""></option>
                    <?php  
			         while($lasciudades=asignar_a($repciudades,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo $lasciudades[id_ciudad]?>"> <?php echo "$lasciudades[ciudad]"?></option>
			      <?}?>
		        </select>  
                </td>
             </tr>   
		     <tr>
             <td class="tdtitulos" colspan=4><hr></td>
             </tr>
          
       <tr>
              <td><label title="Registrar ente"  class="boton" style="cursor:pointer" onclick="guardarELENTE()" >Guardar</label></td>  
        </tr>
      </table>
        <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
       <div id='losentes'></div>
