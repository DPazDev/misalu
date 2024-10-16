<?php
include ("../../lib/jfunciones.php");
sesion();
$ciu=("select id_ciudad,ciudad from ciudad order by ciudad;");
$reciu=ejecutar($ciu);
$servi=("select id_servicio,servicio from servicios order by servicio");
$ress=ejecutar($servi);
$epmedi=("select id_especialidad_medica,especialidad_medica from especialidades_medicas order by especialidad_medica;");
$remedi=ejecutar($epmedi);
$epaq=$_POST["cedupp"];
$lasucur=("select id_sucursal,sucursal from sucursales order by sucursal;");
$sucur=ejecutar($lasucur);

?>
<style>


.contenedor-servicios{
display: grid;
justify-items: stretch;
grid-template-columns: 1fr 1fr;
grid-row-gap: 2px;
grid-column-gap: 1px;
 background: #ecf0f1  ;
 border-radius:8px;
 overflow:hidden;
 padding:1rem 1rem 1rem 2rem
}
.contenedor-servicios legend{
  margin:0.5rem 0 0.2rem;
background-size:100% 1px;
background-repeat:no-repeat;
background-position:0 0;
background: #eafaf1;
border-radius:8px;
overflow:hidden;
padding:0.5em 0.5rem 0.5rem 1rem;
border-color: #DAF7A6;
}

.contenedor-servicios div{
color: #0c92ac;
font-weight: bold;
margin:1px 1px 1px 1%;
padding: 0px;
}
.contenedor-servicios #ns-con-direcion{ grid-column: 1;
  grid-row: 1 / 3;}

.contenedor-servicios #ns-con-laboral{
  grid-column: 1/3;
  font-size:0.8rem;
 position:relative;
 margin:0 0 1.5rem;
 background-size:100% 1px;
 background-repeat:no-repeat;
 background-position:0 0;
 background: #fbeee6;
 border-radius:8px;
 overflow:hidden;
 padding:1rem 1rem 1rem 1rem
}

.contenedor-servicios #ns-con-envio{
  grid-column: 1/3;
  margin:1rem 0 0.2rem;
  text-align:center;

  }

/* -----------dar estilo a los horarios --------*/

#ns-con-laboral{
display: grid;
justify-items: stretch;
grid-template-columns:2fr repeat(7,1fr);
grid-template-rows: auto ;
gap: 5px;
}

#ns-con-laboral .column-titulo{
  grid-column: 1/9;
color:  #a9cce3;
font-size: 1.5rem;
text-transform: uppercase;
font-weight: bold;
margin:1px 1px 1px 1%;
padding: 0px;
text-align: center;
width: 100%;
}
#ns-con-laboral .row-titulo{
color: #0c92ac;
font-weight: bold;
font-size: 1rem;
margin:1px 1px 1px 1%;
padding: 0px;
}

#ns-con-laboral input{
color: #0c92ac;
font-weight: bold;
margin:1px 1px 1px 1%;
padding: 1px;
width: 70%;
}

/* ------------------ ESTILO A BOTON checkbox ---------------*/

.checkbox-JASoft input[type=checkbox]  {
	visibility: hidden;
}
.checkbox-JASoft {
	display: inline-block;
	position: relative;
    width: 65px;
	height: 25px;
	background: #555;
	border-radius: 6px;
	box-shadow: inset 0px 1px 1px rgba(0,0,0,0.6), 0px 1px 0px rgba(255,255,255,0.3);
}
.checkbox-JASoft label {

    /* aspecto */
    display: block;
    width: 30px;
	height: 15px;
	border-radius: 17px;
	box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
	background: #fcfff4;
	background: linear-gradient(to top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
    cursor: pointer;

    /* Posicionamiento */
    position: absolute;
    top: 5px;
	left: 5px;
    z-index: 1;

	/* Comportamiento */
    transition: all .4s ease;

    /* ocultar el posible texto que tenga */
    overflow: hidden;
    text-indent: 35px;
    transition: text-indent 0s;
}
.checkbox-JASoft input[type=checkbox]:checked + label {
	left: auto;
    right: 5px;
}
.checkbox-JASoft:after {
	content: 'NO';
	font: 12px/30px Arial, sans-serif;
	color: #AAA;
	position: absolute;
	right: 10px;
    z-index: 0;
	font-weight: bold;
	text-shadow: 1px 1px 0px rgba(255,255,255,.20);
}

.checkbox-JASoft:before {
	content: 'SÍ';
	font: 12px/30px Arial, sans-serif;
	color: lime;
	position: absolute;
	left: 10px;
	z-index: 0;
	font-weight: bold;
}

////////tooltip///////
    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }
    .tooltip .tiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 3px;
        padding: 6px 0;
        position: absolute;
        z-index: 100;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    }
    .tooltip .tiptext::after {
        content: "";
        position: absolute;
        border-width: 5px;
        border-style: solid;
    }
  .tooltip .bottom .tiptext{
      margin-left: -60px;
      top: 150%;
      left: 50%;
  }
  .tooltip .bottom .tiptext::after{
      margin-left: -5px;
      bottom: 100%;
      left: 50%;
      border-color: transparent transparent #2E2E2E transparent;
  }

