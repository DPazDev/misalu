<?php 
include ("../../lib/jfunciones.php");
sesion();

   $tamano = $_FILES["my_files"]['size'];
	$tipo = $_FILES["my_files"]['type'];
	$archivo = $_FILES["my_files"]['name'];
	$prefijo = substr(md5(uniqid(rand())),0,6);
    if ($archivo != "") {
		// guardamos el archivo a la carpeta files
		$destino =  "../../files/".$archivo;
		$nombre_fichero="../../files/$archivo";
	    if (file_exists($nombre_fichero)) {	
			$status = "Error el archivo ya existe!!!";
		}else{	
		  if (copy($_FILES['my_files']['tmp_name'],$destino)) {
			$status = "Archivo subido exitosamente!!";			
		   } else {
			   $status = "Error al subir el archivo";
		    }
		}    
	} else {
		$status = "Error al subir archivo";
	}
	
echo basename($status);
?>
