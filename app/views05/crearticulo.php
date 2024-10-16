<?
include ("../../lib/jfunciones.php");
sesion();
$querytipoinsumos=("select tbl_tipos_insumos.id_tipo_insumo,tbl_tipos_insumos.tipo_insumo from tbl_tipos_insumos order by tbl_tipos_insumos.tipo_insumo;");
$repquerytipoinsumos=ejecutar($querytipoinsumos);
$laboratorios=("select tbl_laboratorios.id_laboratorio,tbl_laboratorios.laboratorio from tbl_laboratorios order by tbl_laboratorios.laboratorio;");
$replaboratorios=ejecutar($laboratorios);



?>
<style type="text/css">
.buscarcod{
display:block;
position:absolute; 
z-index:8000;
background-color:#89B157; 
border-radius:5px;
-moz-border-radius:10px;
-webkit-border-radius:10px;
box-shadow:#808080 0px 3px 15px, inset 0px 0px 1px #00b300;
-moz-box-shadow:#808080 0px 3px 15px, inset 0px 0px 1px #00b300;
-webkit-box-shadow:#808080 0px 3px 15px, inset 0px 0px 1px #00b300;
 clear: left;
 min-width: 300px;
 max-width: 500px;
	}
.buscarcod ul,li{
	margin-right: 0px;
	padding-right:0px;	
list-style-type: decimal;
}

.buscarcod ul li:hover{
	font-size:12px;
	text-decoration:none;	
	padding-left:22px;
	background:#333333;
	color:#ffffff;
	margin:0 0 0 0;
	border-color: red;
}
.buscarcodinactiva{display:none;}
.spanX {
width: 10px;
height: 10px;
font-size: 80%;
border:2px solid red;
border-color: #000;
padding-bottom: 1px;
padding-left:1px;
padding-right:1px;
padding-top: 1px;
display: block;
color: #000;
float: right;
	}
</style>

<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
 <tr>  
    <td colspan=8 class="titulo_seccion">Crear art&iacute;culo</td>   
   </tr> 
</table>
<table class="tabla_cabecera5" border="0" cellpadding=0 cellspacing=0>
<br>
     <tr>
        <td class="tdtitulos">C&oacute;digo de barra del art&iacute;culo:</td>
        <td id="padre" class="tdcampos" colspan="2">
 <?php 
 				
  ?>
        <input type="text" id="codbarra" class="campos"  size="35"  onblur="buscarcodbarra(this,'procesaRespuesta')" ></td>
    
     </tr>
     <tr>
       <td class="tdtitulos">Nombre del art&iacute;culo:</td>
       <td class="tdcampos" colspan="2">
       <?php $dir=$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
