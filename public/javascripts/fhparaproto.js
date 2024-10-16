

function alertaelemento(str){
	idelementomecreaste=str.id+1;
	idnovo=idelementomecreaste+1;
	if(!document.getElementById(''+idelementomecreaste+''))//exite el nodo
	{	contenedor= crearElemento(str);
		document.getElementById(''+contenedor+'').className="estaconte";
		
		document.getElementById(str.id).addEventListener("blur", function(){EliminaLista(contenedor)});
		cod=document.getElementById(''+contenedor+'').id;
		DivContenedor=document.getElementById(''+contenedor+'');
		idnovo=cod+1;
		document.getElementById(''+contenedor+'').innerHTML=" <div id='"+idnovo+"'><a href='#' ><span class='spanX' onclick=\"EliminaLista('"+contenedor+"')\">X</span></a>"
	document.getElementById(''+idnovo+'').className="esta";
	}


	return idnovo;
}

	//funcion para incorporar un / en la fecha o un formato aceptable de forma aaaa/nn/dd
function fechasformato(elEvento,str){

	//var evento = elEvento || window.event;
	//var key=evento.which:evento.keyCode;
	var key = window.Event ? elEvento.which : elEvento.keyCode;
		retor='';
if((key== 8) || (key== 46) || (key== 40) || (key== 39) || (key== 38) || (key== 37) || (key== 116) || (key== 0)){

		} 
else {
	camp=str.id
	var fecha = document.getElementById(""+camp+"");
	var ncaract=fecha.value.length;
	if(ncaract>9)
	{//fecha
	fec=validarMesAn(fecha.value);
	
		if(fec==false)
		{errorcampo(camp);
		}else{$(camp).style.border='';}
	}
	if( ncaract== 4 || ncaract == 7){
			fecha.value += "/";
	}
			
	retor=(key >= 48 && key <= 57 );
	return retor;
}

}
//fechasformato fin 



/* **** PERMISOS DE USUARIOS **** */
function permisos(){
  usuario=$F('usuario');
  dpto=$F('dpto');
    new Ajax.Updater('reporte', 'views06/reporte_permisos.php', {
	          parameters: {parausuario: usuario, paradpto:dpto}}); 
	document.r_permisos.reset();
}

/* **** MODIFICAR MONTOS EN COBERTURA **** */
function mod_poliza1(){
  ci=$F('ci');
 if(ci.length==0 ){
        alert("El Número de Cédula es Obligatorio.");
        }
    new Ajax.Updater('mod_poliza1', 'views07/mod_poliza1.php', {
	          parameters: {ci: ci}}); 

}

function guardar_poliza(){
        
	contador=$F('contador');
	monto=$F('monto');
	poliza=" ";

	j=0;
	con=0;
		for(var c=0;c<contador;c++){
		j++;	
		formp=document.getElementById("poliza_"+[j]);
		

	if(formp.value==""){
		con++;}
		else {
	formp1=formp.value;
	}	}
	
	if(c-con==0 || monto.length==0){
	alert ("Debe Seleccionar una de las coberturas de Gastos y asignar valor al monto");}
	else {
	if(c-con==1){
 	          new Ajax.Updater('mod_poliza1', 'views07/guardar_poliza.php',{
	          parameters: {poliza: formp1,contador: contador,monto: monto}}); 
               
        }}  }                        

/* **** MODIFICAR SERVICIO Y TIPO DE SERVICIO EN ORDEN **** */

function mod_servicio1(){
  orden=$F('orden');
 if(orden.length==0 ){
        alert("El Número de Orden es Obligatorio.");
        }
    new Ajax.Updater('mod_servicio1', 'views07/mod_servicio1.php', {
	          parameters: {orden: orden}}); 
}

function tiposervicio(){
  ser=$F('ser');
    new Ajax.Updater('tipser', 'views07/tiposervicio.php', {
	          parameters: {ser: ser}}); 
}


function guardar_servicio(){       
	orden=$F('orden');
	ser=$F('ser');
	proser=$F('proservi');
	          new Ajax.Updater('guardar_servicio', 'views07/guardar_servicio.php',{
	          parameters: {ser: ser,proser: proser,orden: orden}});                
        } 




