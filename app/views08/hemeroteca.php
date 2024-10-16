<?php
include ("../../lib/jfunciones.php");
include_once ("../../lib/Excel/reader.php");



function mostrar_archi($carpeta){

    if(is_dir($carpeta)){
	   if($dir = opendir($carpeta)){
		   while(($archivo = readdir($dir)) !== false){
		      if($archivo != '.' && $archivo != '..' && archivo != '.htaccess'){
                    echo '<li><a target="_blank" href="misalu/'.$carpeta.'/'.$archivo.'">'.$archivo.'</a></li>';
				  }
		   }
		   closedir($dir);
	   }
	}
}




?>



<table class="tabla_cabecera3 colortable"  cellpadding=10 cellspacing=10>
     <tr>		
     	<th colspan=1 class="titulo_seccion">Hemeroteca LC/ FT/ PADM</th>
    </tr>	
		 
    <tr>
        <td class="tdtitulos">NOTICIAS</td>
   </tr>
   <tr>
	    <td class="tdcampos"><?echo mostrar_archi("../../hemeroteca/noticias/"); ?></td>

	</tr>
     <tr>
        <td class="tdtitulos">TEMAS DE INTERÉS</td>
    </tr>
    <tr>
	    <td class="tdcampos "><?echo mostrar_archi("../../hemeroteca/t_interes/"); ?></td>
	</tr>
		
</table>
