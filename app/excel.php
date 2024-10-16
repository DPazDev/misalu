<?php
//Modulo para generar una hoja Excel
include("includes/funciones.php");
session();
header('Content-type: application/vnd.ms-excel'); //Cabecera necesaria para la creacióe la hoja 
$numealeatorio=rand(2,99); // Se crea un numero aleatorio entre el 2 al 99
header("Content-Disposition: attachment; filename=archivo$numealeatorio.xls");// Se crea la hoja con el nombre del numero aleatorio
header("Pragma: no-cache");
header("Expires: 0");//Fin de las cabeceras necesarias para la creacion de una hoja excel



/*Esto es un ejemplo de una hoja Excel que tiene 4 columnas con el nombre *
 *  Codigo                                                                *
 *  Nombre y Apellido                                                     *
 *  Cedula                                                                *
 *  Estado                                                                *
 *  Como se puede ver se puede utilizar codigo PHP y todas las propiedades*
 *  HTML                                                                 */

   echo" <strong>Titulares del ente  $elente</strong>";
   echo"<BR>";
   echo "<table border=1>\n";
   echo "<tr>\n";
   echo "<th>C&oacute;digo</th>\n";
   echo "<th>Nombre Apellido</th>\n";
   echo "<th>C&eacute;dula</th>\n";
   echo "<th>Estado</th>\n";
         while($f_titular=pg_fetch_assoc($query1)){  
        echo"<tr>
                <td>
                       $f_titular[id_titular]
                </td>
                <td >
                        $f_titular[apellidos] $f_titular[nombres]
                </td>
                <td>
                       $f_titular[cedula]
                </td>
                <td>
                <strong>  $f_titular[estado_cliente]  </strong>
                </td>
        </tr>";
               }
 
      
echo"</table>";
?>