/* **** ASIGNAR POLIZA A CLIENTE YA REGISTRADO **** */
function asig_poliza1(){
  ci=$F('ci');
 if(ci.length==0 ){
        alert("El Número de Cédula es Obligatorio.");
        }
    new Ajax.Updater('asig_poliza1', 'views07/asig_poliza1.php', {
	          parameters: {ci: ci}}); 
}


function guard_poliza_asig(){
	
        var selecttitu=$F('poliza_t');
        var selectbeni=$F('poliza_b');

 		new Ajax.Updater('asig_poliza1', 'views07/guard_poliza_asig.php', {
	          parameters: {selecttitu: selecttitu,selectbeni: selectbeni}});
  }



/* **** ASIGNAR POLIZA INICIAL A CLIENTE YA REGISTRADO **** */
function asig_poliza_inicial1(){
  ci=$F('ci');
 if(ci.length==0 ){
        alert("El Número de Cédula es Obligatorio.");
        }
    new Ajax.Updater('asig_poliza_inicial1', 'views07/asig_poliza_inicial1.php', {
	          parameters: {ci: ci}}); 
}

function bus_poliza(){
	
        var selecttitu=$F('poliza_t');
        var selectbeni=$F('poliza_b');

 		new Ajax.Updater('bus_poliza', 'views07/bus_poliza.php', {
	          parameters: {selecttitu: selecttitu,selectbeni: selectbeni}});
  }


function guard_poliza_inicial(){
	
        var selecttitu=$F('poliza_t');
        var selectbeni=$F('poliza_b');
	var polizass=$F('polizass');

 		new Ajax.Updater('asig_poliza_inicial1', 'views07/guard_poliza_inicial.php', {
	          parameters: {selecttitu: selecttitu,selectbeni: selectbeni,polizass: polizass}});
  }


/* ***** ASIGNAR PREGUNTAS DE SEGURIDAD ***** */

function guardar_pregun_seg(){
	
        var pregunta1=$F('pregunta1');
        var respuesta1=$F('respuesta1');
        var pregunta2=$F('pregunta2');
        var respuesta2=$F('respuesta2');
        var pregunta3=$F('pregunta3');
        var respuesta3=$F('respuesta3');


 if(pregunta1.length =="0" || respuesta1.length =="0" || pregunta2.length =="0" || respuesta2.length =="0" || pregunta3.length =="0" || respuesta3.length =="0"   ){
        alert("DEBE ASIGANAR PREGUNTAS Y RESPUESTAS.");
        }

else{
 		new Ajax.Updater('pregun_seg', 'views07/guardar_pregun_seg.php', {
	          parameters: {pregunta1: pregunta1,respuesta1: respuesta1,pregunta2: pregunta2,respuesta2: respuesta2,pregunta3: pregunta3,respuesta3: respuesta3}});
  }
}


/* ***** VERIFICAR RESPUESTA A LAS PREGUNTAS DE SEGURIDAD ***** */

function pregunta_pass(){
	var respuesta=$F('respuesta');
	var id=$F('id');
	var valor=$F('valor');
	var id_pre=$F('id_pre');
if(respuesta.length=="0"){
        alert("La Respuesta a la Pregunta es Obligatorio.");
}
else{
 		new Ajax.Updater('clientes', 'views07/mod_pass.php', {
	          parameters: {respuesta: respuesta,id: id, valor: valor,id_pre: id_pre}});
  }
}




/* ***** REPORTE CLIENTES POR ENTES, PARA USUARIOS DE OTRAS EMPRESAS ***** */