///archivo de sujerencias 
$datos="q='+this.value+'&idcampoinvocaion='+this.id+'";//&:separador de datos variable= datos
$sugerencia="http://$dir/crearticulo_verificarnombre.php?$datos";
        ?>
       <input type="text" id="nombarti" class="campos" onblur="verificarNombreMarca();" size="65" onkeyup="sugerir(this,'<?php echo $sugerencia;?>')" ></td>  
     </tr>
     <tr>
       <td class="tdtitulos">Grupo:</td>
       <td class="tdcampos"  colspan="2">
  
       <select id="grupoarti" class="campos"  style="width: 230px;" onchange="visualizarcaracteristicas()" >
           <option value=""></option>
           <?php
              while($grupoarti=asignar_a($repquerytipoinsumos,NULL,PGSQL_ASSOC)){
											          	 
           	 ?> 
          
              <option value="<?php echo $grupoarti[id_tipo_insumo]?>" id="<?php echo $grupoarti[id_tipo_insumo]?>">
                     <?php echo "$grupoarti[tipo_insumo]"?>
               </option>
             <?php }?>
          </select>
 
          </td>
     </tr>
     <tr>
        <td class="tdtitulos">Marca del art&iacute;culo:</td>
       <td class="tdcampos"  colspan="2">
		<select id="marcarti" class="campos" onchange="verificarNombreMarca();" style="width: 230px;" >
        <option value=""></option>
           <?php
              while($labora=asignar_a($replaboratorios,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $labora[id_laboratorio]?>" id="<?php echo $labora[id_laboratorio]?>" >
                     <?php echo "$labora[laboratorio]"?>
               </option>
             <?php }?>
          </select>

<?php

?>          
          
        </td>
		
     </tr>
	<tr>
	<td colspan="3">
	
<div id="camposCarcarteisticas" style="display:none">  
  	
		<table width="100%" > 
		
				<tr>			<td class="tdtitulos"  title="Nombre distintivo del medicamento">Nombre comercial</td>
					<td class="tdcampos" title="Ejmp: Theraflu Plus" > 
					<input type="text" id="nom_comercial" class="campos" maxlength="50" size="35"></td>
				</tr>

				<tr> <td class="tdtitulos" title="Denominaci&oacute;n oficialmente aceptada en el pa&iacute;s.">Nombre generico</td>
					<td class="tdcampos" title="Ejmp:Paracetamol">
					<input type="text" id="nom_generico" class="campos" maxlength="50" size="35"></td>
				</tr>

				<tr>	<td class="tdtitulos" title="Sustancias a la cual se debe el efecto farmacol&oacute;gico de un medicamento">Principio activo</td>
					<td class="tdcampos" title="Ejmp: Paracetamol, Fenilefrina, Clorfenamina">
					<input type="text" id="princ_activo" class="campos" maxlength="50" size="35"></td>
				</tr>

				<tr>			
					<td class="tdtitulos" title="M&iacute;nimo de unidades considerado para hacer un nuevo pedido">M&iacute;nimo en Stock</td>
					<td class="tdcampos"  title="Ejmp:5">
					<input type="text" value="0" id="minimo_stock" class="campos" size="35" maxlength="5" ></td>
				</tr>

				<tr>	
					<td class="tdtitulos" title="Efecto causado por el agente qu&iacute;mico que act&uacute;a sobre el sistema nervioso">Farmacolog&iacute;a</td>
					<td class="tdcampos" title="Ejem:Analg&eacute;sico, antipir&eacute;tico" >
					<input type="text" id="farmacologia" class="campos" size="35"></td>
				</tr>

				<tr>
					<td class="tdtitulos" title="Otras observaciones del medicamento">Descripci&oacute;n extra</td>
					<td class="tdcampos" title="Ejemp:para el control del dolor leve o moderado">
					<input type="text" id="descripcion" class="campos" size="35"></td>
				</tr>
				<tr>	   
					<td class="tdtitulos">Es un psicotr&oacute;pico ?</td>
	   			<td class="tdcampos" colspan="2">
	    				<input type="radio" name="option1" id="psi" value="2" checked>No
						<input type="radio" name="option1" id="psi" value="1">Si
					
					</td>   
				</tr>
				<tr><td colspan="2" align="center"><button id='MODcaract' style="display:none" onclick="medicacaractactualiza()">Actualizar de medicamentos</button> </td></tr>
				<tr>
				<td colspan="2">
	<div id="carctactuliza"></div>
		<img id="spinner2" src="../public/images/spinner.gif" style="display: none;" />				
				</td>		
				</tr>
		</table>
	
</div>

<div id="verificadatos" style="display:none"></div>
<input type="hidden" id="verificacion" readonly="readonly" value="">
<input type="hidden" id="idinsumo" readonly="readonly" value="">
</td>

</tr>
 
 <tr>
        <td  title="Guardar art&iacute;culo"><label class="boton" style="cursor:pointer" onclick="Guardarti(); return false;" >Guardar</label></td>
        <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
		<td><label title="Registrar nueva marca"class="boton" style="cursor:pointer" onclick="MarcaLab()" >Reg. Marca</label></td>
</tr>
 
</table>   
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
 <tr>  
        <td><img id="CargGuardarti" src="../public/images/spinner.gif" style="display: none;" /></td>   
  </tr> 
</table>
<div id='marcaslab' style="display:none">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
 <tr>  
    <td colspan=8 class="titulo_seccion">Registro de marca</td>   
   </tr> 
</table>

 <table class="tabla_citas"  cellpadding=0 cellspacing=0>
<br> 
     <tr>
        <td class="tdtitulos">Nombre de la marca:</td>
        <td class="tdcampos"><input class="campos" type="text" id="nuenomarca" ></td>
      </tr>
		  <br>  
 <tr>
    <td  title="Guardar nombre de la marca"><label class="boton" style="cursor:pointer" onclick="GuardNombreMarca(); return false;" >Guardar</label></td>
        <td><label title="Salir del Proceso actual"class="boton" style="cursor:pointer" onclick="VerMarcaLab()" >Salir</label></td> 
	 </tr> 
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='seguardomarca'></div>
</div>
