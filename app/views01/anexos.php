<?
include ("../../lib/jfunciones.php");
sesion();
$cuantbeni=0;
$titular=$_REQUEST['titular'];
$idrecibo=$_REQUEST['recibo'];
$buscotitu=("select clientes.nombres,clientes.apellidos from clientes,titulares
                      where clientes.id_cliente=titulares.id_cliente and
                                 titulares.id_titular=$titular;");
$reptitu=ejecutar($buscotitu);                                 
$datatitu=assoc_a($reptitu);
$nombretitu="$datatitu[nombres] $datatitu[apellidos]";
$verbenefi=("select clientes.nombres,clientes.apellidos,beneficiarios.id_beneficiario from clientes,beneficiarios
                        where clientes.id_cliente=beneficiarios.id_cliente and
                       beneficiarios.id_titular=$titular order by clientes.nombres;");
 $repverbeni=ejecutar($verbenefi);        
 $cuantbeni=num_filas($repverbeni);
?>
 <input type="hidden" id="eltitular" value="<? echo $titular?>"> 
 <input type="hidden" id="elrecibo" value="<? echo $idrecibo?>"> 
 <input type="hidden" id="cuanthijo" value="<? echo $cuantbeni?>"> 
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Generar Anexos</td>  
     </tr>
</table>	 
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
         <td class="tdtitulos">Anexo para <?echo $nombretitu?>:</td>
        <td class="tdcampos"><TEXTAREA COLS=60 ROWS=2 id="titular" class="campos" ></TEXTAREA></td>
	  </tr>
	 
	   <?
             if($cuantbeni>=1){
              $be=1;   
              while($losben=asignar_a($repverbeni,NULL,PGSQL_ASSOC)){    
              $hijo="benef$be";
              $cualhijo="hijo$be";
        ?>
           <tr>
                   <td class="tdtitulos">Anexo para <?echo "$losben[nombres] $losben[apellidos]"?>:</td>
                   <td class="tdcampos"><TEXTAREA COLS=60 ROWS=2 id="<?echo $hijo?>" class="campos"></TEXTAREA></td>   
           </tr>
           <input type="hidden" id="<?echo $cualhijo?>" value="<? echo $losben[id_beneficiario]?>"> 
  <?  $be++;
        }
       }?>
       <tr>
	       <td title="Imprimir hoja anexo"><label id="titularboton" class="boton" style="cursor:pointer" onclick="anexos()" >Imprimir</label>
        </tr>
       </table>