function reporte_cliente_x_ente_empresas(){       
	estadot=$F('estadot');
	estadob=$F('estadob');
	id_ente=$F('id_ente');

	          new Ajax.Updater('reporte_cliente_x_ente_empresas', 'views06/reporte_cliente_x_ente_empresas.php',{
	          parameters: {estadot: estadot,estadob: estadob,id_ente: id_ente}});    


var1 = document.getElementById('reporte_cliente_x_ente_empresas'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
            
        }

/* ***** REPORTE CONSULTAS MEDICAS POR ENTES, PARA USUARIOS DE OTRAS EMPRESAS ***** */

function reporte_consultas_medicas_x_ente_empresas(){  
        fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
/*alert(fecha1);*/
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;   
/*alert(fecha2);  */
	estado=$F('estado');
	

	          new Ajax.Updater('reporte_consultas_medicas_x_ente_empresas', 'views06/reporte_consultas_medicas_x_ente_empresas.php',{
	          parameters: {fecha1: fecha1, fecha2: fecha2, estado: estado}});    


var1 = document.getElementById('reporte_consultas_medicas_x_ente_empresas'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
            
        } 



function reporte_consultas_medicas_x_ente_empresas2(){  
        fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
/*alert(fecha1);*/
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;   
/*alert(fecha2);  */
	estado=$F('estado');
	

	          new Ajax.Updater('reporte_consultas_medicas_x_ente_empresas', 'views06/reporte_consultas_medicas_x_ente_empresas2.php',{
	          parameters: {fecha1: fecha1, fecha2: fecha2, estado: estado}});    


var1 = document.getElementById('reporte_consultas_medicas_x_ente_empresas'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
            
        } 

/* ***** REPORTE PROCESOS POR USUARIO ESPECIFICO ***** */

function procesos(){
  usuario=$F('usuario');
/*alert(usuario);*/
  fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
/*alert(fecha1);*/
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;   
/*alert(fecha2);  */
	servic=$F('servic');
/*alert(servic);*/
sucur=$F('sucur');
    new Ajax.Updater('procusu', 'views06/reporte_proceso_usuario.php', {
	          parameters: {parausuario: usuario,fecha1: fecha1, fecha2: fecha2, servic: servic, sucur: sucur}}); 


var1 = document.getElementById('procusu'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

}




/* ***** REPORTE VENTAS INDIVIDUALES ***** */

function ventas(){
  fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
/*alert(fecha1);*/
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;   
/*alert(fecha2);  */
	
    new Ajax.Updater('venta', 'views06/reporte_ventas_individuales.php', {
	          parameters: {fecha1: fecha1, fecha2: fecha2}}); 


var1 = document.getElementById('venta'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

}


/* ***** REPORTE FORMA DE PAGO DE VENTAS INDIVIDUALES ***** */

function pago_ventas(){
  fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;

	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;   
	
	pagos=$F('pagos');

    new Ajax.Updater('paventa_ind', 'views06/reporte_pago_ventas_ind.php', {
		parameters: {
			fecha1: fecha1,
			fecha2: fecha2,
			pagos: pagos
		}}); 


var1 = document.getElementById('paventa_ind'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

}






/* **** REINICIAR PASSWORD DESDE MODULO DE SEGURIDAD **** */
function asig_pass(){
  ci=$F('ci');
 if(ci.length==0 ){
        alert("El Número de Cédula es Obligatorio.");
        }
   else new Ajax.Updater('asig_pass', 'views07/asig_pass.php', {
	          parameters: {ci: ci}}); 
}


function guardar_pass1(){
	
id_admin1=$F('id_admin1');
passw=$F('passw');

login=$F('login');
	passw=calcMD5(passw);
       //$passw = document.usuario.passw.value;

 		new Ajax.Updater('asig_pass1', 'views07/guardar_pass1.php', {
	          parameters: {id_admin1: id_admin1,passw: passw,login: login}});
  }




/* ***** REPORTE CONTRATOS ANULADOS DE VENTAS INDIVIDUALES ***** */

function contratos(){
  fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
/*alert(fecha1);*/
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;   
/*alert(fecha2);  */
	
    new Ajax.Updater('venta', 'views06/reporte_contrato_anulado.php', {
	          parameters: {fecha1: fecha1, fecha2: fecha2}}); 


var1 = document.getElementById('venta'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

}





/* ***** REPORTE AUDITAR REEMBOLSO ***** */

function reembolsos(){
  fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
/*alert(fecha1);*/
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;   
/*alert(fecha2);  */
	
    new Ajax.Updater('reemb', 'views06/reporte_auditar_reembolso.php', {
	          parameters: {fecha1: fecha1, fecha2: fecha2}}); 


var1 = document.getElementById('reemb'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

}


/* ***** REPORTE SEGUIR PROCESO ***** */

function ordenes(){
orden=$F('orden');
factura=$F('factura');
serie=$F('serie');	
    new Ajax.Updater('ver_proceso', 'views06/rep_seguir_proceso.php', {
	          parameters: {orden: orden,factura: factura,serie: serie}}); 


var1 = document.getElementById('ver_proceso'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

}




/* ***** REPORTE HISTORIAL DE ARTICULOS ***** */

function his_arts(){
  fecha = document.getElementById("dateField1");
	fecha1 = fecha.value;
/*alert(fecha1);*/
	nombre=$F('nombre');
	estatus=$F('estatus');	



    new Ajax.Updater('art', 'views05/rep_articulos.php', {
	          parameters: {nombre:nombre, fecha1: fecha1, estatus:estatus }}); 


var1 = document.getElementById('art'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

}


/* ***** REPORTE DE ARTICULOS CONSUMIDOS EN PERIODOS DE FECHAS ESPECIFICAS ***** */

function art_consum(){
  fecha = document.getElementById("dateField1");
	fecha1 = fecha.value;
  fecha = document.getElementById("dateField2");
	fecha2 = fecha.value;
/*alert(fecha1);*/
/*alert(fecha2);*/
	nombre=$F('nombre');

    new Ajax.Updater('art_consum', 'views05/rep_art_consumidos.php', {
	          parameters: {nombre:nombre, fecha1: fecha1, fecha2: fecha2, }}); 

var1 = document.getElementById('art_consum'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}




/* ***** ASIGNAR PROPIEDADES POLIZAS A ENTES CON CLIENTES YA REGISTRADOS ***** */


function guard_prop_poliza(){

        var ente=$F('ente');
        var poliza=$F('poliza');

 		new Ajax.Updater('guard_prop_poliza', 'views07/guard_prop_poliza.php', {
	          parameters: {ente: ente,poliza: poliza}});
  }



/* *****  REPORTE ORDENES Y FACTURAS EJECUTADOS EL DIA DE HOY  ***** */
function reporte_procesos_del_dia(){
	
        var sucur=$F('sucur');

		new Ajax.Updater('reporte_procesos_del_dia', 'views06/reporte_procesos_del_dia.php', {
	          parameters: {sucur: sucur}});
  }



/*      ING PATRICIA       */

/* *****  VALIDACIONES PARA REGISTRO MASIVO DE CLIENTE PARA OCUPACIONAL  ***** */

/*** VALIDACION CAMPOS VACIOS ***/
function registro_cliente_masivo1(){
	
num_dato=$F('num_dato'); //cuenta la cantidad de registros
 
 alert(num_dato);
 
 arricedula=        new Array();  
 arriprimer_ape=    new Array(); 
 arriprimer_nom=    new Array();
 arrifecha_nac=     new Array();
 
validar=0;
	k=0 ;                            
 	for(j=0;j<num_dato;j++){
		k++;

///LLENADO DE CAMPO
 arricedula[k]=$F("cedula"+k);  
 arriprimer_ape[k]=$F("primer_ape"+k);   
 arriprimer_nom[k]=$F("primer_nom"+k);
 arrifecha_nac[k]=$F("fecha"+k);
 
/*$f=validarMesAn(fecha);//si esta bien TRUE si esta mal es FAlse
if ($f) { }*/

 //VALIDACION
if (arricedula[k] == null || arricedula[k].length == 0 || /^\s+$/.test(arricedula[k]) || arriprimer_ape[k] == null || arriprimer_ape[k].length == 0 || /^\s+$/.test(arriprimer_ape[k]) || arriprimer_nom[k] == null || arriprimer_nom[k].length == 0 || /^\s+$/.test(arriprimer_nom[k])
|| arrifecha_nac[k] == null || arrifecha_nac[k].length == 0 || /^\s+$/.test(arrifecha_nac[k])) {
validar=0;
}else {

 validar=1;
}


  



if (validar==0)
{
	
ms='mensaje'+k;

 document.getElementById(ms).innerHTML='Revisar';

}

}//fin del for


return 0;
          

}



/******** AUTORIZACION DONATIVOS *********/

function auto_donativo1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/auto_donativo.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function auto_donativo2(){
        var cooperador,proceso,contenedor;
        contenedor = document.getElementById('clientes');
        proceso= document.anularord.proceso.value;
		cooperador= document.anularord.cooperador.value;
		estado_proceso= document.anularord.estado_proceso.value;
			ajax=nuevoAjax();
				ajax.open("GET", "views01/camb_edo.php?proceso="+proceso+"&cooperador="+cooperador+"&estado_proceso="+estado_proceso,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {      
                contenedor.innerHTML = ajax.responseText
                }
			}
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send(null)
			var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}

function auto_donativo2(){
        var contenedor;
		  contenedor = document.getElementById('donativo'); 
	  	  proceso= $F('proceso');
		  
		if (proceso.length==0 || proceso==null || proceso<=0) 
	{
	
		alert ("El Campo Numero de Orden es Obligatorio ");
	}

		else
		{
			    ajax=nuevoAjax();
                ajax.open("GET", "views01/auto_donativo1.php?proceso="+proceso,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
					var1 = document.getElementById('donativo'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
      }
      
      
}

function en_donativo(){
	   var contenedor;
		  contenedor = document.getElementById('clientes');
		   
	varestado_pro=$F('estado_pro');
   varautoriza=$F('autorizados');


    if (varestado_pro!='18') {
    
alert('Debe cambiar el estado de la orden')    	
    	}else {	
     if(varautoriza==''){
		      alert('El campo autorizado es necesario!!! '+varautoriza);
      }else{
      	
       ajax=nuevoAjax();
                ajax.open("GET", "views01/auto_donativo2.php?proceso="+proceso+"&autorizado="+varautoriza,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
					var1 = document.getElementById('donativo'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';      
      }
  }                             
}
/******** AUTORIZACION DONATIVOS *********/



/******** REPORTE DE AUTORIZACION DONATIVOS *********/

function rep_autoridonativos1(){

  contenedor = document.getElementById('rep_autoridonativos');
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;

	 if(fecha1.length==0 || fecha2.length==0){
        alert("Fecha Inicio y Fecha Final son Obligatorios.");
        }
        else{
            	
       ajax=nuevoAjax();
                ajax.open("GET", "views06/rep_autoridonativos2.php?fecha1="+fecha1+"&fecha2="+fecha2,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
					var1 = document.getElementById('rep_autoridonativos'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>'; 
        }
   }

   function ver_proceso_d(donativo)
	{
       url="views01/buscaranular.php?proceso="+donativo ;
 		 	Modalbox.show(this.url, {title: this.title, width: 800, height: 400, overlayClose: false});
	}


/*IMPRESION DEL ACTA*/

function impactadonativo(donativo){
	
 url='views06/iactdonativo.php?donativo='+donativo ;
   imprimir(url);
}

/*IMPRESION DE LA SOLICITUD DE DONATIVO*/

function solicituddonativo(){
	proceso= $F('proceso');
 url='views01/isolicituddonativo.php?proceso='+proceso ;
   imprimir(url);
}
               
/******** FIN DE REPORTE DE AUTORIZACION DONATIVOS *********/


/* REPORTE FACTUAS CON IGTF*/

function rep_fac_igtf1(){
  contenedor = document.getElementById('rep_fac_igtf');
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	sucursal = $F('sucursal');

	 if(fecha1.length==0 || fecha2.length==0){
        alert("Fecha Inicio y Fecha Final son Obligatorios.");
        }
        else{
            	
       ajax=nuevoAjax();
                ajax.open("GET", "views06/rep_fac_igtf2.php?fecha1="+fecha1+"&fecha2="+fecha2+"&sucursal="+sucursal,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
					var1 = document.getElementById('rep_fac_igtf'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>'; 
        }
   } 
/* FIN REPORTE IGTF */

/**/
function rep_excel_igtf(){

	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	sucursal = $F('sucursal');

 url='views06/rep_excel_igtf.php?fecha1='+fecha1+"&fecha2="+fecha2+"&sucursal="+sucursal,true ;
   imprimir(url);
}
    



/*      ING PATRICIA       */


