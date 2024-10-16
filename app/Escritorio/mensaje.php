<?php
include ("../../lib/jfunciones.php");

echo cabecera1(sistema);
echo cuerpo_msj();
?>
<table <?php echo propiedades_tabla; ?>>
	<tr>
		<td align="center"><br><h4><?php echo $_REQUEST['mensaje']; ?></h4><br></td>
	</tr>	
	<?php 
	for($i=10;$i>=0;$i--){
		if(!empty($_SESSION['enlace'.$i]) && !empty($_SESSION['boton'.$i])){ 
			echo "<tr><td align=\"center\">
				<a href=\"".$_SESSION['enlace'.$i]."\" class=\"boton\">".$_SESSION['boton'.$i]."</a><br>
			      </td></tr><tr><td style=\"font-size: 4px;\">&nbsp;</td></tr>";
			unset($_SESSION['enlace'.$i]);
			unset($_SESSION['boton'.$i]);
		}
	}
	?>
</table>
<?php
echo cuerpo_2();
echo pie();
?>