<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];
$idtipoparagrafo=$_POST['id_tparagrafo'];
$idparagrafo=$_POST['id_paragrafo'];
$regparagrafo=$_POST['regparagrafo'];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
 /* **** modifico un paragrafo**** */
if ($idparagrafo>0){
	$paragrafo=strtoupper($_POST['paragrafo']);
	
	$f_mod_paragrafo="update 
								tbl_paragrafo
						set  
								paragrafo='$paragrafo'                            
						where 
								tbl_paragrafo.id_paragrafo=$idparagrafo				      
";
	$r_mod_paragrafo=ejecutar($f_mod_paragrafo);
	
	$log="MODIFICO EL PARAGRAFO CON ID $idparagrafo";
logs($log,$ip,$admin);
	}
	
 /* **** Registro un Paragrafo**** */
if ($regparagrafo==1)
{
	$rparagrafo=strtoupper($_POST['rparagrafo']);
$q_rparagrafo = "insert into 
									tbl_paragrafo (id_tipo_paragrafo,paragrafo) values('$idtipoparagrafo','$rparagrafo')";
$r_rparagrafo = ejecutar($q_rparagrafo);
	
		$log="REGISTRO EL PARAGRAFO CON ID $rparagrafo";
logs($log,$ip,$admin);
}

/* **** Fin de Registrar un Paragrafo**** */


$q_paragrafos=("select 
								* 
						from 
								tbl_paragrafo 
						
						where
								tbl_paragrafo.id_tipo_paragrafo='$idtipoparagrafo'
						order by 
								tbl_paragrafo.paragrafo");
$r_paragrafos=ejecutar($q_paragrafos);



?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="oa" id="oa">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Paragrafos </td>	</tr>	
			<?php
			$i=0;
		while($f_paragrafos = asignar_a($r_paragrafos)){
			$i++;
			?>
			<tr>
<td  colspan=4 class="tdtitulos"><hr></hr></td>


		
		</tr>
<tr>

<td  colspan=3 class="tdtitulos">
<textarea class="campos"  id="paragrafo_<?php echo $i?>" name="paragrafo_<?php echo $i?>" cols=90 rows=3><?php  echo $f_paragrafos[paragrafo]; ?></textarea></td>
<td colspan=1 class="tdcampos"><a href="#" OnClick="bus_mod_paragrafo(<?php echo "'$f_paragrafos[id_paragrafo]','$i'"?>);" class="boton" title="Modificar el Paragrafo">Modificar</a>  </td>	
	</tr>	
			
<?php
}
?>
<tr>
<td  colspan=4 class="tdtitulos"><hr></hr></td>
</tr>
		
		<tr>

<td  colspan=3 class="tdtitulos">
<textarea class="campos"  id="rparagrafo" name="rparagrafo" cols=90 rows=3></textarea></td>
<td colspan=1 class="tdcampos"><a href="#" OnClick="reg_rparagrafo(1);" class="boton" title="Registrar un Nuevo Paragrafo">Registrar</a>  </td>	
	</tr>	
</table>

</form>
