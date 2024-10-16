<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');
$id_ente=$_REQUEST['ente'];
/* ***** Ver el tipo de ente Juan Pablo ***** */
$vertipoente = ("select entes.id_tipo_ente from entes where id_ente=$id_ente");
$repvetipoente = ejecutar($vertipoente);
$dattipoente   = assoc_a($repvetipoente);
$elidtipoentees = $dattipoente['id_tipo_ente'];
/* ***** Fin de Ver el tipo de ente Juan Pablo ***** */
$q_examen="select 
										examenes_bl.id_examen_bl,
										examenes_bl.examen_bl,
										tbl_baremos_precios.precio
								from 
										examenes_bl,
										tbl_baremos_entes,
										tbl_baremos_precios,
										tbl_baremos
								where 
										examenes_bl.id_examen_bl=tbl_baremos_precios.id_examen_bl and
										tbl_baremos_precios.id_baremo=tbl_baremos.id_baremo and
										tbl_baremos.id_baremo=tbl_baremos_entes.id_baremo  and
										tbl_baremos_entes.id_ente=$id_ente and 
										examenes_bl.id_tipo_examen_bl=3
								order by 
										examenes_bl.examen_bl";
$r_examen=ejecutar($q_examen);
$num_filase=num_filas($r_examen);

if ($num_filase==0){
   if(($elidtipoentees <> 7) || ($elidtipoentees <> 2)){
					$q_examen="select 
															* 
												from 
															examenes_bl 
												where
															examenes_bl.id_tipo_examen_bl=3 
												order by 
															examenes_bl.examen_bl";
					$r_examen=ejecutar($q_examen);

     }
     if(($elidtipoentees == 7) || ($elidtipoentees == 2)){ 
	 $buscidentebare = "select entes.id_ente from entes,tbl_baremos_entes where
                                 entes.id_tipo_ente = 7 and 
                                 entes.id_ente = tbl_baremos_entes.id_ente;";
         $repbuscidentebare = ejecutar($buscidentebare); 
         $datdelidentbare = assoc_a($repbuscidentebare);
         $identebares =  $datdelidentbare['id_ente'];
         $q_examen="select examenes_bl.id_examen_bl, examenes_bl.examen_bl, tbl_baremos_precios.precio 
		              from 
		                 examenes_bl, tbl_baremos_entes, tbl_baremos_precios, tbl_baremos 
		              where 
		               examenes_bl.id_examen_bl=tbl_baremos_precios.id_examen_bl and 
		               tbl_baremos_precios.id_baremo=tbl_baremos.id_baremo and 
		               tbl_baremos.id_baremo=tbl_baremos_entes.id_baremo and 
		               tbl_baremos_entes.id_ente=$identebares and examenes_bl.id_tipo_examen_bl=3 
		               order by examenes_bl.examen_bl";
         $r_examen=ejecutar($q_examen);

      }

     					?>
					<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
       <?php       
$i=0;
$ban="";
 while($f_examen=asignar_a($r_examen,NULL,PGSQL_ASSOC)){
	$i++;
	
	?>
    	<tr>
		<td colspan=4 class="tdtitulos"><hr></hr></td>
			
	</tr>

	<tr>
		<td colspan=2 class="tdtitulos"><?php echo $f_examen[examen_bl]?></td>
		<td colspan=2 class="tdcampos">
		<input class="campos" type="hidden" id="idexamen_<?php echo $i?>" name="idexamenl" maxlength=128 size=20 value="<?php echo $f_examen[id_examen_bl]?>">
		<input class="campos" type="hidden" id="examen_<?php echo $i?>" name="examenl" maxlength=128 size=20 value="<?php echo $f_examen[examen_bl]?>">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="examen" maxlength=128 size=20 value="<?php
                if(($elidtipoentees <> 7) || ($elidtipoentees <> 2)){
  	            echo $f_examen[honorarios];
 	          }
                if(($elidtipoentees == 7) || ($elidtipoentees == 2)){ 
  		    echo $f_examen[precio];
		  }?>"  disabled OnChange="return validarNumero(this);" >
		<input class="campos" type="hidden" id="coment_<?php echo $i?>"  name="coment" maxlength=128 size=20 value=""   >
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkl" maxlength=128 size=20 value=""></td>

		
	</tr>
	<?php
	}

	}
	else
	{

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
       <?php       
$i=0;
$ban="";
 while($f_examen=asignar_a($r_examen,NULL,PGSQL_ASSOC)){
	$i++;
	
	?>
    	<tr>
		<td colspan=4 class="tdtitulos"><hr></hr></td>
			
	</tr>

	<tr>
		<td colspan=2 class="tdtitulos"><?php echo $f_examen[examen_bl]?></td>
		<td colspan=2 class="tdcampos">
		<input class="campos" type="hidden" id="idexamen_<?php echo $i?>" name="idexamenl" maxlength=128 size=20 value="<?php echo $f_examen[id_examen_bl]?>">
		<input class="campos" type="hidden" id="examen_<?php echo $i?>" name="examenl" maxlength=128 size=20 value="<?php echo $f_examen[examen_bl]?>">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="examen" maxlength=128 size=20 value="<?php echo $f_examen[precio]?>"  disabled OnChange="return validarNumero(this);" >
		<input class="campos" type="hidden" id="coment_<?php echo $i?>"  name="coment" maxlength=128 size=20 value=""   >
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkl" maxlength=128 size=20 value=""></td>

		
	</tr>
	<?php
	}
	}
	
echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
		<tr> <td>&nbsp;</td></tr>
	 <tr>				
				<td class="tdtitulos">* Monto</td>
              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=20 value="0"  OnChange="return validarNumero(this);" ></td>
					
	<td><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a>
	</td>

	</tr>		
	
	<tr>
				<td class="tdtitulos">* Cuadro Medico</td>
              	<td class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=20 value=""   ></td>
					<td class="tdtitulos">Hora Cita</td>
              	<td class="tdcampos"><input class="campos" type="text" name="horac" maxlength=128 size=20 value=""   ></td>
              	<td class="tdcampos"><input class="campos" type="hidden" name="decrip" maxlength=128 size=20 value="EXAMENES DE LABORATORIO"   ></td>
				
	</tr>		
		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=2 class="campos"></textarea></td>
	</tr>		
	
	<tr>
</tr>


</table>
