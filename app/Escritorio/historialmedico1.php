<?
 include ("../../lib/jfunciones.php");
  sesion();
$idinforme=$_REQUEST['numinforme'];
    $informe=("select tbl_informedico.diagnostico,tbl_informedico.laboratorio,tbl_informedico.ultrasonido,tbl_informedico.radiologia,
	                 tbl_informedico.estudiosespe from tbl_informedico where
                     tbl_informedico.id_infomedico=$idinforme;");
    $rephistinform=ejecutar($informe);   
    $datainforme=assoc_a($rephistinform);
    
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <br>
        <tr>  
          <td colspan=8 class="titulo_seccion">Informe Medico</td>   
       </tr> 
 </table>
 <table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
	    <td class="tdcampos">Diagn&oacute;stico:</td>
	    <td class="tdcampos"><TEXTAREA COLS=60 ROWS=2 class="campos" disabled><?echo $datainforme['diagnostico'];?></TEXTAREA></td>	    
	 </tr>
	 <tr>
	    <td class="tdcampos">Laboratorio:</td>
	    <td class="tdcampos"><TEXTAREA COLS=60 ROWS=2 class="campos" disabled><?echo $datainforme['laboratorio'];?></TEXTAREA></td>	    
	 </tr>
	 <tr>
	    <td class="tdcampos">Ultrasonido:</td>
	    <td class="tdcampos"><TEXTAREA COLS=60 ROWS=2 class="campos" disabled><?echo $datainforme['ultrasonido'];?></TEXTAREA></td>	    
	 </tr>  
	 <tr>
	    <td class="tdcampos">Radiolog&iacute;a:</td>
	    <td class="tdcampos"><TEXTAREA COLS=60 ROWS=2 class="campos" disabled><?echo $datainforme['radiologia'];?></TEXTAREA></td>	    
	 </tr> 
	 <tr>
	    <td class="tdcampos">Estudios Especiales:</td>
	    <td class="tdcampos"><TEXTAREA COLS=60 ROWS=2 class="campos" disabled><?echo $datainforme['estudiosespe'];?></TEXTAREA></td>	    
	 </tr>         
</table>   
