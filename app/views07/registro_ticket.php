<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include ("../../lib/jfunciones.php");
sesion();
?>
<div class="campos" id="RegistroTickera">
  <div id="titulotiker"><h1>REGISTRO Y GESTION DE TIKERAS</h1></div>

  <div id="NombrePlanTickera"><label fron="TikeraTitulo">PLAN ASIGNADO A TICKAERA</label><input type="text" id="TikeraTitulo" name="TikeraTitulo" values="" placeholder='Nombre del Plan de las Tikeras'></div>

  <div id="CantTikeras"><label fron="CantidadTikeras">CANTIDAD DE TIKERAS A CREAR</label><input type="text" id="CantidadTikeras" name="CantidadTikeras" values="" size=5 onKeyPress="return SoloNumeros(event)"></div>


  <div id="RangosCodigoTikera">
    <label fron="CodTickera">Rangos de Cod/Numero de tickera</label>
    <label fron="CodTickeraInicio">Desde</label><input type="text" size=5 id="CodTickeraInicio" name="CodTickeraInicio" values="" onKeyPress="return SoloNumeros(event)">
    <label fron="TicketRangoFin">Hasta</label><input type="text" size=5 id="CodTickeraFin" name="CodTickeraFin" values="" onKeyPress="return SoloNumeros(event)">
  </div>

  <div id="IdentificacionSerie">
        <label fron="InicialSerie">Inicales de la Serie</label><input type="text" size=5 id="InicialSerie" name="InicialSerie" values="" placeholder='CC'>
  </div>

  <div id="DescripcionTicket"><label fron="NotaTicket" >Descripcion Plan</label><TEXTAREA autocapitalize="characters" id="NotaTicket"  name="NotaTicket" cols="50" rows="4" maxlength="200" placeholder='Descripción de los servicios por los que se puede inercambiar los ticket'></textarea></div>

  <div id="CantidadTicket"><label fron="cantTicket">Cantidad de ticket</label><input type="text" id="cantTicket" name="cantTicket" values="" onKeyPress="return SoloNumeros(event)" placeholder="Ticket por Tikeras"></div>



  <div id="EnvioRegistro"><label fron="generarticket"><span title="Generar Ticket"  class="boton" style="cursor:pointer" onclick="CrearTickeras2()" >Generar</span></label></div>
</div>
<DIV id='activartickera'><label fron="generarticket"><span title="Generar Ticket"  class="boton" style="cursor:pointer" onclick="Tickerasbuscar()" >ACTIVAR TICKERA</span></label></DIV>
<div id='RegistroTickeras'><div>
<div id='RegistroTickerasCompleta'><div>
