<?php
include ("lib/jfunciones.php");


?>
<table <?php echo propiedades_tabla; ?>>
	<tr>
		<td align="center"><br><h4><?php echo $_REQUEST['mensaje']; ?></h4><br></td>
	</tr>	
	<?php 
	for($i=5;$i>=0;$i--){
		if(!empty($_SESSION['enlace'.$i]) && !empty($_SESSION['boton'.$i])){ 
			echo "<tr><td align=\"center\">
				<a href=\"".$_SESSION['enlace'.$i]."\" class=\"boton\">".$_SESSION['boton'.$i]."</a><br>
			      </td></tr><tr><td style=\"font-size: 4px;\">&nbsp;</td></tr>";
			
		}
	}
	?>
</table>
<?php

?>
