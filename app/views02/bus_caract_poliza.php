<?php

include ("../../lib/jfunciones.php");

sesion();



$id_poliza=$_POST['id_poliza'];
$id_tipo_caract=$_POST['id_tipo_caract'];
$control=$_POST['control'];
$caract=strtoupper($_POST['caract']);
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$ordnumero=$_POST['noorden'];
$id_admin= $_SESSION['id_usuario_'.empresa];
/* aqui se registra un examen para algun tipo de baremo */

if ($control==1){//REgistrar

	

	$re_caract_poliza="insert into tbl_caract_poliza (caracteristica,id_poliza,id_tipo_caract,orden) 

values ('$caract','$id_poliza','$id_tipo_caract','$ordnumero');";

$fe_caract_poliza=ejecutar($re_caract_poliza);
	/* **** Se registra lo que hizo el usuario**** */

$log="registro la caracteristica  $caract del id_poliza= $id_poliza 
";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */

}

if ($control==2){ ///ACTUALIZAR
    
$conexa=$_POST['conexa'];

$caracteristica1=$_POST['caracteristica1'];

$idcaractpoliza1=$_POST['idcaractpoliza1'];

$NoOrden=$_POST['noorden'];

$losordenes1=$_POST['losordenes1'];

$caracteristica2=split("@",$caracteristica1);

$idcaractpoliza2=split("@",$idcaractpoliza1);

$NoOrden2=split("@",$NoOrden);

for($i=0;$i<=$conexa;$i++){



	$caracteristica=$caracteristica2[$i];

	$idcaractpoliza=$idcaractpoliza2[$i];
	$NoOrden=$NoOrden2[$i];
   

	if(!empty($idcaractpoliza) && $idcaractpoliza>0){



		 $q.="update 
                            tbl_caract_poliza 
                    set 
                            caracteristica=upper('$caracteristica'),orden=$NoOrden 
                    where  
                            tbl_caract_poliza.id_caract_poliza='$idcaractpoliza' ;
                    ";

	}

	}

$q.="

commit work;

";

$r=ejecutar($q);

	/* **** Se registra lo que hizo el usuario**** */

$log="Actualizo la  caracteristica   del id_poliza= $idcaractpoliza  con $caracteristica
";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */

}


$q_eli_caract=("delete from tbl_caract_poliza where caracteristica=''");

$r_eli_caract=ejecutar($q_eli_caract);

/* fin de  registrar un examen para algun tipo de baremo */



/* **** busco el usuario **** */

$id_admin= $_SESSION['id_usuario_'.empresa];

$q_admin=("select * from admin where admin.id_admin='$id_admin'");

$r_admin=ejecutar($q_admin);

$f_admin=asignar_a($r_admin);



?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>



<tr> <td colspan=5 class="titulo_seccion">CaracteristicaSSSS</td></tr>

<tr><td   colspan=4 class="tdcampos">Caracteristica</td>

<td   colspan=4 class="tdcampos">No. Orden</td>

</tr>

<?php

	

$q_caract_poliza="select * from tbl_caract_poliza where id_poliza=$id_poliza and id_tipo_caract=$id_tipo_caract order by orden";

$r_caract_poliza=ejecutar($q_caract_poliza);



?>

		<?php		

		$i=0;

		while($f_caract_poliza=asignar_a($r_caract_poliza,NULL,PGSQL_ASSOC)){

			$i++;

			

		?>

<tr>

	

<td   colspan=4 class="tdcampos">

<input class="campos" type="hidden" id="id_caract_poliza_<?php echo $i?>" name="id_caract_poliza_<?php echo $i?>" maxlength=128 size=5 value="<?php echo $f_caract_poliza[id_caract_poliza]?>"    >

<textarea class="campos" type="text" id="caracteristica_<?php echo $i?>" name="caracteristica_<?php echo $i?>" cols=100 rows=2    >

<?php echo $f_caract_poliza[caracteristica]?></textarea><input class="campos" type="checkbox" checked style="visibility:block" id="check_<?php echo $i?>" name="checkl" maxlength=128 size=20 value="">  

		

</td>

<td   colspan=1 class="tdcampos">

<input class="campos" type='text' size='3' id="no_orden_<?echo $i?>" name="no_orden_<?echo $i?>" value='<?echo $f_caract_poliza[orden]?>'>	

</td>		

		</tr>

				<?php

		}

		echo "<input type=\"hidden\" id=\"conexa\" name=\"conexa\" value=\"$i\">";

		

		?>

	

		

		

<tr>

		<td colspan=2 class="tdtitulos"> <a href="#"  OnClick="act_caract_poliza();" class="boton"> Actualizar </a>

			</td>

			<td colspan=2 class="tdtitulos"><a href="#"  OnClick="reg_caract_poliza2();" class="boton"> Registar </a>

			</td>

</tr>



</table>

	<div id="bus_reg_caract_poliza"></div>












