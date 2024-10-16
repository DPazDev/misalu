<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$busdepar=("select departamentos.id_departamento,departamentos.departamento 
                 from departamentos order by departamento;");                 
$repbusdepar=ejecutar($busdepar);                 
?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Chat interactivo</td>  
        
	</tr>
  </table>
   <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
           <tr>
             <td class="tdtitulos">Mensaje:</td>
             <td class="tdcampos"><TEXTAREA COLS=65 ROWS=10 id="mensaje" class="campos"><?echo $direccion?></TEXTAREA>
             </td>  
           </tr>
           <tr>
              <td class="tdtitulos">Para el departamento:</td>
              <td class="tdcampos"  colspan="1">
                     <select id="depart" class="campos" style="width: 260px;">
                          <option value=""></option>
			              <?while($eldepar=asignar_a($repbusdepar,NULL,PGSQL_ASSOC)){?>
                             <option value="<? echo $eldepar[id_departamento]?>"> <? echo "$eldepar[departamento]"?></option>
			             <?}?>
			         </select> 
             </td> 
          </tr>
           <tr>
              <td><label title="Enviar mensaje" id="mensaje1" class="boton" style="cursor:pointer" onclick="addmessage();tiempomsj()" >Enviar</label></td>  
              <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
          </tr>
   </table>    
   <input type="hidden" id="idadmin" value="<?echo $elid?>">      
   <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
   <div id='mesajeenviado'></div>
