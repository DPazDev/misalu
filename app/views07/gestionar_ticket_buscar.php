<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include ("../../lib/jfunciones.php");
sesion();
////ACTIVAR UNA TICKERA


?>
<DIV class="campos" id="RegistroTickera">

  <div id="titulotiker"><h1>BUSCAR TICKERA </h1></div>
  <div id="NombrePlanTickera"><label fron="TikeraTitulo">Numero/Serial/Codigo</label><input type="text" id="CodTickera" name="CodTickera" values="" placeholder='Numero de Tickera'></div>

  <DIV id='activartickera'><label fron="generarticket"><span title="Generar Ticket"  class="boton" style="cursor:pointer" onclick="ActivarTickeras()" >ACTIVAR</span></label></DIV>

</DIV>
<DIV id='resultadotikera'></DIV>