</style>




<fieldset id='Nuevo-Servicios' class="tdtitulos contenedor-servicios" >
    <legend class="tdtitulos"> FORMULARIO DE SERVICIO NUEVO</legend>
    <Div id='ns-con-direcion'>
      <label for="dprop" class="tdtitulos">Direcci&oacute;n: </label><br>
      <td class="tdtitulos" colspan="1"><TEXTAREA COLS=25 ROWS=5 id="dprop" class="campos"></TEXTAREA>
    </div>

     <Div id='ns-con-telefono'>
       <label  for="tel1p" class="tdtitulos">Tel&eacute;fono:</label><br>
     <input type="text" id="tel1p" class="campos" >
    </Div>


    <Div id='ns-con-servicio'>
          <label for="servp" class="tdtitulos">Servicio:</label><br>
          <select id="servp" class="campos"  style="width: 210px;" >
                                   <option value="0"></option>
                                <?php  while($tservi=asignar_a($ress,NULL,PGSQL_ASSOC)){?>
                                   <option value="<?php echo $tservi[id_servicio]?>"> <?php echo "$tservi[servicio]"?></option>
                                <?php
                               }
                               ?>
                              </select>
    </div>


    <Div id='ns-con-ciudad'>
        <label for="ciup" class="tdtitulos">Ciudad:</label><br>
        <select id="ciup" class="campos" >
                                 <option value="0"></option>
                                 <?php  while($tciudad=asignar_a($reciu,NULL,PGSQL_ASSOC)){ ?>
                                        <option value="<?php echo $tciudad[id_ciudad]?>"> <?php echo "$tciudad[ciudad]"?></option>
                                  <?php
                                     }
                                  ?>
                             </select>
    </div>


    <Div id='ns-con-especialidad'>
                <label for="esmep" class="tdtitulos">Especialidad m&eacute;dica:</label><br>
                <select id="esmep" class="campos" >
                                           <option value="0"> </option>
                                           <?php  while($tmedi=asignar_a($remedi,NULL,PGSQL_ASSOC)){ ?>
                                           <option value="<?php echo $tmedi[id_especialidad_medica]?>"> <?php echo "$tmedi[especialidad_medica]"?></option>
                                          <?php
                                            }
                                         ?>
                                         </select>
    </div>

    <div id='ns-con-horario'>
      <label for="hopp" class="tdtitulos" >Horario:</label><br>
      <TEXTAREA COLS=25 ROWS=4 id="hopp" class="campos"></TEXTAREA>
    </div>

    <Div id='ns-con-comentario'>
      <label for="comentp" class="tdtitulos" >Comentario:</label><br>
      <TEXTAREA COLS=25 ROWS=4 id="comentp" class="campos"></TEXTAREA>
    </div>

    <DIV id='ns-con-sucursal'>
            <label for="sucur" class="tdtitulos" >Sucursal:</label><br>
            <select id="sucur" class="campos" >
                                        <option value="0"></option>
          			      <?php  while($sucunom=asignar_a($sucur,NULL,PGSQL_ASSOC)){ ?>
          			      <option value="<?php echo $sucunom[id_sucursal]?>"> <?php echo "$sucunom[sucursal]"?></option>
          		           <?php
          		            }																	          ?>
          			    </select>
    </DIV>


      <Div id='ns-con-nomina'>
           <label for="pno"  class="tdtitulos" >Es proveedor nomina:</label><br>
               <span>
                 <span class="checkbox-JASoft">
                   <input type="checkbox" id="pvnp" value=1 OnClick="Proveedor();">
                   <label for="pvnp"  title='indica si es un proveedor que esta por nomina' >Si</label>
                 </span>
               </span>
     </div>

     <DIV id='ns-con-costo-servicio'>
                 <fieldset><legend>COSTO DEL SERVICIO</legend>
                 <label for="TipoMonto">Procentaje o monto del servicio</label><br>
                 <select id="TipoMonto"  name="TipoMonto" class="campos" style="width:15%;" onChange="PorcentajeMontoservicio(event,this)"  >
                     <option value="0">%</option>
                     <option value="1">Monto</option>
                 </select>
                 <span class="tooltip bottom">
                   <input type="number" id="CostoServicio" name="CostoServicio" onblur="$(id+'1').style='visibility: hidden;';" onkeyup="id=this.id;elemento=$(id+'1'); elemento.style='visibility: visible;'; elemento.innerHTML=this.value;" class="campos" style="width:20%;" max="100"  min="0" step="1" size='5' value='40' maxlength="4"  onkeydown="return soloMoneda(event,this)" title="Porcentaje o monto del costo de servicio">
                   <span id='CostoServicio1' class="tiptext">Texto del tooltip</span>

                 </span>
                 <!-- -------------------  SELECION DE LA MONEDA ------------------------- -->
                 <?php $monedasconsulta=("select * from tbl_monedas");
                 $repMonedaConsulta=ejecutar($monedasconsulta);
                   if($idMoneda=='0'){$activomoneda='selected';}else{$activomoneda='';}
                 ?>
                     <select id="monedaservicio" class="campos"   >
                           <option id='porcentaje' disabled value="0" <?php echo $activomoneda;?>>%</option>
                           <?php  while($moneda=asignar_a($repMonedaConsulta,NULL,PGSQL_ASSOC)){
                                       if($moneda[id_moneda]==$idMoneda){$activomoneda='selected';}else{$activomoneda='';}
                                ?>
                                       <option value="<?php echo $moneda[id_moneda]?>"  <?php echo $activomoneda;?> > <?php echo "$moneda[simbolo] - $moneda[moneda] "?></option>
                                  <?php
                                            }
                                  ?>
                        </select>

             </fieldset>
      </DIV>

      <DIV id='ns-con-calificacion'>
          <fieldset><legend><label for="extram" class="tdtitulos" >Clasificaci&oacute;n de proveedor?</label></legend>

                                <input type="radio" id="extram" name="grouptp" value="0" checked>Indirecto
                                <input type="radio" id="intram" name="grouptp"  value="1">Directo
      </DIV>

      <DIV id='ns-con-laboral' >
                        <span class="column-titulo">Horario por días</span>
                        <SPAN class="row-titulo">D&iacute;as laborales:</SPAN>
                        <span>Lunes<span class="checkbox-JASoft"><input type="checkbox" id="lunes1"  onchange="idenput=this.id;dia=$F(idenput);idgemelo='estuido'+idenput; if(dia==null || dia==''){gemelo=$(idgemelo).disabled=true;$(idgemelo).focus();}else{gemelo=$(idgemelo).disabled=false;$(idgemelo).focus();}" value='1' disabled><label for="lunes1"  title='Has Click para activar el horario' >Lunes</label></span></span>
                        <span>Martes<span class="checkbox-JASoft"><input type="checkbox" id="martes1" onchange="idenput=this.id;dia=$F(idenput);idgemelo='estuido'+idenput; if(dia==null || dia==''){gemelo=$(idgemelo).disabled=true;}else{gemelo=$(idgemelo).disabled=false;$(idgemelo).focus();}" value='1' disabled>'<label for="martes1" title='Has Click para activar el horario'>Martes</label></span></span>
                        <span>Mi&eacute;rcoles<span class="checkbox-JASoft"><input type="checkbox" id="miercoles1" onchange="idenput=this.id;dia=$F(idenput);idgemelo='estuido'+idenput; if(dia==null || dia==''){gemelo=$(idgemelo).disabled=true;}else{gemelo=$(idgemelo).disabled=false;$(idgemelo).focus();}"value='1' disabled><label for="miercoles1" title='Has Click para activar el horario'>Miércoles</label></span></span>
                        <span>Jueves<span class="checkbox-JASoft"><input type="checkbox" id="jueves1" onchange="idenput=this.id;dia=$F(idenput);idgemelo='estuido'+idenput; if(dia==null || dia==''){gemelo=$(idgemelo).disabled=true;}else{gemelo=$(idgemelo).disabled=false;$(idgemelo).focus();}" value='1' disabled><label for="jueves1" title='Has Click para activar el horario'>Jueves</label></span></span>
                        <span>Viernes<span class="checkbox-JASoft"><input type="checkbox" id="viernes1" onchange="idenput=this.id;dia=$F(idenput);idgemelo='estuido'+idenput; if(dia==null || dia==''){gemelo=$(idgemelo).disabled=true;}else{gemelo=$(idgemelo).disabled=false;$(idgemelo).focus();}" value='1' disabled><label for="viernes1" title='Has Click para activar el horario'>Viernes</label></span></span>
             			      <span>S&aacute;bado<span class="checkbox-JASoft"><input type="checkbox" id="sabado1" onchange="idenput=this.id;dia=$F(idenput);idgemelo='estuido'+idenput; if(dia==null || dia==''){gemelo=$(idgemelo).disabled=true;}else{gemelo=$(idgemelo).disabled=false;$(idgemelo).focus();}" value='1' disabled><label for="sabado1" title='Has Click para activar el horario'>S&aacute;bado</label></span></span>
             			      <span>Domingo<span class="checkbox-JASoft"><input type="checkbox" id="domingo1" onchange="idenput=this.id;dia=$F(idenput);idgemelo='estuido'+idenput; if(dia==null || dia==''){gemelo=$(idgemelo).disabled=true;}else{gemelo=$(idgemelo).disabled=false;$(idgemelo).focus();}" value='1' disabled><label for="domingo1" title='Has Click para activar el horario'>Domingo</label></span></span>

                       <SPAN class="row-titulo">Cantidad de estudios por d&iacute;a</SPAN>
                       <span><input type="number" min="0" max="100" id="estuidolunes1"  value=""  onKeyPress="return soloNumeros(event)" placeholder="Lunes" title='Cantidad de estudios maximo aceptados por el proveedor, 0 es indefinido' disabled ></span>
                       <span><input type="number" min="0" max="100" id="estuidomartes1" value=""  onKeyPress="return soloNumeros(event)" placeholder="Martes" title='Cantidad de estudios maximo aceptados por el proveedor, 0 es indefinido' disabled></span>
                       <span><input type="number" min="0" max="100" id="estuidomiercoles1"value=""  onKeyPress="return soloNumeros(event)" placeholder="Mi&eacute;rcoles" title='Cantidad de estudios maximo aceptados por el proveedor, 0 es indefinido' disabled></span>
                       <span><input type="number" min="0" max="100" id="estuidojueves1" value=""  onKeyPress="return soloNumeros(event)" placeholder="Jueves" title='Cantidad de estudios maximo aceptados por el proveedor, 0 es indefinido' disabled> </span>
                       <span><input type="number" min="0" max="100" id="estuidoviernes1" value="" onKeyPress="return soloNumeros(event)"  placeholder="Viernes" title='Cantidad de estudios maximo aceptados por el proveedor, 0 es indefinido' disabled> </span>
                       <span><input type="number" min="0" max="100" id="estuidosabado1" value="" onKeyPress="return soloNumeros(event)"  placeholder="S&aacute;bado" title='Cantidad de estudios maximo aceptados por el proveedor, 0 es indefinido' disabled> </span>
                       <span><input type="number" min="0" max="100" id="estuidodomingo1" value="" onKeyPress="return soloNumeros(event)"  placeholder="Domingo" title='Cantidad de estudios maximo aceptados por el proveedor, 0 es indefinido' disabled> </span>

               </DIV>

               <DIV class="tdtitulos" id='ns-con-activos' >
                                 <label class="tdtitulos" for="Servicio-Activo-sino" >Activo:</label><br>
                               <span id='Servicio-Activo'>
                                   <input type="radio" name="opc"  id="op1" value='1' checked >Si
                                   <input type="radio" name="opc"  id="op2" value='0'>No
                                </span>
               </DIV>

      <input type="hidden" id="perspro" value="<?php echo $epaq;?>">
      <DIV id='ns-con-envio'>
       <span title="Guardar servicio"><label class="boton" style="cursor:pointer" onclick="guaservi()" >Guardar servicios</label></span>
      </DIV>
 </fieldset>

  <div id="errorproveedor"></DIV>
