function nuevoAjax(){
  var xmlhttp=false;
  try {
   // Creación del objeto ajax para navegadores diferentes a Explorer
   xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
   // o bien
   try {
     // Creación del objet ajax para Explorer
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) {
     xmlhttp = false;
   }
  }

  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
   xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

function ir(url){
  location.href = url;
}

function ventana(url){
        window.open(url,'window','scrollbars=1,width=650,height=350');
}	

function ventana2(url,ancho,alto){
        window.open(url,'window','scrollbars=1,width='+ancho+',height='+alto);
}




/* **** programacion de ing. hunmaira *** */ 

/* **** REGISTRAR PAIS **** */

function pais(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		reg_pais();
	}

function reg_pais(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_pais.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function guardar_pais(){
	var pais, contenedor;
	contenedor = document.getElementById('clientes');
	pais = document.paiss.pais.value;
	if(document.paiss.pais.value.length== 0){
		alert("Debe insertar un nombre valido para el pais");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_pais.php?pais="+pais,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

function modificar_pais(){
	var pais0, contenedor;
	contenedor = document.getElementById('clientes');
	pais0 = document.paiss.pais0.value;

	if(document.paiss.pais0.value.length==0){
		alert("Debe seleccionar un nombre valido para el pais");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/modificar_pais.php?pais0="+pais0,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

function guardar_pais1(){
	var pais_nuevo, id_pais2, contenedor;
	contenedor = document.getElementById('clientes');
	pais_nuevo = document.paiss.pais_nuevo.value;
	id_pais2 = document.paiss.id_pais2.value;
	
	if(document.paiss.pais_nuevo.value.length== 0 || document.paiss.id_pais2.value.length==0){
		alert("Debe insertar un nombre valido para el pais");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_pais1.php?pais_nuevo="+pais_nuevo+"&id_pais2="+id_pais2,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

/* **** FIN DE REGISTRAR PAIS **** */


/* **** REGISTRAR ESTADO **** */

function estado(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		reg_estado();
	}

function reg_estado(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_estado.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function guardar_estado(){
	var estado, pais, contenedor;
	contenedor = document.getElementById('clientes');
	pais = document.estadoo.pais.value;
	estado = document.estadoo.estado.value;

	if(document.estadoo.pais.value.length== 0 || document.estadoo.estado.value.length== 0){
		alert("Debe seleccionar un pais valido y debe insertar un nombre valido para el Estado");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_estado.php?estado="+estado+"&pais="+pais,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
   }
}

function modificar_estado(){
	var estado0, contenedor;
	contenedor = document.getElementById('clientes');
	estado0 = document.estadoo.estado0.value;
/*alert(estado0);*/

	if(document.estadoo.estado0.value.length==0){
		alert("Debe seleccionar un nombre valido para el Estado");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/modificar_estado.php?estado0="+estado0,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

function guardar_estado1(){
	var estado_nuevo, id_pais2, id_estado2, contenedor;
	contenedor = document.getElementById('clientes');
	estado_nuevo = document.estadoo.estado_nuevo.value;
	id_estado2 = document.estadoo.id_estado2.value;
	id_pais2 = document.estadoo.id_pais2.value;
	
	if(document.estadoo.estado_nuevo.value.length== 0 || document.estadoo.id_estado2.value.length==0){
		alert("Debe insertar un nombre valido para el Estado");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_estado1.php?estado_nuevo="+estado_nuevo+"&id_pais2="+id_pais2+"&id_estado2="+id_estado2,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}


/* **** FIN DE REGISTRAR ESTADO **** */


/* **** REGISTRAR CIUDAD **** */

function ciudad(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		reg_ciudad();
	}

function reg_ciudad(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_ciudad.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function guardar_ciudad(){
	var pais, contenedor;
	contenedor = document.getElementById('clientes');
	pais = document.ciudadd.pais.value;

	if(document.ciudadd.pais.value.length== 0){
		alert("Debe seleccionar un Pais valido");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_ciudad.php?&pais="+pais,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
   }
}

function guardar_ciudad1(){
	var ciudad,estado,contenedor;
	contenedor = document.getElementById('clientes');
	estado = document.ciudadd1.estado.value;
	ciudad = document.ciudadd1.ciudad.value;
		

	if(document.ciudadd1.estado.value.length== 0 || document.ciudadd1.ciudad.value.length== 0){
		alert("Debe seleccionar un Estado valido y debe insertar un nombre valido para la Ciudad");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_ciudad1.php?estado="+estado+"&ciudad="+ciudad,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
   }
}

function modificar_ciudad(){
	var ciudad0, contenedor;
	contenedor = document.getElementById('clientes');
	ciudad0 = document.ciudadd.ciudad0.value;


	if(document.ciudadd.ciudad0.value.length==0){
		alert("Debe seleccionar un nombre valido para la Ciudad");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/modificar_ciudad.php?ciudad0="+ciudad0,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

function guardar_ciudad2(){
	var ciudad_nuevo, id_ciudad2, id_estado2, contenedor;
	contenedor = document.getElementById('clientes');
	ciudad_nuevo = document.ciudadd.ciudad_nuevo.value;
	id_estado2 = document.ciudadd.id_estado2.value;
	id_ciudad2 = document.ciudadd.id_ciudad2.value;
	
	if(document.ciudadd.ciudad_nuevo.value.length== 0 || document.ciudadd.id_ciudad2.value.length==0){
		alert("Debe insertar un nombre valido para la Ciudad");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_ciudad2.php?ciudad_nuevo="+ciudad_nuevo+"&id_ciudad2="+id_ciudad2+"&id_estado2="+id_estado2,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

/* **** FIN DE REGISTRAR CIUDAD **** */


/* **** REGISTRAR DEPARTAMENTO **** */

function depart(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		reg_depart();
	}

function reg_depart(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_depart.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function guardar_depart(){
	var depart, coment, contenedor;
	contenedor = document.getElementById('clientes');
	depart = document.departamento.depart.value;
	coment = document.departamento.coment.value;
	
	if(document.departamento.depart.value.length== 0 || document.departamento.coment.value.length== 0){
		alert("Debe insertar un nombre valido para el Departamento y un comentario");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_depart.php?&depart="+depart+"&coment="+coment,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
   }
}

function modificar_depart(){
	var depart0, contenedor;
	contenedor = document.getElementById('clientes');
	depart0 = document.departamento.depart0.value;

	if(document.departamento.depart0.value.length==0){
		alert("Debe seleccionar un nombre valido para el Departamento");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/modificar_depart.php?depart0="+depart0,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

function guardar_depart1(){
	var depart_nuevo, id_depart2, coment_nuevo, contenedor;
	contenedor = document.getElementById('clientes');
	depart_nuevo = document.departamento.depart_nuevo.value;
	id_depart2 = document.departamento.id_depart2.value;
	coment_nuevo = document.departamento.coment_nuevo.value;

	if(document.departamento.depart_nuevo.value.length== 0 || document.departamento.id_depart2.value.length==0){
		alert("Debe insertar un nombre valido para el Departamento");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_depart1.php?depart_nuevo="+depart_nuevo+"&id_depart2="+id_depart2+"&coment_nuevo="+coment_nuevo,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

/* **** FIN REGISTRAR DEPARTAMENTO **** */

/* **** REGISTRAR ORGANO **** */

function organo(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		reg_organo();
	}

function reg_organo(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_organo.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function guardar_organo(){
	var organo, contenedor;
	contenedor = document.getElementById('clientes');
	organo = document.organo.organo.value;

	if(document.organo.organo.value.length== 0){
		alert("Debe insertar un nombre valido para el Organo");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_organo.php?organo="+organo,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

function modificar_organo(){
	var organo0, contenedor;
	contenedor = document.getElementById('clientes');
	organo0 = document.organo.organo0.value;

	if(document.organo.organo0.value.length==0){
		alert("Debe seleccionar un nombre valido para el Organo");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/modificar_organo.php?organo0="+organo0,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

function guardar_organo1(){
	var organo_nuevo, id_organo2, contenedor;
	contenedor = document.getElementById('clientes');
	organo_nuevo = document.organo.organo_nuevo.value;
	id_organo2 = document.organo.id_organo2.value;
	
	if(document.organo.organo_nuevo.value.length== 0 || document.organo.id_organo2.value.length==0){
		alert("Debe insertar un nombre valido para el Organo");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_organo1.php?organo_nuevo="+organo_nuevo+"&id_organo2="+id_organo2,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}
/* **** FIN REGISTRAR ORGANO **** */

/* **** REGISTRAR SUCURSAL **** */

function sucursal(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		reg_sucursal();
	}


function reg_sucursal(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_sucursal.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function guardar_sucursal(){
	var sucursal, direccion, telefono, fax, contenedor;
	contenedor = document.getElementById('clientes');
	sucursal = document.sucursal.sucursal.value;
	direccion = document.sucursal.direccion.value;
	telefono = document.sucursal.telefono.value;
	fax = document.sucursal.fax.value;

	if(document.sucursal.sucursal.value.length== 0 || document.sucursal.direccion.value.length==0 || document.sucursal.telefono.value.length== 0 || document.sucursal.fax.value.length==0){
		alert("Debe insertar un nombre valido para la Sucursal, una Direccion, un Numero de telefono y un Numero de fax");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_sucursal.php?sucursal="+sucursal+"&direccion="+direccion+"&telefono="+telefono+"&fax="+fax,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

function modificar_sucursal(){
	var sucursal0, contenedor;
	contenedor = document.getElementById('clientes');
	sucursal0 = document.sucursal.sucursal0.value;

	if(document.sucursal.sucursal0.value.length==0){
		alert("Debe seleccionar un nombre valido para la Sucursal");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/modificar_sucursal.php?sucursal0="+sucursal0,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}


function guardar_sucursal1(){
	var sucursal_nuevo, id_sucursal2, telefono_nuevo, fax_nuevo, direccion_nuevo, contenedor;
	contenedor = document.getElementById('clientes');
	sucursal_nuevo = document.sucursal.sucursal_nuevo.value;
	id_sucursal2 = document.sucursal.id_sucursal2.value;
	telefono_nuevo = document.sucursal.telefono_nuevo.value;	
	direccion_nuevo = document.sucursal.direccion_nuevo.value;
	fax_nuevo = document.sucursal.fax_nuevo.value;


	if(document.sucursal.sucursal_nuevo.value.length== 0 || document.sucursal.id_sucursal2.value.length==0){
		alert("Debe insertar un nombre valido para la Sucursal");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_sucursal1.php?sucursal_nuevo="+sucursal_nuevo+"&id_sucursal2="+id_sucursal2+"&telefono_nuevo="+telefono_nuevo+"&fax_nuevo="+fax_nuevo+"&direccion_nuevo="+direccion_nuevo,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}
/* **** FIN REGISTRAR SUCURSAL **** */

/* **** REGISTRAR USUARIOS **** */

function reg_usuario(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		reg_usuario1();
}

function reg_usuario1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_usuario1.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function guardar_usuario(){
	var nombres,apellidos,cedula,cargo,depart,sucursal,login,passw,passw1,email,telef,celular,direccion,pais,estado,ciudad,tipo,comentarios,dia,mes,ano,acti,contenedor;
	contenedor = document.getElementById('guardar_usuario');
	nombres = document.usuario.nombres.value;
	apellidos = document.usuario.apellidos.value;
	cedula = document.usuario.cedula.value;
	cargo = document.usuario.cargo.value;
	depart = document.usuario.depart.value;
	sucursal = document.usuario.sucursal.value;
	login = document.usuario.login.value;
	passw = document.usuario.passw.value;
	passw1 = document.usuario.passw1.value;
	email = document.usuario.email.value;
	telef = document.usuario.telef.value;
	celular = document.usuario.celular.value;
	direccion = document.usuario.direccion.value;
	pais = document.usuario.pais.value;
	ciudad = document.usuario.ciudad.value;
	estado = document.usuario.estado.value;
	tipo = document.usuario.tipo.value;
	acti = document.getElementsByName('activar');

	for(i=0; i<acti.length; i++){

	if(acti[i].checked){ 
	acti1=acti[i].value;
	alert (acti1);
	}
	}

	comentarios = document.usuario.comentarios.value;
	dia = document.usuario.dia.value;
	mes = document.usuario.mes.value;
	ano = document.usuario.ano.value;
	passw=calcMD5(passw);
	
	if(nombres.length== 0 || apellidos.length==0 || cedula.length==0 || cargo.length==0 || depart.length==0 || sucursal.length==0 || login.length==0 || email.length==0 || telef.length==0 || celular.length==0){
		alert("Debe verificar que todos los campos esten llenos");
	}
	else 

if(document.usuario.passw.value != document.usuario.passw1.value){
		alert("Los password deben ser iguales");}
	else{ 
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_usuario.php?nombres="+nombres+"&apellidos="+apellidos+"&cedula="+cedula+"&cargo="+cargo+"&depart="+depart+"&sucursal="+sucursal+"&login="+login+"&passw="+passw+"&passw1="+passw1+"&email="+email+"&telef="+telef+"&celular="+celular+"&direccion="+direccion+"&pais="+pais+"&ciudad="+ciudad+"&estado="+estado+"&tipo="+tipo+"&comentarios="+comentarios+"&dia="+dia+"&mes="+mes+"&ano="+ano+"&ac="+acti1,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

function mod_usuario(){
	var contenedor,ci;
	ci=document.usuario.ci.value;
	contenedor = document.getElementById('guardar_usuario');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/mod_usuario.php?ci="+ci,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function guardar_usuario1(){
	var nombres,id_admin1,contenedor;
	contenedor = document.getElementById('clientes');
	nombres = document.usuario.nombres.value;
	id_admin1 = document.usuario.id_admin1.value;
	apellidos = document.usuario.apellidos.value;
	cedula = document.usuario.cedula.value;
	cargo = document.usuario.cargo.value;
	depart = document.usuario.depart.value;
	sucursal = document.usuario.sucursal.value;
	login = document.usuario.login.value;
	passw = document.usuario.passw.value;	
	email = document.usuario.email.value;
	telef = document.usuario.telef.value;
	celular = document.usuario.celular.value;
	direccion = document.usuario.direccion.value;
	pais = document.usuario.pais.value;
	ciudad = document.usuario.ciudad.value;
	estado = document.usuario.estado.value;
	tipo = document.usuario.tipo.value;
	comentarios = document.usuario.comentarios.value;
	dia = document.usuario.dia.value;
	mes = document.usuario.mes.value;
	ano = document.usuario.ano.value;
	passw=calcMD5(passw);

	if(nombres.length== 0 ){
		alert("Debe verificar que todos los campos esten llenos");
	}
	
	else{ 
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_usuario1.php?nombres="+nombres+"&id_admin1="+id_admin1+"&apellidos="+apellidos+"&cedula="+cedula+"&cargo="+cargo+"&depart="+depart+"&sucursal="+sucursal+"&login="+login+"&passw="+passw+"&email="+email+"&telef="+telef+"&celular="+celular+"&direccion="+direccion+"&pais="+pais+"&ciudad="+ciudad+"&estado="+estado+"&tipo="+tipo+"&comentarios="+comentarios+"&dia="+dia+"&mes="+mes+"&ano="+ano,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
    }
}

/* **** FIN REGISTRAR USUARIO**** */

/* **** reportes ing Hummaira **** */




 
/* **** REPORTE RELACION DE ORDENES ENTES PRIVADOS *** */
 
function entpriv(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		r_entpriv();
	}

function r_entpriv(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_entpriv.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function reporte_entpriv(){
        var sucur,servic,enpriv,estapro,lgnue,contenedor;
        contenedor = document.getElementById('reporte_entpriv');
        sucur = document.r_enpri.sucur.value;	
	servic = document.r_enpri.servic.value;
	enpriv = document.r_enpri.enpriv.value;
	estapro = document.r_enpri.estapro.value;
	lgnue = document.r_enpri.lgnue.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;

                if(sucur.length==0 || servic.length==0 || enpriv.length==0 || estapro.length==0 || fecha1.length==0 || fecha2.length==0){
        alert("El Campo Fecha Inicio, Fecha Final, Sucursal, Servicio, Ente Privado, Estado del Proceso son Obligatorios.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_entpriv.php?sucur="+sucur+"&servic="+servic+"&enpriv="+enpriv+
"&estapro="+estapro+"&lgnue="+lgnue+"&fecha1="+fecha1+"&fecha2="+fecha2,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
		var1 = document.getElementById('reporte_entpriv'); 
		var1.innerHTML = '<img src="../public/images/espera.gif"><br><span  class="titulos">Cargando...</span>';
        }
}

function imp_entpriv(){
	sucur = document.r_enpri.sucur.value;	
	servic = document.r_enpri.servic.value;
	enpriv = document.r_enpri.enpriv.value;
	estapro = document.r_enpri.estapro.value;
	lgnue = document.r_enpri.lgnue.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	url='views06/ireporte_entpriv.php?sucur='+sucur+'&servic='+servic+'&enpriv='+enpriv+
'&estapro='+estapro+'&lgnue='+lgnue+'&fecha1='+fecha1+'&fecha2='+fecha2
	imprimir(url);
}

function exc_entpriv(){
	sucur = document.r_enpri.sucur.value;	
	servic = document.r_enpri.servic.value;
	enpriv = document.r_enpri.enpriv.value;
	estapro = document.r_enpri.estapro.value;
	lgnue = document.r_enpri.lgnue.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	url='views06/excel_entpriv.php?sucur='+sucur+'&servic='+servic+'&enpriv='+enpriv+'&estapro='+estapro+'&lgnue='+lgnue+'&fecha1='+fecha1+'&fecha2='+fecha2
	imprimir(url);
}


/* **** FIN REPORTE RELACION DE ORDENES ENTES PRIVADOS *** */


/* **** REPORTE POR PARAMETROS PARA ENTES  *** */

function paraente(){
                var var1 = document.getElementById('clientes');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
                r_paraente();
        }

function r_paraente(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/r_paraente.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}

function buscar_provper(){
        var contenedor;
        contenedor = document.getElementById('buscar_provper');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/buscar_provper.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}

function buscar_provcli(){
        var contenedor;
        contenedor = document.getElementById('buscar_provper');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/buscar_provcli.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}

function buscar_provtotal(){
        var contenedor;
        contenedor = document.getElementById('buscar_provper');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/buscar_provtotal.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}


function reporte_paraente(){
        var sucur,servic,enpriv,estapro,lgnue,contenedor,tipcliente;
        contenedor = document.getElementById('reporte_paraente');

        sucur = document.r_paraente.sucur.value;
	servic = document.r_paraente.servic.value;
	ente = document.r_paraente.ente.value;
	estapro = document.r_paraente.estapro.value;
	lgnue = document.r_paraente.lgnue.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;

	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	tipcliente = document.r_paraente.tipcliente.value;
	proveedor = document.r_paraente.proveedor.value;


                if(fecha1.length==0 || fecha2.length==0 || tipcliente.length==0 || proveedor.length==0){
        alert("El Campo Fecha Inicio, Fecha Final, Sucursal, Servicio, Ente, Estado del Proceso, Tipo de Cliente, proveedor son Obligatorios.");

        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_paraente.php?sucur="+sucur+"&servic="+servic+"&ente="+ente+"&estapro="+estapro+"&lgnue="+lgnue+"&fecha1="+fecha1+"&fecha2="+fecha2+"&tipcliente="+tipcliente+"&proveedor="+proveedor,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
		var1 = document.getElementById('reporte_paraente'); 
        	var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }

}

function imp_paraente(){
	var sucura,url
	sucura = document.r_paraente.sucur.value;
	servic = document.r_paraente.servic.value;
	ente = document.r_paraente.ente.value;
	estapro = document.r_paraente.estapro.value;
	fechai = document.getElementById("dateField1");
		fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	tipcliente = document.r_paraente.tipcliente.value;
	proveedor = document.r_paraente.proveedor.value;
	url='views06/ireporte_paraente.php?sucur='+sucura+'&servic='+servic+'&ente='+ente+'&estapro='+estapro+'&fecha1='+fecha1+'&fecha2='+fecha2+'&tipcliente='+tipcliente+'&proveedor='+proveedor
  	imprimir(url);
}


function exc_paraente(){
	var sucura,url
	sucura = document.r_paraente.sucur.value;
	servic = document.r_paraente.servic.value;
	ente = document.r_paraente.ente.value;
	estapro = document.r_paraente.estapro.value;
	fechai = document.getElementById("dateField1");
		fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	tipcliente = document.r_paraente.tipcliente.value;
	proveedor = document.r_paraente.proveedor.value;
	url='views06/excel_paraente.php?sucur='+sucura+'&servic='+servic+'&ente='+ente+'&estapro='+estapro+'&fecha1='+fecha1+'&fecha2='+fecha2+'&tipcliente='+tipcliente+'&proveedor='+proveedor
  	imprimir(url);
}

    
/* **** FIN REPORTE POR PARAMETROS ENTES  *** */


/* **** REPORTE DE CLIENTES POR EDAD  *** */

function rep_cliente_edad(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		r_cliente_edad();
	}

function r_cliente_edad(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_cliente_edad.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function reporte_cliente_edad(){
        var ente,inicio,fin,tipcliente,contenedor;
        contenedor = document.getElementById('reporte_cliente_edad');
        ente = document.r_clienteedad.ente.value;
	inicio = document.r_clienteedad.inicio.value;
	fin = document.r_clienteedad.fin.value;
	tipcliente = document.r_clienteedad.tipcliente.value;
	estado = document.r_clienteedad.estado.value;

                if(ente.length==0 || inicio.length==0 || fin.length==0){
        alert("El Campo Ente y edades son Obligatorios.");
        }
	else if(inicio > fin){
        alert("Verificar las Edades, la primera Edad debe ser menor que la segunda.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_cliente_edad.php?ente="+ente+"&inicio="+inicio+"&fin="+fin+"&tipcliente="+tipcliente+"&estado="+estado,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
		var1 = document.getElementById('reporte_cliente_edad'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

        }
}

function imp_cliente_edad(){
	ente = document.r_clienteedad.ente.value;
	inicio = document.r_clienteedad.inicio.value;
	fin = document.r_clienteedad.fin.value;
	tipcliente = document.r_clienteedad.tipcliente.value;
	estado = document.r_clienteedad.estado.value;
	url='views06/ireporte_cliente_edad.php?ente='+ente+'&inicio='+inicio+'&fin='+fin+'&tipcliente='+tipcliente+'&estado='+estado
  imprimir(url);
}

function exc_cliente_edad(){
	ente = document.r_clienteedad.ente.value;
	inicio = document.r_clienteedad.inicio.value;
	fin = document.r_clienteedad.fin.value;
	tipcliente = document.r_clienteedad.tipcliente.value;
	estado = document.r_clienteedad.estado.value;
	url='views06/excel_cliente_edad.php?ente='+ente+'&inicio='+inicio+'&fin='+fin+'&tipcliente='+tipcliente+'&estado='+estado
  imprimir(url);
}

/* **** FIN REPORTE CLIENTES POR EDAD  *** */

/* **** REPORTE ESTADO DEL CLIENTE  *** */

function rep_estado_cliente(){
                var var1 = document.getElementById('clientes');
                var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
                r_estado_cliente();
        }

function r_estado_cliente(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/r_estado_cliente.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}


function reporte_estado_cliente(){
        var ente,estado,tipcliente,contenedor;
        contenedor = document.getElementById('reporte_estado_cliente');
        ente = document.r_estadocliente.ente.value;
	estado = document.r_estadocliente.estado.value;
	tipcliente = document.r_estadocliente.tipcliente.value;


                if(ente.length==0 || estado.length==0 || tipcliente.length==0){
        alert("El Campo Ente, Estado y tipo de Cliente son Obligatorios.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_estado_cliente.php?ente="+ente+"&estado="+estado+"&tipcliente="+tipcliente,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
		var1 = document.getElementById('reporte_estado_cliente'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}

function imp_estado_cliente(){
  	ente = document.r_estadocliente.ente.value;
	estado = document.r_estadocliente.estado.value;
	tipcliente = document.r_estadocliente.tipcliente.value;
	url='views06/ireporte_estado_cliente.php?ente='+ente+'&estado='+estado+'&tipcliente='+tipcliente
	imprimir(url);
}

function exc_estado_cliente(){
  	ente = document.r_estadocliente.ente.value;
	estado = document.r_estadocliente.estado.value;
	tipcliente = document.r_estadocliente.tipcliente.value;
	url='views06/excel_estado_cliente.php?ente='+ente+'&estado='+estado+'&tipcliente='+tipcliente
	imprimir(url);
}

/* **** FIN REPORTE ESTADO DEL CLIENTE  *** */

/* **** REPORTE CLIENTES POR ENTES  *** */

function clientexente(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		r_cliente_x_ente();
	}

function r_cliente_x_ente(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_cliente_x_ente.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function reporte_cliente_x_ente(){
        var ente,estadot,estadob,subdivi,contenedor;
        contenedor = document.getElementById('reporte_cliente_x_ente');
        ente = document.r_clientexente.ente.value;
	estadot = document.r_clientexente.estadot.value;
	estadob = document.r_clientexente.estadob.value;
	subdivi = document.r_clientexente.subdivi.value;


                if(ente.length==0 || estadot.length==0 || estadob.length==0){
        alert("El Campo Ente, Estado del Titular, Estado del Beneficiario.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_cliente_x_ente.php?ente="+ente+"&estadot="+estadot+"&estadob="+estadob+"&subdivi="+subdivi,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);

		var1 = document.getElementById('reporte_cliente_x_ente'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>'

        }
}

function imp_cliente_x_ente(){
	ente = document.r_clientexente.ente.value;
	estadot = document.r_clientexente.estadot.value;
	estadob = document.r_clientexente.estadob.value;
	subdivi = document.r_clientexente.subdivi.value;		
	url='views06/ireporte_cliente_x_ente.php?ente='+ente+'&estadot='+estadot+'&estadob='+estadob+'&subdivi='+subdivi
	imprimir(url);
}

function exc_cliente_x_ente(){
	ente = document.r_clientexente.ente.value;
	estadot = document.r_clientexente.estadot.value;
	estadob = document.r_clientexente.estadob.value;
	subdivi = document.r_clientexente.subdivi.value;		
	url='views06/excel_cliente_x_ente.php?ente='+ente+'&estadot='+estadot+'&estadob='+estadob+'&subdivi='+subdivi
	imprimir(url);
}


/* **** FIN REPORTE CLIENTES POR ENTES  *** */

/* **** REPORTE DE CONSULTAS PREVENTIVAS  *** */

function consulta_prevent(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		r_consulta_preventiva();
	}

function r_consulta_preventiva(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_consulta_preventiva.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function reporte_consulta_preventiva(){
        var ci,contenedor;
        contenedor = document.getElementById('reporte_consulta_preventiva');
        ci = document.r_preventiva.ci.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;

	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;

                if(ci.length==0 || fecha1.length==0 || fecha2.length==0){
        alert("El Campo Fecha Inicio, Fecha Final, Numero de Cedula, Tipo de Cliente son Obligatorios.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_consulta_preventiva.php?ci="+ci+"&fecha1="+fecha1+"&fecha2="+fecha2,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
		var var1 = document.getElementById('reporte_consulta_preventiva'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

        }
}

function imp_consulta_preventiva(){
   	ci = document.r_preventiva.ci.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	url='views06/ireporte_consulta_preventiva.php?ci='+ci+'&fecha1='+fecha1+'&fecha2='+fecha2
	imprimir(url);
}

function exc_consulta_preventiva(){
   	ci = document.r_preventiva.ci.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	url='views06/excel_consulta_preventiva.php?ci='+ci+'&fecha1='+fecha1+'&fecha2='+fecha2
	imprimir(url);
}
/* **** FIN REPORTE CONSULTAS PREVENTIVAS  *** */

/* **** REPORTE DE COBERTURA DE CLIENTES POR ENTES  *** */

function cobertura_cliente(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		r_cobertura_clientesxente();
	}

function r_cobertura_clientesxente(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_cobertura_clientesxente.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function reporte_cobertura_clientesxente(){
        var ente,estadot,estadob,contenedor;
        contenedor = document.getElementById('reporte_cobertura_clientesxente');
        ente = document.r_coberturaclientexente.ente.value;
	estadot = document.r_coberturaclientexente.estadot.value;
	estadob = document.r_coberturaclientexente.estadob.value;

                if(ente.length==0 || estadot.length==0 || estadob.length==0){
        alert("El Campo Ente, Estado del Titular, Estado del Beneficiario.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_cobertura_clientesxente.php?ente="+ente+"&estadot="+estadot+"&estadob="+estadob,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);

		var var1 = document.getElementById('reporte_cobertura_clientesxente'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}

function imp_cobertura_clientesxente(){
    	ente = document.r_coberturaclientexente.ente.value;
	estadot = document.r_coberturaclientexente.estadot.value;
	estadob = document.r_coberturaclientexente.estadob.value;
	url='views06/ireporte_cobertura_clientesxente.php?ente='+ente+'&estadot='+estadot+'&estadob='+estadob
	imprimir(url);
}

function exc_cobertura_clientesxente(){
    	ente = document.r_coberturaclientexente.ente.value;
	estadot = document.r_coberturaclientexente.estadot.value;
	estadob = document.r_coberturaclientexente.estadob.value;
	url='views06/excel_cobertura_clientesxente.php?ente='+ente+'&estadot='+estadot+'&estadob='+estadob
	imprimir(url);
}

/* **** FIN REPORTE DE COBERTURA DE CLIENTES POR ENTES  *** */

/* **** REPORTE DE CLIENTES POR COBERTURA POR ENTES  *** */

function clientesxcobertura(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		r_clientes_x_cobertura();
	}

function r_clientes_x_cobertura(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_clientes_x_cobertura.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function r_clientes_x_cobertura1(){
        var ente,contenedor;
        contenedor = document.getElementById('r_clientes_x_cobertura');
        ente = document.r_clientesxcobertura.ente.value;
	
                if(ente.length==0 ){
        alert("El Campo Ente es necesario.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/r_clientes_x_cobertura1.php?ente="+ente,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
        }
}

function reporte_clientes_x_cobertura(){
        var ente,poliza,monto,contenedor;
        contenedor = document.getElementById('r_clientes_x_cobertura1');
        ente = document.r_clientesxcobertura.ente.value;
	poliza = document.r_clientesxcobertura.poliza.value;
	monto = document.r_clientesxcobertura.monto.value;



                if(ente.length==0 || poliza.length==0 || monto.length==0){
        alert("El Campo Ente, Poliza y Monto.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_clientes_x_cobertura.php?ente="+ente+"&poliza="+poliza+"&monto="+monto,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
		var1 = document.getElementById('r_clientes_x_cobertura1'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

        }
}

function imp_clientes_x_cobertura(){
 	ente = document.r_clientesxcobertura.ente.value;
	poliza = document.r_clientesxcobertura.poliza.value;
	monto = document.r_clientesxcobertura.monto.value;
	url='views06/ireporte_clientes_x_cobertura.php?ente='+ente+'&poliza='+poliza+'&monto='+monto
  	imprimir(url);
}

function exc_clientes_x_cobertura(){
 	ente = document.r_clientesxcobertura.ente.value;
	poliza = document.r_clientesxcobertura.poliza.value;
	monto = document.r_clientesxcobertura.monto.value;
	url='views06/excel_clientes_x_cobertura.php?ente='+ente+'&poliza='+poliza+'&monto='+monto
  	imprimir(url);
}

/* **** FIN REPORTE DE CLIENTES POR COBERTURA POR ENTES  *** */

/* **** REPORTE GASTOS DEL CLIENTE   *** */

function gastos_cliente(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		r_gastos_cliente();
	}

function r_gastos_cliente(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_gastos_cliente.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function r_gastos_cliente1(){
        var ci,contenedor;
        contenedor = document.getElementById('r_gastos_cliente');
        ci = document.r_gastoscliente.ci.value;
	
                if(ci.length==0 ){
        alert("El Numero de Cedula es Obligatorio.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/r_gastos_cliente1.php?ci="+ci,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
        }
}

function reporte_gastos_cliente(){
        var ci,poliza,contenedor,formp,contador;
        contenedor = document.getElementById('r_gastos_cliente1');
       	contador = document.r_gastoscliente.contador.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	poliza=" ";
	j=0;
	con=0;
                if(fecha1.length==0 || fecha2.length==0 ){
        alert("Las Fechas son Obligatorios.");
        }
		else{
		for(var c=0;c<contador;c++){
		j++;	
		formp=document.getElementById("poliza_"+[j]);
		if(formp.value==""){
		con++;}
		else {
		formp1=formp.value;}
	}
	if(c-con==0){
	alert ("Debe Seleccionar una de las coberturas de Gastos");}
	else {
	if(c-con==1){
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_gastos_cliente.php?poliza="+formp1+"&fecha1="+fecha1+"&fecha2="+fecha2,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);

		var1 = document.getElementById('r_gastos_cliente1'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';

			}
		else
			{	
			alert ("Solo Debe Seleccionar una sola cobertura de Gastos");
			}
}}}

function imp_gastos_cliente(){
	contador = document.r_gastoscliente.contador.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	poliza=" ";
	j=0;
	con=0;
	for(var c=0;c<contador;c++){
		j++;	
		formp=document.getElementById("poliza_"+[j]);
		if(formp.value==""){
		con++;}
		else {
		formp1=formp.value;}}

	if(c-con==1){
	url='views06/ireporte_gastos_cliente.php?poliza='+formp1+'&fecha1='+fecha1+'&fecha2='+fecha2
}
	imprimir(url);
}

function exc_gastos_cliente(){
	contador = document.r_gastoscliente.contador.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	poliza=" ";
	j=0;
	con=0;
	for(var c=0;c<contador;c++){
		j++;	
		formp=document.getElementById("poliza_"+[j]);
		if(formp.value==""){
		con++;}
		else {
		formp1=formp.value;}}

	if(c-con==1){
	url='views06/excel_gastos_cliente.php?poliza='+formp1+'&fecha1='+fecha1+'&fecha2='+fecha2
}
	imprimir(url);
}

/* **** FIN REPORTE GASTOS DEL CLIENTE  *** */

/* **** REPORTE PARIENTES POR ENTES  *** */

function parientesxente(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		r_parientes_x_ente();
	}

function r_parientes_x_ente(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_parientes_x_ente.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function reporte_parientes_x_ente(){
        var ente,estado,signo,subdivi,contenedor;
        contenedor = document.getElementById('reporte_parientes_x_ente');
        ente = document.r_parientexente.ente.value;
	estado = document.r_parientexente.estado.value;
	signo = document.r_parientexente.signo.value;
	subdivi = document.r_parientexente.subdivi.value;
	cantidad = document.r_parientexente.cantidad.value;
	nu_parentesco = document.r_parientexente.nu_parentesco.value;
    losregistros= new Array
for(i=1;i<=nu_parentesco;i++){
	campos='campo'+i;
	registros=document.getElementById(campos).value;
	if((registros!=0) && (document.getElementById(campos).checked==true)){
		losregistros[i]=registros;
		} 
	}

                if(ente.length==0 || estado.length==0){
        alert("El Campo Ente, Estado del Titular.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_parientes_x_ente.php?ente="+ente+"&estado="+estado+"&signo="+signo+"&subdivi="+subdivi+"&cantidad="+cantidad+"$nu_parentesco="+losregistros,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
        }
}
/* **** FIN REPORTE PARIENTES POR ENTE **** */


/* *** REPORTE PROVEEDORES **** */

function proveedores(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		r_proveedor();
	}

function r_proveedor(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_proveedor.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function reporte_proveedor(){
        contenedor = document.getElementById('reporte_proveedor');
	provee = document.r_proveedor.provee.value;

                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_proveedor.php?provee="+provee,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
		var1 = document.getElementById('reporte_proveedor'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        
}

function imp_proveedor(){
	provee = document.r_proveedor.provee.value;
	url='views06/ireporte_proveedor.php?provee='+provee

	imprimir(url);
}

/* *** FIN REPORTE PROVEEDORES *** */


/* *** REPORTE CONSULTAS MEDICAS POR ENTE *** */

function consultas_medicas(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		r_consultas_medicas_x_ente();
	}

function r_consultas_medicas_x_ente(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_consultas_medicas_x_ente.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function reporte_consultas_medicas_x_ente(){
        var sucur,contenedor;

        contenedor = document.getElementById('reporte_consultas_medicas_x_ente');
        sucur = document.r_consultas_medicas_x_ente.sucur.value;
        nomina = document.r_consultas_medicas_x_ente.nomina.value;
        estado = document.r_consultas_medicas_x_ente.estado.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;


                if(fecha1.length==0 || fecha2.length==0 || sucur.length==0 || nomina.length==0 || estado.length==0 ){
        alert("El Campo Fecha Inicio, Fecha Final, Sucursal, Estado del Proceso y Tipo de Proveedor son Obligatorios.");
        }
		else{
                ajax=nuevoAjax();
                ajax.open("GET", "views06/reporte_consultas_medicas_x_ente.php?sucur="+sucur+"&fecha1="+fecha1+"&nomina="+nomina+"&estado="+estado+"&fecha2="+fecha2,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);

		var1 = document.getElementById('reporte_consultas_medicas_x_ente'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}

function imp_consultas_medicas_x_ente(){
 	sucur = document.r_consultas_medicas_x_ente.sucur.value;
        nomina = document.r_consultas_medicas_x_ente.nomina.value;
        estado = document.r_consultas_medicas_x_ente.estado.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	url='views06/ireporte_consultas_medicas_x_ente.php?sucur='+sucur+'&nomina='+nomina+'&estado='+estado+'&fecha1='+fecha1+'&fecha2='+fecha2
  	imprimir(url);
}

function exc_consultas_medicas_x_ente(){
 	sucur = document.r_consultas_medicas_x_ente.sucur.value;
        nomina = document.r_consultas_medicas_x_ente.nomina.value;
        estado = document.r_consultas_medicas_x_ente.estado.value;
	fechai = document.getElementById("dateField1");
	fecha1 = fechai.value;
	fechaf = document.getElementById("dateField2");
	fecha2 = fechaf.value;
	url='views06/excel_consultas_medicas_x_ente.php?sucur='+sucur+'&nomina='+nomina+'&estado='+estado+'&fecha1='+fecha1+'&fecha2='+fecha2
  	imprimir(url);
}
/* *** FIN REPORTE CONSULTAS MEDICAS POR ENTES *** */

/* *** REPORTE CONSULTAS MEDICAS POR PROVEEDORES *** */

function consultas_provee(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		r_consultas_medicas_proveedor();
	}

function r_consultas_medicas_proveedor(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views06/r_consultas_medicas_proveedor.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}









/* **** fin de programacion ing hummaira **** */



function buscarusuario(){
	var usuario,contenedor;
	contenedor = document.getElementById('buscarusuario');

	usuario = document.usuario.usuario.value;
	if(usuario.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "views07/buscarusuario.php?usuario="+usuario,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.send(null);
	}
}

function buscarcedula(){
	var cedula,contenedor;
	contenedor = document.getElementById('buscarcedula');
	cedula = document.usuario.cedula.value;
	if(cedula.length==0){
	alert("El Campo cedula es Obligatorio.");
	}
		
	if(cedula.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "views07/buscarcedula.php?cedula="+cedula,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.send(null);
	}
}


function buscarestado(){
	var pais,contenedor;
	contenedor = document.getElementById('buscarestado');
	pais = document.clientes.pais.value;
	if(pais.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "views01/buscarestado.php?pais="+pais,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		ajax.send(null);
	}
}

function buscarciudad(){
	var estado,contenedor;
	contenedor = document.getElementById('buscarciudad');
	estado = document.clientes.estado.value;
	if(estado.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "views01/buscarciudad.php?estado="+estado,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		ajax.send(null);
	}
}

function changeFocus(){
       alert('hola');
}

function verificarclave(){
	var clave,clave1,contenedor;
	contenedor = document.getElementById('verificarclave');

	clave = document.usuario.clave.value;
	clave1 = document.usuario.clave1.value;
	if(clave != clave1){
	alert("Las claves tienen que ser iguales.");
	}
	
}

/* **** registrar porveedor Juan P**** */

function reg_prope(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views03/regprovee.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/* **** fin registrar porveedor **** */
/* **** Control de Articulos**** */
function control_articulos(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/controlarti.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/* **** fin control articulos**** */
/* **** Ver los donativos **** */
function ver_donativos(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/verpdonati.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/* **** fin de los donativos **** */

/* **** Orden de bienes **** */
function orden_bienes(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/orden_bienes.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/* **** fin de orden de bienes **** */



/* **** Crear articulo **** */
function crea_articulo(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/crearticulo.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/* **** fin de crear articulo **** */
/* **** Modificar articulo **** */

function modif_articulo(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/modfarticulo.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/* **** fin de modificar articulo **** */
/* **** Ver pedidos porveedor **** */
function ver_pediprov(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/verpeprove.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/* **** fin ver pedidos porveedor **** */

/* **** Ver pedidos dependencia **** */
function ver_pedidepen(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/verpedepen.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/* **** fin ver pedidos dependencia **** */

/********  Registrar cliente Pri/Par ********/

 function reg_clientesPP(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views01/nuevo_clientepp.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}

/********  Fin de Registrar cliente Pri/Par ********/

/********  Registrar cliente ********/
 function reg_clientes(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views01/nuevo_cliente.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/********  Fin de Registrar cliente ********/

/* **** registrar porveedor clinica**** */

function reg_procli(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views03/regproclin.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}
/* **** fin registrar porveedor clinica**** */

/* **** registrar porveedor persona**** */

function roa_pp(){
    var contenedor;
    contenedor = document.getElementById('clientes');
    ajax=nuevoAjax();
    ajax.open("GET", "views06/finiq_pper.php",true);
    ajax.onreadystatechange=function() {
	if (ajax.readyState==4){
	    contenedor.innerHTML = ajax.responseText
	    }
    }
    ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    ajax.send(null)
}

/* **** fin registrar porveedor persona**** */

/********  Registrar Farma Express ********/
 function reg_farma(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views01/farmaexp.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/********  Fin de Registrar cliente ********/
/********** Registrar Donativo ******/
 function crea_donativo(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/pedidonativo1.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/************ Fin del Donativo **************/


/********** Registrar Pedido Dependencia ******/
 function crea_pedido(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/pedidoprovee.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/************ Fin del Registro **************/
/********** Registrar Pedido Proveedor ******/
 function crea_pediprov(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/pedidoprovedor.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/************ Fin del Registro **************/
/********** Reporte Finiquito Modificable ******/

 function finiquitoMF(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/finiq_modifca.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
               contenedor.innerHTML = ajax.responseText
                }
        }

        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)

}

/************ Fin de Reporte Finiquito Modificable **************/
/********** Registrar Orden inventario muerto ******/

 function ver_inventariomuerto(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/inventariomuerto.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
               contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/************ Fin de inventario muerto **************/
/********** Control de Inventario 2 ******/

 function inventario2(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/controlartiinventario.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
               contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/************ Fin de Control de Inventario 2 **************/
/********** Control de Inventario - Compra ******/

 function invencompra(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/controlartiinventario2.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
               contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/************ Fin de Inventario - Compra **************/

/********** Reporte Finiquito E-H ******/

 function rep_finiquitoeh(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/finiquitoeh.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
               contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/************ Fin de Reporte Finiquito E-H **************/

/********** Reporte Finiquito Modificable ******/

 function finiquitoMF(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/finiq_modifca.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
               contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/************ Fin de Reporte Finiquito Modificable **************/

/********** Registrar Orden inventario muerto ******/
 function ver_inventariomuerto(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/inventariomuerto.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/************ Fin de inventario muerto **************/

/**********  Ordenes de Medicamento ******/
 function ordenes_medicamentos(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/r_medicinas.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/************ Fin de Ordenes de Medicamento **************/

/********  Crear Pedido (Compras) ********/
 function crea_dep(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views05/depen.php",true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
                }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}
/********  Fin de Registrar cliente Juan Pablo********/

/* **** buscar proveedor persona **** */ 

function buscarprovp(){
        var contenedor;
	
        contenedor = document.getElementById('buscarprovp');
        contador = document.oa.contador.value
		servicio = document.oa.servicio.value;
		tiposerv = document.oa.tiposerv.value;
		fechar = document.getElementById("dateField1");
		fechare=fechar.value;
		fechac = document.getElementById("dateField2");
		fecharci=fechac.value;
		horac = document.oa.horac.value;
		monto =document.oa.monto.value;
		enfermedad = document.oa.enfermedad.value;
		decrip = document.oa.decrip.value;
		comenope = document.oa.comenope.value;
		montoh =document.oa.montoh.value;
		montog = document.oa.montog.value;
		montoo = document.oa.montoo.value;
		numpro = document.oa.numpro.value;
		donativo = document.oa.donativo.value;
		cobertura=" ";
		j=0;
		con=0;
           if (fechare.value=="" || monto.length==0 || enfermedad.length==0 || numpro.length=="") {
		alert ("Los campos Fecha de Recepcion, Monto, Cuadro Medico, Numero Preforma en caso de ser una carta compromiso son obligatorios ");
		}
		else
		{
			
			for(var i=0; i<contador; i++)
			{
                j++;
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
                }
				else
				{
					formp1=formp.value;
					}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas ");
				}
				else	
				{
				if (i - con==1)
				{
				
                ajax=nuevoAjax();
                ajax.open("GET", "views01/provp.php?formp1="+formp1+"&monto="+monto+"&tiposerv="+tiposerv+"&donativo="+donativo,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
					var1 = document.getElementById('buscarprovp'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
				}
				else
			{	
			alert ("Solo Debe Seleccionar una sola cobertura  ");
			}
			}
		}
}


/* **** fin buscar proveedor persona **** */

/* **** buscar proveedor clinica **** */ 
function buscarprovc(){
        var contenedor;
		
        contenedor = document.getElementById('buscarprovp');
		contador = document.oa.contador.value
		servicio = document.oa.servicio.value;
		tiposerv = document.oa.tiposerv.value;
		fechar = document.getElementById("dateField1");
		fechare=fechar.value;
		fechac = document.getElementById("dateField2");
		fecharci=fechac.value;
		horac = document.oa.horac.value;
		monto =document.oa.monto.value;
		enfermedad = document.oa.enfermedad.value;
		decrip = document.oa.decrip.value;
		comenope = document.oa.comenope.value;
		montoh =document.oa.montoh.value;
		montog = document.oa.montog.value;
		montoo = document.oa.montoo.value;
		numpro = document.oa.numpro.value;
		donativo = document.oa.donativo.value;
		cobertura=" ";
		j=0;
		con=0;
        if (fechare.value=="" || monto.length==0 || enfermedad.length==0 || numpro.length=="") {
		alert ("Los campos Fecha de Recepcion, Monto, Cuadro Medico, Numero Preforma en caso de ser una carta compromiso son obligatorios ");
		}
		else
		{
			
			for(var i=0; i<contador; i++)
			{
                j++;
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
                }
				else
				{
					formp1=formp.value;
					}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas  ");
				}
				else	
				{
				if (i - con==1)
				{
			
			
                ajax=nuevoAjax();
                ajax.open("GET", "views01/provc.php?formp1="+formp1+"&monto="+monto+"&tiposerv="+tiposerv+"&donativo="+donativo,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('buscarprovp'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
				}
				else
			{	
			alert ("Solo Debe Seleccionar una sola cobertura  ");
			}
			}
		}
}
/* **** fin buscar proveedor clinica **** */

/* **** asignar cobertura auxiliar **** */ 
function asig_cober(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		asig_cober1();
}

function asig_cober1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/asig_cober.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function asigcober(){
        var contenedor;
		
        contenedor = document.getElementById('asigcober');
      	proceso= document.oa.proceso.value;
			if (proceso.length==0) 
	{
	
		alert ("El Campo Numero de Orden es Obligatorio ");
	}

		else
		{
		
		
                ajax=nuevoAjax();
                ajax.open("GET", "views01/asigcober.php?proceso="+proceso,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('asigcober'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		}
}

function asigguar(){
        var contenedor;
		
        contenedor = document.getElementById('asigguar');
		contador = document.oa.contador.value
		monto =document.oa.monto.value;
		tiposerv = document.oa.tiposerv.value;
		servicio = document.oa.servicio.value;
		
		tp = document.oa.tp.value;
		
		cobertura=" ";
	
		j=0;
		con=0;
        	for(var i=0; i<contador; i++)
			{
                j++;
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
                }
				else
				{
					formp1=formp.value;
					}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas  ");
				}
				else	
				{
				if (i - con==1)
				{
				
		        ajax=nuevoAjax();
                ajax.open("GET", "views01/asigguar.php?formp1="+formp1+"&monto="+monto+"&tiposerv="+tiposerv+"&servicio="+servicio+"&tp="+tp,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('asigguar'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
				}
				else
			{	
			alert ("Solo Debe Seleccionar una sola cobertura ");
			}
			}
				
}



/* **** fin asignar cobertura auxiliar  **** */

/* **** buscar examenes de laboratorio**** */ 
function buscarexal(){
        var contenedor;
			var1 = document.getElementById('buscarexa'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('buscarexa');
        
                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscarexal.php",true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
}
/* **** fin buscar examenes de laboratorio **** */

/* **** buscar examenes de laboratorio APS**** */
function buscarexalaps(){
        var contenedor;
                        var1 = document.getElementById('buscarexa');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('buscarexa');

                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscarexalaps.php",true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
}
/* **** fin buscar examenes de laboratorio APS**** */


/* **** buscar examenes especiales **** */ 
function buscarexae(){
        var contenedor,examenes;
			var1 = document.getElementById('buscarexa'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('buscarexa');
        examenes= document.oa.examenes.value;
                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscarexae.php?examenes="+examenes,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
}
/* **** fin buscar examenes especiales **** */

/* **** buscar examenes especiales APS**** */
function buscarexaeaps(){
        var contenedor,examenes;
                        var1 = document.getElementById('buscarexa');
                var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('buscarexa');
        examenes= document.oa.examenes.value;
                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscarexaeaps.php?examenes="+examenes,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
}
/* **** fin buscar examenes especiales APS**** */


/* **** quitar examenes de laboratorio radiologicos **** */ 

function quitarexa(){
        var contenedor;
			var1 = document.getElementById('buscarexa'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('buscarexa');
        
                ajax=nuevoAjax();
                ajax.open("GET", "views01/vacio.php",true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
}


/* **** quitar examenes de laboratorio radiologicos **** */

/* ****** Registrar Orden Atencion***** */
function reg_oa(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		reg_oa1();
}

function reg_oa1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/reg_oa.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function reg_oats(){
	var contenedor;
	var1 = document.getElementById('reg_oats'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	contenedor = document.getElementById('reg_oats');
	servicio = document.oa.servicio.value;
	 cedula = document.oa.cedula.value;
	 if(cedula.length==0 || servicio.length==0)
		{
        alert("El Campo cedula  y servicio es Obligatorio.");
		}
		else
		{
	ajax=nuevoAjax();
	ajax.open("GET", "views01/reg_oa1.php?servicio="+servicio,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
	}
}



function reg_oa2(){
        var cedula,contenedor;
			var1 = document.getElementById('reg_oa2'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('reg_oa2');
        cedula = document.oa.cedula.value;
		tiposerv = document.oa.tiposerv.value;
		servicio = document.oa.servicio.value;
        if(cedula.length==0 || tiposerv.length==0)
		{
        alert("El Campo cedula, servicio y tipo servicio es Obligatorio.");
		}

        if(cedula.length>0 && tiposerv.length>0){ 
                ajax=nuevoAjax();
                ajax.open("GET", "views01/reg_oa2.php?cedula="+cedula+"&tiposerv="+tiposerv+"&servicio="+servicio,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				
        }
		else
		{
			
			      ajax=nuevoAjax();
                ajax.open("GET", "views01/vacio.php?",true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor1.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
			
			}
		
}
/* **** Este guardar es para examenes **** */
function guardaroa(){
        var 
tiposerv,fechacitas,fechar,fechac,horac,enfermedad,decrip,comenope,con,cobertura,contador,monto,id_proveedor,formp,examenes,contenedor;
        contenedor = document.getElementById('clientes');
		proceso= document.oa.proceso.value;
        contador = document.oa.contador.value;
		conexa = document.oa.conexa.value;
		id_proveedor = document.oa.proveedor.value;
		servicio = document.oa.servicio.value;
		tiposerv = document.oa.tiposerv.value;
		fechar = document.getElementById("dateField1");
		fechare=fechar.value;
		fechac = document.getElementById("dateField2");
		fecharci=fechac.value;
		horac = document.oa.horac.value;
		monto =document.oa.monto.value;
		enfermedad = document.oa.enfermedad.value;
		decrip = document.oa.decrip.value;
		comenope = document.oa.comenope.value;
		examenes= document.oa.examenes.value;
		organo= document.oa.organo.value;
		edoproceso= document.oa.edoproceso.value;
		numpre= document.oa.numpre.value;
		donativo= document.oa.donativo.value;
	    cobertura="";
		examen1="";
		idexamen1="";
		honorarios1= "";
		coment1= "";
					
        j=0;
		z=0;
	con=0;
	if (fechare.value=="" || monto.length==0 || id_proveedor.length==0) {
		alert ("Los campos Fecha de Recepcion, Monto, Cuadro Medico, Proveedor son obligatorios ");
		}
		else
		{
			
			for(var i=0; i<contador; i++)
			{
                j++;
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
                }
				else
				{
					formp1=formp.value;
				}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas  ");
				}
				else	
				{
				if (i - con==1)
				{
					
					
						j=0;
 	for(var i=0; i<conexa; i++) 
	{
	
		j++;
		formexa = document.getElementById("check_"+[j]);
		if(formexa.checked) 
			
		{
			z++;
			idexamen=document.getElementById("idexamen_"+[j]);
		 	examen=document.getElementById("examen_"+[j]);
			honorarios=document.getElementById("honorarios_"+[j]);
			coment=document.getElementById("coment_"+[j]);
			idexamen1 +="@"+idexamen.value;
			examen1 +="@"+examen.value;
			honorarios1 +="@"+honorarios.value;
			coment1 +="@"+coment.value;
				
	
	}
	}
				if (z>0)
			{					
				ajax=nuevoAjax();
				ajax.open("GET", "views01/guardar_oa.php?id_cobertura="+formp1+"&monto="+monto+
				"&id_proveedor="+id_proveedor+"&fechare="+fechare+"&fecharci="+fecharci+"&horac="+horac+"&enfermedad="+enfermedad+"&decrip="+decrip+"&comenope="+comenope+"&tiposerv="+tiposerv+"&servicio="+servicio+"&contador="+contador+"&proceso="+proceso+"&conexa="+conexa+"&examen1="+examen1+"&honorarios1="+honorarios1+"&idexamen1="+idexamen1+"&examenes="+examenes+"&organo="+organo+'&edoproceso='+edoproceso+'&numpre='+numpre+'&coment1='+coment1+'&donativo='+donativo,true);
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
		else
		{
		alert ("Debes Seleccionar algun examen");
		}
				}
				else
				{	
			alert ("Solo Debe Seleccionar una sola cobertura ");
				}
				}
		}

}
/* **** Fin guardar es para examenes **** */



/* **** Este guardar es para consultas **** */
function guardaroac(){
        var 
tiposerv,fechacitas,fechar,fechac,horac,enfermedad,decrip,comenope,con,cobertura,contador,monto,id_proveedor,formp,contenedor;
		contenedor = document.getElementById('clientes');
		proceso= document.oa.proceso.value;
		contador = document.oa.contador.value;
		id_proveedor = document.oa.proveedor.value;
		servicio = document.oa.servicio.value;
		tiposerv = document.oa.tiposerv.value;
		fechar = document.getElementById("dateField1");
		fechare=fechar.value;
		fechac = document.getElementById("dateField2");
		fecharci=fechac.value;
		horac = document.oa.horac.value;
		monto =document.oa.monto.value;
		enfermedad = document.oa.enfermedad.value;
		decrip = document.oa.decrip.value;
		comenope = document.oa.comenope.value;
		montoh =document.oa.montoh.value;
		montog = document.oa.montog.value;
		montoo = document.oa.montoo.value;
		numpro = document.oa.numpro.value;
		organo= document.oa.organo.value;
		edoproceso= document.oa.edoproceso.value;
		numpre= document.oa.numpre.value;
		donativo= document.oa.donativo.value;
		cobertura="";
        j=0;
	con=0;
	
	if (fechare.value=="" || monto.length==0 || enfermedad.length==0 || id_proveedor.length==0) 
		{
		alert ("Los campos Fecha de Recepcion, Monto, Cuadro Medico, Proveedor son obligatorios ");
		}
		else
		{
			for(var i=0; i<contador; i++)
		{
                j++;
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
		                }
				else
				{
					formp1=formp.value;
				}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas  ");
				}
				else	
				{
				if (i - con==1)
				{
				ajax=nuevoAjax();
				ajax.open("GET", "views01/guardar_oa.php?id_cobertura="+formp1+"&monto="+monto+
				"&id_proveedor="+id_proveedor+"&fechare="+fechare+"&fecharci="+fecharci+"&horac="+horac+"&enfermedad="+enfermedad+"&decrip="+decrip+"&comenope="+comenope+"&tiposerv="+tiposerv+"&contador="+contador+"&proceso="+proceso+"&servicio="+servicio+"&montoh="+montoh+"&montog="+montog+"&montoo="+montoo+"&numpro="+numpro+"&organo="+organo+'&edoproceso='+edoproceso+"&numpre="+numpre+'&donativo='+donativo,true);
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
				else
				{	
			alert ("Solo Debe Seleccionar una sola cobertura  ");
				}
				}
		}
	
}
/* **** Fin guardar es para consultas **** */


/* **** Este guardar es para reembolso ambulatorio y cirugia ambulatoria **** */
function guardarra(){
        var 
tiposerv,fechacitas,fechar,fechac,enfermedad,comenope,con,cobertura,contador,monto,formp,formexa,examenes,contenedor;

        contenedor = document.getElementById('clientes');
		proceso= document.oa.proceso.value;
        contador = document.oa.contador.value;
		conexa = document.oa.conexa.value;
		servicio = document.oa.servicio.value;
		tiposerv = document.oa.tiposerv.value;
		fechar = document.getElementById("dateField1");
		fechare=fechar.value;
		fechac = document.getElementById("dateField2");
		fecharci=fechac.value;
		horac = document.oa.horac.value;
		monto =document.oa.monto.value;
		enfermedad = document.oa.enfermedad.value;
		comenope = document.oa.comenope.value;
		organo= document.oa.organo.value;
		edoproceso= document.oa.edoproceso.value;
		numpre= document.oa.numpre.value;
		donativo= document.oa.donativo.value;
		tp= document.oa.tp.value;
		id_proveedor=0;	
		cobertura="";
		descri1="";
		tiposerv1="";
		nombre1="";
		factura1= "";
		honorarios1= "";
		tc1="";
		fechaci1="";
		fechacf1= "";
		t1= "";
				
        j=0;
		z=0;
	con=0;
	if (fechare.value=="" || monto.length==0 || enfermedad.length==0) {
	
		alert ("Los campos Fecha de Recepcion, Monto, Cuadro Medico son obligatorios ");
		}

		else

		{
			for(var i=0; i<contador; i++)
			{
		j++;
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
                }
				else
				{
					formp1=formp.value;
				}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas  ");
				}
				else	
				{
				if (i - con==1)
				{
			
					if (proceso>1 && edoproceso!=13)
					{
						p=0;
						t=1;
						}
						else
						{
							p=3;
							t=4;
							}
				j=p;
 	for(var i=t; i<conexa; i++) 
	{
	
		j++;
		formexa = document.getElementById("check_"+[j]);
		if(formexa.checked) 
			
		{
			z++;
			descri=document.getElementById("descri_"+[j]);
		 	tiposer=document.getElementById("tiposerv_"+[j]);
			nombre=document.getElementById("nombre_"+[j]);
		 	factura=document.getElementById("factura_"+[j]);
			honorarios=document.getElementById("honorarios_"+[j]);
			
			tc=document.getElementById("tc_"+[j]);
			fechaci=document.getElementById("fechaci_"+[j]);
		 	fechacf=document.getElementById("fechacf_"+[j]);
			t=document.getElementById("t_"+[j]);
			descri1 +="@"+descri.value;
			tiposerv1 +="@"+tiposer.value;
			nombre1 +="@"+nombre.value;
			factura1 +="@"+factura.value;
			honorarios1 +="@"+honorarios.value;
			tc1 +="@"+tc.value;
			fechaci1 +="@"+fechaci.value;
			fechacf1 +="@"+fechacf.value;
			t1 +="@"+t.value;
					
			
		}
		}
			if (z>0)
			{
				ajax=nuevoAjax();
				ajax.open("GET", "views01/guardar_oa.php?id_cobertura="+formp1+"&monto="+monto+
				"&fechare="+fechare+"&fecharci="+fecharci+"&enfermedad="+enfermedad+"&comenope="+comenope+"&tiposerv1="+tiposerv1+"&contador="+contador+"&conexa="+conexa+"&descri1="+descri1+"&honorarios1="+honorarios1+"&servicio="+servicio+'&id_proveedor='+id_proveedor+'&factura1='+factura1+'&nombre1='+nombre1+"&proceso="+proceso+"&organo="+organo+'&edoproceso='+edoproceso+'&numpre='+numpre+'&tp='+tp+'&donativo='+donativo+'&tc1='+tc1+'&fechaci1='+fechaci1+'&fechacf1='+fechacf1+'&t1='+t1,true);
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
		else
		{
		alert ("Debes Seleccionar algun Tipo de Gasto");
		}
				}
				else
				{	
			alert ("Solo Debe Seleccionar una sola cobertura  ");
				}
				}
		}
	
}
/* **** Fin guardar para reembolso ambulatorio **** */


/* **** guardar procesos en espera que fue retomado **** */
function pespera(){
        var 
contenedor;

	    contenedor = document.getElementById('clientes');
		contador = document.oa.contador.value;
		servicio = document.oa.servicio.value;
		fechar = document.getElementById("dateField1");
		fechare=fechar.value;
		fechac = document.getElementById("dateField2");
		fecharci=fechac.value;
		monto =document.oa.monto.value;
		enfermedad = document.oa.enfermedad.value;
		comenope = document.oa.comenope.value;
		cobertura="";
        j=0;
	con=0;
	if (fechare.value=="" || monto.length==0 || enfermedad.length==0 ) 
		{
		alert ("Los campos Fecha de Recepcion, Monto, Cuadro Medico");
		}
		else
		{
			for(var i=0; i<contador; i++)
		{
                j++;
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
		                }
				else
				{
					formp1=formp.value;
				}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas ");
				}
				else	
				{
				if (i - con==1)
				{
				ajax=nuevoAjax();
				ajax.open("GET", "views01/procesoespera.php?id_cobertura="+formp1+"&monto="+monto+"&fechare="+fechare+"&fecharci="+fecharci+"&enfermedad="+enfermedad+"&comenope="+comenope+"&servicio="+servicio,true);
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
				else
				{	
			alert ("Solo Debe Seleccionar una sola cobertura ");
				}
				}
		}
		
}
/* **** Fin guardar procesos en espera que fue retomado**** */


/* **** Este guardar es para emergencia, hospitalizacion gastos varios **** */
function guardareme(){

        var 
tiposerv,fechacitas,fechar,fechac,enfermedad,comenope,con,cobertura,contador,monto,formp,formexa,examenes,contenedor;
		contenedor = document.getElementById('clientes');
		proceso= document.oa.proceso.value;
		contador = document.oa.contador.value;
		conexa = document.oa.conexa.value;
		servicio = document.oa.servicio.value;
		tiposerv = document.oa.tiposerv.value;
		fechar = document.getElementById("dateField1");
		fechare=fechar.value;
		fechac = document.getElementById("dateField2");
		fecharci=fechac.value;
		monto =document.oa.monto.value;

		enfermedad = document.oa.enfermedad.value;
		comenope = document.oa.comenope.value;
		organo= document.oa.organo.value;
		edoproceso= document.oa.edoproceso.value;
		numpre= document.oa.numpre.value;
		donativo= document.oa.donativo.value;
		horac = document.oa.horac.value;

id_proveedor=0;	

        	cobertura="";
		descri1="";
		factor1="";
		nombre1="";
		honorarios1= "";
	j=0;
	z=0;
	con=0;
	if (fechare.value=="" || monto.length==0 || enfermedad.length==0) {
	
		alert ("Los campos Fecha de Recepcion, Monto, Cuadro Medico son obligatorios ");
		}

		else

		{
			for(var i=0; i<contador; i++)
			{
		j++;
		
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
                }
				else
				{
					formp1=formp.value;
				}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas ");
				}
				else	
				{
				if (i - con==1)
				{
					
				
				j=0;
	
 	for(var i=1; i<conexa; i++) 
	{
	
		j++;
		
		formexa = document.getElementById("check_"+[j]);
	
		if(formexa.checked) 
		
		{
			z++;
			descri=document.getElementById("descri_"+[j]);
		 	factor=document.getElementById("factor_"+[j]);
			nombre=document.getElementById("nombre_"+[j]);
		 	honorarios=document.getElementById("honorarios_"+[j]);
			descri1 +="@"+descri.value;
			factor1 +="@"+factor.value;
			nombre1 +="@"+nombre.value;
			honorarios1 +="@"+honorarios.value;
			
			
		}
	
		}
					if (z>0)
			{	
				ajax=nuevoAjax();
				ajax.open("GET", "views01/guardar_oa.php?id_cobertura="+formp1+"&monto="+monto+
				"&fechare="+fechare+"&fecharci="+fecharci+"&enfermedad="+enfermedad+"&comenope="+comenope+"&factor1="+factor1+"&contador="+contador+"&conexa="+conexa+"&descri1="+descri1+"&honorarios1="+honorarios1+"&servicio="+servicio+'&id_proveedor='+id_proveedor+'&nombre1='+nombre1+'&tiposerv='+tiposerv+"&proceso="+proceso+"&organo="+organo+'&edoproceso='+edoproceso+'&numpre='+numpre+'&donativo='+donativo+'&horac='+horac,true);
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
		else
		{
		alert ("Debes Seleccionar el o los Gastos");
		}
				}
				else
				{	
			alert ("Solo Debe Seleccionar una sola cobertura ");
				}
				}
		}
			
}
/* **** Fin guardar para emergencia gastos varios **** */

/* **** Fin de Registrar Orden Atencion **** */

/* ****** Registrar Citas Medicas ***** */
function reg_cita(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		reg_cita1();
}

function reg_cita1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/regcitas.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function buscarcedulac(){
        var cedula,contenedor;
			var1 = document.getElementById('buscarcedulac'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('buscarcedulac');
        cedula = document.cita.cedula.value;
		especialidad = document.cita.especialidad.value;
        if(cedula.length==0){
        alert("El Campo cedula es Obligatorio.");
        }

        if(cedula.length>0){ 
                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscarcedulac.php?cedula="+cedula+"&especialidad="+especialidad,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
        }
}

function con_medico(){
        var proceso,id_proveedor,cedula,contenedor;
			var1 = document.getElementById('con_medico'); 
	
        contenedor = document.getElementById('con_medico');
        id_proveedor = document.cita.proveedor.value;
		  proceso = document.cita.proceso.value;
		  cedula = document.cita.cedula.value;  
	    if(id_proveedor.length==0){
        alert("Seleccione un Medico.");
        }           

        if(id_proveedor.length>0){
                ajax=nuevoAjax();   
                ajax.open("GET", "views01/con_medico.php?id_proveedor="+id_proveedor+"&proceso="+proceso+'&cedula='+cedula,true);
                ajax.onreadystatechange=function() {  
                if (ajax.readyState==4) {   
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
					var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}


function con_medico2(){
        var id_proveedor,contenedor;
			var1 = document.getElementById('con_medico2'); 
	
        contenedor = document.getElementById('con_medico2');
        id_proveedor = document.con_morbis.proveedor.value;
		if(id_proveedor.length==0){
        alert("Seleccione un Medico.");
        }           

        if(id_proveedor.length>0){
                ajax=nuevoAjax();   
                ajax.open("GET", "views01/con_medico2.php?id_proveedor="+id_proveedor,true);
                ajax.onreadystatechange=function() {  
                if (ajax.readyState==4) {   
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
					var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}


function con_medico3(){
        var id_proveedor,contenedor;
			var1 = document.getElementById('con_medico3'); 
	
        contenedor = document.getElementById('con_medico3');
        id_proveedor = document.con_morbis.proveedor.value;
		if(id_proveedor.length==0){
        alert("Seleccione un Medico.");
        }           

        if(id_proveedor.length>0){
                ajax=nuevoAjax();   
                ajax.open("GET", "views01/con_medico3.php?id_proveedor="+id_proveedor,true);
                ajax.onreadystatechange=function() {  
                if (ajax.readyState==4) {   
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
					var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}


function verificar_fecha(){
        var contenedor;
			var1 = document.getElementById('verificar_fecha'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('verificar_fecha');
		id_proveedor = document.cita.proveedor.value;
      	fechacitas = document.getElementById("dateField1");
	fechacita=fechacitas.value;
                   ajax=nuevoAjax();
                ajax.open("GET", "views01/verificar_fecha.php?fechacita="+fechacita+"&id_proveedor="+id_proveedor,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
}






function con_paciente(){
        var cedula,contenedor;
			var1 = document.getElementById('con_medico'); 
		
        contenedor = document.getElementById('con_medico');
        cedula = document.cita.cedula.value;
                if(cedula.length==0){
        alert("El Campo cedula, es Obligatorio.");
        }
        else
        {
        if(cedula.length>0){
                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscarcitasp.php?cedula="+cedula,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
        }
}


function guardar_cita(){
        var tlf1,tlf2,tipcon,fechacitas,fecha,con,cobertura,contador,monto,id_proveedor,formp,comenope,contenedor;
	contenedor = document.getElementById('clientes');
	contador = document.cita.contador.value;
	id_proveedor = document.cita.proveedor.value;
	monto = document.cita.monto.value; 
	tipcon = document.cita.tipcon.value;
	fechacitas = document.getElementById("dateField1");
	fechacita=fechacitas.value;
	fecha= document.cita.fecha.value;
	tlf1= document.cita.tlf1.value;
	tlf2= document.cita.tlf2.value;
	comenope= document.cita.comenope.value;
   
	cobertura="";
        j=0;
	con=0;
	
	if (fechacitas.value=="" || fechacitas.value<fecha) {
		alert ("El campo Fecha de cita es obligatorio la fecha no puede menor al dia actual");
		}
		else
		{
			for(var i=0; i<contador; i++)
		{
                j++;
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
		                }
				else
				{
					formp1=formp.value;
				}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas  ");
				}
				else	
				{
				if (i - con==1)
				{

					
				ajax=nuevoAjax();
				ajax.open("GET", "views01/guardar_cita.php?id_cobertura="+formp1+"&monto="+monto+
				"&id_proveedor="+id_proveedor+"&fechacita="+fechacita+"&tipcon="+tipcon+"&tlf1="+tlf1+"&tlf2="+tlf2+"&comenope="+comenope,true);
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
			
			else
			{	
			alert ("Solo Debe Seleccionar una sola cobertura ");
alert (con);
			}
		}
	
	}
			
}

/* **** fin de registrar citas medicas **** */


/* **** modificar citas **** */

function act_cita(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		act_cita1();
}

function act_cita1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/actcitas.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function buscarproceso(){
        var proceso,contenedor;
		
		contenedor = document.getElementById('buscarproceso');
        proceso = document.cita.proceso.value;
		if(proceso.length==0){
        alert("El Campo numero de orden es Obligatorio.");
        }

        if(proceso.length>0){ 
                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscarproceso.php?proceso="+proceso,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('buscarproceso'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}



function actualizar_cita(){
        var proceso,tipcon,fechacitas,fecha,con,cobertura,id_cobertura_t_b,contador,monto,id_proveedor,formp,comenope,contenedor;
	contenedor = document.getElementById('clientes');
	contador = document.cita.contador.value;
	proceso= document.cita.proceso.value;
	id_proveedor = document.cita.proveedor.value;
	monto = document.cita.monto.value; 
	tipcon = document.cita.tipcon.value;
	fechacitas = document.getElementById("dateField1");
	fechacita=fechacitas.value;
	fecha= document.cita.fecha.value;
	id_cobertura_t_b= document.cita.id_cobertura_t_b.value;
	diagnostico= document.cita.diagnostico.value;
	comenope= document.cita.comenope.value;
        cobertura=" ";
        j=0;
	con=0;
	if (fechacitas.value=="") {
		alert ("Debe Seleccionar una Fecha de Cita igual o mayor al dia de hoy ");
		}
		else
		{
			for(var i=0; i<contador; i++)
			{
                j++;
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
                }
				else
				{
					formp1=formp.value;
					}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas ");
				}
				else	
				{
				if (i - con==1)
				{
					
				ajax=nuevoAjax();
				ajax.open("GET", "views01/actualizar_cita.php?id_cobertura="+formp1+"&monto="+monto+
				"&id_proveedor="+id_proveedor+"&fechacita="+fechacita+"&tipcon="+tipcon+"&id_cobertura_t_b="+id_cobertura_t_b+"&proceso="+proceso+"&diagnostico="+diagnostico+"&comenope="+comenope,true);
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
			else
			{	
			alert ("Solo Debe Seleccionar una sola cobertura ");
			}
		}
		
	}
			
}

/* **** fin de modificar citas **** */

/* **** consultar citas paciente **** */

function con_citas(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		con_citas1();
}

function con_citas1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/concitas.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function buscarcitas(){
        var cedula,contenedor;
		var1 = document.getElementById('buscarcitas'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('buscarcitas');
        cedula = document.ccita.cedula.value;
		fechasinicio = document.getElementById("dateField1");
		fechasfin = document.getElementById("dateField2");
        fechainicio= fechasinicio.value;
		fechafin=fechasfin.value;
		if(cedula.length==0 || fechafin.value=="" || fechainicio.value==""){
        alert("El Campo cedula, fecha inicio y fecha fin es Obligatorio.");
        }
	else
	{
        if(cedula.length>0){ 
                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscarcitas.php?cedula="+cedula+"&fechainicio="+fechainicio+"&fechafin="+fechafin,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
        }
	}	
}


/* **** fin de consultar citas paciente **** */

/* **** consultar morbilidad **** */

function con_morbi(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		con_morbi1();
}

function con_morbi1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/con_morbis.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function buscarmorbis(){
	
        var proveedor,contenedor;
				var1 = document.getElementById('buscarmorbis'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('buscarmorbis');
        proveedor = document.con_morbis.id_proveedorp.value;
		proveedorc = document.con_morbis.id_proveedorc.value;
		fechasinicio = document.getElementById("dateField1");
		fechasfin = document.getElementById("dateField2");
        fechainicio= fechasinicio.value;
		fechafin=fechasfin.value;

		if(proveedor.length==0 || fechafin.value=="" || fechainicio.value==""){
        alert("El Campo proveedor, fecha inicio y fecha fin es Obligatorio.");
        }
	else
	{
        if(proveedor.length>0){ 
                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscarmorbi.php?proveedor="+proveedor+"&proveedorc="+proveedorc+"&fechainicio="+fechainicio+"&fechafin="+fechafin,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
        }
	}	
}



/* **** fin consultar morbilidad **** */



/* **** cambiar edo orden **** */

function cam_edo_ord(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		cam_edo_ord1();
}

function cam_edo_ord1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/cam_edo_ord.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function bcam_edo_ord(){
        var proceso,contenedor;

                contenedor = document.getElementById('bcam_edo_ord');
        proceso = document.anularord.proceso.value;
                if(proceso.length==0){
        alert("El Campo numero de orden es Obligatorio.");
        }
        if(proceso.length>0){     
	                ajax=nuevoAjax();
                ajax.open("GET", "views01/bcam_edo_ord.php?proceso="+proceso,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('bcam_edo_ord'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}


function camb_edo(){
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



/* **** fin de cambiar edo orden **** */




/* **** anular orden **** */

function anu_ords(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		anu_ords1();
}

function anu_ords1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/anuord.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function buscaranular(){
        var proceso,contenedor;
		
                contenedor = document.getElementById('buscaranular');
        proceso = document.anularord.proceso.value;
                if(proceso.length==0){
        alert("El Campo numero de orden es Obligatorio.");
        }
        if(proceso.length>0){     
                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscaranular.php?proceso="+proceso,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('buscaranular'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}


function anular_orden(){
        var cooperador,proceso,contenedor;
        contenedor = document.getElementById('clientes');
        proceso= document.anularord.proceso.value;
	cooperador= document.anularord.cooperador.value;
			ajax=nuevoAjax();
				ajax.open("GET", "views01/anular_orden.php?proceso="+proceso+"&cooperador="+cooperador,true);
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



/* **** fin de anular orden **** */


/* **** carta de rechazo **** */

function carta_re(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		carta_re1();
}

function carta_re1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/carta_re.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function buscarta_re(){
        var proceso,contenedor;
		
                contenedor = document.getElementById('buscarta_re');
        proceso = document.anularord.proceso.value;
                if(proceso.length==0){
        alert("El Campo numero de orden es Obligatorio.");
        }
        if(proceso.length>0){     
                ajax=nuevoAjax();
                ajax.open("GET", "views01/buscarta_re.php?proceso="+proceso,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('buscarta_re'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}


function carta_rechazo(){
        var cooperador,proceso,contenedor;
     contenedor = document.getElementById('clientes');
    proceso= document.anularord.proceso.value;
	cooperador= document.anularord.cooperador.value;
	conexa= document.anularord.conexa.value;
	tipo_rechazo= document.anularord.tipo_rechazo.value;

	tipo_paragrafo1="";
	paragrafo1="";
			j=0;

			for(var i=0; i<conexa; i++) 
				
			{
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
					
				{
				tipo_paragrafo=document.getElementById("tipo_paragrafo_"+[j]);
				paragrafo=document.getElementById("paragrafo_"+[j]);
							
				tipo_paragrafo1 +="@"+tipo_paragrafo.value;
				paragrafo1 +="@"+paragrafo.value;
			
				
				}
			}

			ajax=nuevoAjax();
				ajax.open("GET", "views01/procesarcarta.php?proceso="+proceso+"&cooperador="+cooperador+"&tipo_paragrafo1="+tipo_paragrafo1+"&paragrafo1="+paragrafo1+"&tipo_rechazo="+tipo_rechazo+"&conexa="+conexa,true);
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



/* **** fin de carta de rechazo **** */

/* **** Pago unico de gracia **** */

function p_uni_gra(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		p_uni_gra1();
}

function p_uni_gra1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/p_uni_gra.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function bus_p_ug(){
        var proceso,contenedor;
		
                contenedor = document.getElementById('bus_p_ug');
        proceso = document.anularord.proceso.value;
                if(proceso.length==0){
        alert("El Campo numero de orden es Obligatorio.");
        }
        if(proceso.length>0){     
                ajax=nuevoAjax();
                ajax.open("GET", "views01/bus_p_ugra.php?proceso="+proceso,true);
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('bus_p_ug'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        }
}

function pago_gracia(){
        var proceso,contenedor;
     contenedor = document.getElementById('clientes');
    proceso= document.anularord.proceso.value;
	cooperador= document.anularord.cooperador.value;
	conexa= document.anularord.conexa.value;
	con1= document.anularord.con1.value;
	porcentaje= document.anularord.porcentaje.value;
	decrips1="";
	preforma1="";
	tipo_paragrafo1="";
	paragrafo1="";

			j=0;
			j2=0;
			
			for(var i=0; i<con1; i++) 
				
			{
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
					
				{
				decrips=document.getElementById("decrips_"+[j]);
				preforma=document.getElementById("preforma_"+[j]);
							
				decrips1 +="@"+decrips.value;
				preforma1 +="@"+preforma.value;
			
				
				}
				
			}


			for(var i=0; i<conexa; i++) 
				
			{
				j2++;
				formexa2 = document.getElementById("check2_"+[j2]);
				if(formexa2.checked) 
					
				{
					tipo_paragrafo=document.getElementById("tipo_paragrafo_"+[j2]);
				paragrafo=document.getElementById("paragrafo_"+[j2]);
							
				tipo_paragrafo1 +="@"+tipo_paragrafo.value;
				paragrafo1 +="@"+paragrafo.value;
				}
			}
				

			ajax=nuevoAjax();
				ajax.open("GET", "views01/procesar_pago.php?proceso="+proceso+"&tipo_paragrafo1="+tipo_paragrafo1+"&paragrafo1="+paragrafo1+"&conexa="+conexa+"&decrips1="+decrips1+"&preforma1="+preforma1+"&con1="+con1+"&porcentaje="+porcentaje,true);
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





/* **** fin de Pago unico de gracia **** */







/* **** Actualizar Cliente en Orden **** */ 
function act_cli_ord(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		act_cli_ord1();
}

function act_cli_ord1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/act_cli_ord.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function act_cli_ord2(){
        var contenedor;
		
        contenedor = document.getElementById('actcliord');
      	proceso= document.act_cliord.proceso.value;
		
			if (proceso.length==0) 
	{
	
		alert ("El Campo Numero de Orden es Obligatorio ");
	}

		else
		{
		
		
			    ajax=nuevoAjax();
                ajax.open("GET", "views01/act_cli_ord2.php?proceso="+proceso,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('actcliord'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		}
}

function bus_cli_nu(){
        var contenedor;
		
        contenedor = document.getElementById('bus_cli_nu');
      	proceso= document.act_cliord.proceso.value;
		cedula= document.act_cliord.cedula.value;
		
		
				if (cedula.length==0) 
	{
	
		alert ("El Campo Numero de Cedula es obligatorio ");
	}

		else
		{

			
                ajax=nuevoAjax();
                ajax.open("GET", "views01/bus_cli_nu.php?proceso="+proceso+'&cedula='+cedula,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('bus_cli_nu'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		}
}

function gua_act_cliord(){
        var contenedor;
        contenedor = document.getElementById('clientes');
		contador = document.act_cliord.contador.value
		proceso= document.act_cliord.proceso.value;
		monto= document.act_cliord.monto.value;
		cobertura=" ";
		
		j=0;
		con=0;
        	for(var i=0; i<contador; i++)
			{
                j++;
                formp = document.getElementById("cobertura_"+[j]);
                if(formp.value=="")
				{
			con++;
                }
				else
				{
					formp1=formp.value;
					}
			}
				if (i - con==0) {
				alert ("Debe Seleccionar una de las coberturas de Gastos Ambulatorios ");
				}
				else	
				{
				if (i - con==1)
				{
				ajax=nuevoAjax();
                ajax.open("GET", "views01/gua_act_cliord.php?formp="+formp1+"&proceso="+proceso+"&monto="+monto,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
				}
				else
			{	
			alert ("Solo Debe Seleccionar una sola cobertura de Gastos Ambulatorios ");
			}
			}
			
}



/* **** Fin Actualizar Cliente en Orden  **** */

/* **** Colocar una Orden Activa en Espera **** */ 
function col_espera(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		col_espera1();
}

function col_espera1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/col_espera.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function col_espera2(){
        var contenedor;
		
        contenedor = document.getElementById('colespera');
      	proceso= document.espera.proceso.value;
		if (proceso.length==0) 
	{
	
		alert ("El Campo Numero de Orden es Obligatorio ");
	}

		else
		{
		
			    ajax=nuevoAjax();
                ajax.open("GET", "views01/col_espera2.php?proceso="+proceso,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('colespera'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		}
}

function gua_ord_espera(){
        var contenedor;
		contenedor = document.getElementById('clientes');
		proceso= document.espera.proceso.value;
		cobertura=" ";
		        ajax=nuevoAjax();
                ajax.open("GET", "views01/gua_ord_espera.php?proceso="+proceso,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
				var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
}



/* **** Fin Colocar Orden en Espera  **** */



/* **** Retomar una Orden de Espera **** */ 
function ret_ord_espera(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		ret_espera1();
}

function ret_espera1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/ret_ord_esp.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function ret_espera2(){
        var contenedor;
	
        contenedor = document.getElementById('retespera');
      	proceso= document.oa.proceso.value;
			if (proceso.length==0) 
	{
	
		alert ("El Campo Numero de Orden es Obligatorio ");
	}

		else
		{
		
		
			    ajax=nuevoAjax();
                ajax.open("GET", "views01/ret_ord_esp1.php?proceso="+proceso,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
					 var1 = document.getElementById('retespera'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		}
}


function ret_espera3(){
        var contenedor;
		 var1 = document.getElementById('retespera1'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
        contenedor = document.getElementById('retespera1');
      	proceso= document.oa.proceso.value;
		servicio= document.oa.servicio.value;
		tiposerv= document.oa.tiposerv.value;
		comenope1= document.oa.comenope1.value;
		fecharec= document.oa.fecharec.value;
			    ajax=nuevoAjax();
                ajax.open("GET", "views01/ret_ord_esp2.php?proceso="+proceso+'&servicio='+servicio+'&tiposerv='+tiposerv+'&comenope1='+comenope1+'&fecharec='+fecharec,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
		
}

/* **** Fin de Retomar Orden en Espera  **** */

/* **** Actualizar una Orden  **** */ 
function act_orden(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		act_orden1();
}

function act_orden1(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/act_orden.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function act_orden2(){
        var contenedor;
		  contenedor = document.getElementById('actorden');
		  	proceso= document.oa.proceso.value;
		if (proceso.length==0) 
	{
	
		alert ("El Campo Numero de Orden es Obligatorio ");
	}

		else
		{
		
		
  
			    ajax=nuevoAjax();
                ajax.open("GET", "views01/act_orden1.php?proceso="+proceso,true);  
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                        contenedor.innerHTML = ajax.responseText
                        }
                }
                ajax.send(null);
					var1 = document.getElementById('actorden'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
      }
}


function gua_act_ord(){
         var contenedor;
		contenedor = document.getElementById('clientes');
		proceso= document.oa.proceso.value;
		servicio = document.oa.servicio.value;
		tiposerv = document.oa.tiposerv.value;
		fechar = document.getElementById("dateField1");
		fechare=fechar.value;
		fechac = document.getElementById("dateField2");
		fecharci=fechac.value;
		fechaf = document.getElementById("dateField3");
		fecharfi=fechaf.value;
		horac = document.oa.horac.value;
		facturaf = document.oa.facturaf.value;
		controlf = document.oa.controlf.value;
		clave = document.oa.clave.value;
		monto =document.oa.monto.value;
		montor =document.oa.montor.value;
		enfermedad = document.oa.enfermedad.value;
		comenope = document.oa.comenope.value;
		comenger = document.oa.comenger.value;
		comenmed = document.oa.comenmed.value;
		id_proveedor=document.oa.id_proveedor.value;
	
		conexa = document.oa.conexa.value;
		estado_proceso = document.oa.estado_proceso.value;

		fechapi = document.getElementById("dateField4");
		fechap=fechapi.value;
			
		nu_planilla = document.oa.nu_planilla.value;
        	
			descri1="";
			preforma1="";
			nombre1="";
			honorariosr1= "";
			honorarios1= "";
			idgasto1="";
			idorgano1="";
			fcreado1="";
			hcreado1= "";
			idcobertura1= "";
			idtipos1="";
			idservicio1="";
			retencion1="";
			unidades1="";
			fechacon1="";
			fechaconi1="";
			continuo1="";
		

	if (fechare.value=="" || monto.length==0 || enfermedad.length==0) 
	{
	
		alert ("Los campos Fecha de Recepcion, Monto, Cuadro Medico son obligatorios ");
	}

		else
		{
			j=0;
	
			for(var i=0; i<conexa; i++) 
			{
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
				{
				descri=document.getElementById("desc_"+[j]);
				preforma=document.getElementById("preforma_"+[j]);
				nombre=document.getElementById("nom_"+[j]);
				honorariosr= document.getElementById("honorariosr_"+[j]);
				honorarios= document.getElementById("honorarios_"+[j]);
				idgasto=document.getElementById("idgasto_"+[j]);
				idorgano=document.getElementById("idorgano_"+[j]);
				fcreado=document.getElementById("fcreado_"+[j]);
				hcreado= document.getElementById("hcreado_"+[j]);
				idcobertura= document.getElementById("idcobertura_"+[j]);
				idtipos=document.getElementById("idtipos_"+[j]);
				idservicio= document.getElementById("idservicio_"+[j]);
				retencion= document.getElementById("retencion_"+[j]);
				unidades= document.getElementById("unidades_"+[j]);
				fechacon= document.getElementById("fechacon_"+[j]);
				fechaconi= document.getElementById("fechaconi_"+[j]);
				continuo= document.getElementById("continuo_"+[j]);
		
				
				descri1 +="@"+descri.value;
				preforma1 +="@"+preforma.value;
				nombre1 +="@"+nombre.value;
				honorariosr1 +="@"+honorariosr.value;
				honorarios1 +="@"+honorarios.value;
				idgasto1 +="@"+idgasto.value;
				idorgano1 +="@"+idorgano.value;
				fcreado1 +="@"+fcreado.value;
				hcreado1 +="@"+hcreado.value;
				idcobertura1 +="@"+idcobertura.value;
				idtipos1 +="@"+idtipos.value;
				idservicio1 +="@"+idservicio.value;
				retencion1 +="@"+retencion.value;
				unidades1 +="@"+unidades.value;
				fechacon1 +="@"+fechacon.value;
				fechaconi1 +="@"+fechaconi.value;
				continuo1 +="@"+continuo.value;
				}
			}
		
				ajax=nuevoAjax();
				ajax.open("GET", "views01/gua_act_ord.php?fechare="+fechare+"&fecharci="+fecharci+"&fecharfi="+fecharfi+"&enfermedad="+enfermedad+"&comenope="+comenope+"&comenger="+comenger+"&comenmed="+comenmed+"&preforma1="+preforma1+"&conexa="+conexa+"&honorariosr1="+honorariosr1+"&descri1="+descri1+"&honorarios1="+honorarios1+"&servicio="+servicio+"&id_proveedor="+id_proveedor+"&nombre1="+nombre1+"&tiposerv="+tiposerv+"&proceso="+proceso+"&facturaf="+facturaf+"&controlf="+controlf+"&clave="+clave+"&horac="+horac+"&estado_proceso="+estado_proceso+"&idgasto1="+idgasto1+"&idorgano1="+idorgano1+"&fcreado"+fcreado+"&hcreado="+hcreado+"&idcobertura1="+idcobertura1+"&idtipos1="+idtipos1+"&idservicio1="+idservicio1+"&retencion1="+retencion1+"&fechap="+fechap+"&nu_planilla="+nu_planilla+"&monto="+monto+"&unidades1="+unidades1+"&fechacon1="+fechacon1+"&continuo1="+continuo1+"&fechaconi1="+fechaconi1,true);
		
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
}

/* **** Fin de Actualizar Orden  **** */




/* ****** Registrar o modificar baremos ***** */
function reg_baremos(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		reg_barem();
}

function reg_barem(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_barem.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function reg_baremo1(){
	var contenedor;
	contenedor = document.getElementById('reg_baremo1');
	tbaremo = document.baremo.tbaremo.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_baremo.php?tbaremo="+tbaremo,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function reg_baremo2(){

	var contenedor;
	var1 = document.getElementById('reg_baremo2'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	contenedor = document.getElementById('reg_baremo2');
	baremo = document.baremo.baremo.value;
	tbaremo = document.baremo.tbaremo.value;

	 if(baremo.length==0)
		{
        alert("Debe Seleccionar un tipo de Baremo.");
		}
		else
		{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/busbaremo_exa.php?baremo="+baremo+"&tbaremo="+tbaremo,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
	}
}

function reg_baremo3(){

	var contenedor;
	var1 = document.getElementById('reg_baremo3'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	contenedor = document.getElementById('reg_baremo3');
	baremo = document.baremo.baremo.value;

	 if(baremo.length==0)
		{
        alert("Debe Seleccionar un tipo de Baremo.");
		}
		else
		{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_baremo3.php?baremo="+baremo,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
	}
}

function reg_baremo4(){

	var contenedor;
	var1 = document.getElementById('reg_baremo2'); 
		tbaremo = document.baremo.tbaremo.value;
	baremo = document.baremo.baremo.value;
	examen = document.baremo.examen.value;
	monto = document.baremo.monto.value;
	monto2 = document.baremo.monto2.value;

	control=1;
	 if(baremo.length==0)
		{
        alert("Debe Seleccionar un tipo de Baremo.");
		}
		else
		{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/busbaremo_exa.php?baremo="+baremo+"&examen="+examen+"&monto="+monto+"&monto2="+monto2+"&control="+control+"&tbaremo="+tbaremo,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
	var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
	contenedor = document.getElementById('reg_baremo2');
	}
	
}


function act_baremo(){
         var contenedor;
		contenedor = document.getElementById('clientes');
		baremo= document.baremo.baremo.value;
		conexa= document.baremo.conexa.value;
tbaremo = document.baremo.tbaremo.value;
			id_imagenologia1="";
			imagenologia1="";
			honorarios1="";
			honorarios2="";
					

	if (baremo.length==0) 
	{
	
		alert ("Debe Seleccionar un tipo de Baremo.");
	}

		else
		{
			
			j=0;
	
			for(var i=0; i<conexa; i++) 
			{
			
				j++;
				formexa = document.getElementById("check_"+[j]);
				if(formexa.checked) 
				{
						
				id_imagenologia=document.getElementById("id_imagenologia_bi_"+[j]);
				imagenologia=document.getElementById("imagenologia_bi_"+[j]);
				honorarios=document.getElementById("honorarios_"+[j]);
				honorarios_pri=document.getElementById("honorarios_pri_"+[j]);
				
		
				
				id_imagenologia1 +="@"+id_imagenologia.value;
				imagenologia1 +="@"+imagenologia.value;
				honorarios1 +="@"+honorarios.value;
				honorarios2 +="@"+honorarios_pri.value;
				
				}
			}
		
				ajax=nuevoAjax();
				ajax.open("post", "views07/act_baremo.php",true);
		
                ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {      
                contenedor.innerHTML = ajax.responseText
                }
			}
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			ajax.send("id_imagenologia1="+id_imagenologia1+"&imagenologia1="+imagenologia1+
"&honorarios1="+honorarios1+"&honorarios2="+honorarios2+"&baremo="+baremo+"&conexa="+conexa+"&tbaremo="+tbaremo)
				var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		}
}









/* **** fin de baremo **** */





	
function reg_perusuario(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		buscar_permisos();
	}

function buscar_permisos(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/permisos.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function buscar_permisos2(){
	var login,contenedor;
	contenedor = document.getElementById('permisos2');
	login = document.permiso.login.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/permisos2.php?login="+login,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function repermisos(){
	var formp,s,t,id_modulo,id_moduloo,usuario,modulo,contenedor;
	contenedor = document.getElementById('clientes');
	usuario = document.permiso.usuario.value;
	modulo = document.permiso.modulo.value;
	id_modulo=" ";
	j=0;
 	for(var i=0; i<modulo; i++) 
	{
		j++;
		formp = document.getElementById("check_"+[j]);
		if(formp.checked) 
		{
			id_moduloo=document.getElementById("modulo_"+[j]);
			id_modulo +="@"+id_moduloo.value;
		}
		}
		
ajax=nuevoAjax();
		ajax.open("GET", "views07/permisos3.php?usuario="+usuario+"&modulo="+modulo+"&id_modulo="+id_modulo,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function ir_principal(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/principal.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
/******Modulos de entes****/
/* **** Registrar entes **** */

function reg_entes(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views02/reg_entes.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}
/* **** fin registrar entes **** */

/* **** Registrar polizas **** */

function reg_polizas(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views02/reg_polizas.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}
/* **** fin registrar polizas **** */
/***** Fin de los modulos de entes ******/

/* **** Registrar factura **** */

function reg_factura(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views04/reg_facturas.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}



/* **** fin registrar facturas **** */

/* **** ver y modificar factura factura **** */

function ver_factura(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views04/ver_facturas.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}



/* **** fin de ver o modificar facturas **** */

/* **** crear cheques **** */

function che_reembolso(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views04/che_reembolso.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function bus_cheque(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views04/bus_che_rec.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function reg_che_prov(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views04/reg_che_prov.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


/* **** fin crear cheques **** */

/* **** control de fechas para los medicos de la nomina **** */

function reg_fechas(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views07/reg_fechas.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}
/* **** fin control de fechas **** */



/* **** Consultar Anexos**** */

function con_anexos(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views08/con_anexos.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}



/* **** fin consultar anexos **** */

/* **** Reportes de Carlos Ivan Gastos de Entes **** */
function rep_gas_ent(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views06/rep_gas_ent.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function rep_gasser_ent(){
        var contenedor;
	contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
	ajax.open("GET", "views06/rep_gasser_ent.php",true);
	ajax.onreadystatechange=function() {
	   if (ajax.readyState==4){
	      contenedor.innerHTML = ajax.responseText
	      }
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function rep_invs(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/rep_invs.php",true);
        ajax.onreadystatechange=function() {
           if (ajax.readyState==4){
              contenedor.innerHTML = ajax.responseText
              }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}

function rep_factura(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/rep_factura.php",true);
        ajax.onreadystatechange=function() {
           if (ajax.readyState==4){
              contenedor.innerHTML = ajax.responseText
              }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}

function rep_ressocial(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/rep_ressocial.php",true);
        ajax.onreadystatechange=function() {
           if (ajax.readyState==4){
              contenedor.innerHTML = ajax.responseText
              }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}

function rep_relrec(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/rep_rel_rec.php",true);
        ajax.onreadystatechange=function() {
           if (ajax.readyState==4){
              contenedor.innerHTML = ajax.responseText
              }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}

function rep_rem_esp(){
        var contenedor;
        contenedor = document.getElementById('clientes');
        ajax=nuevoAjax();
        ajax.open("GET", "views06/rep_reem_esp.php",true);
        ajax.onreadystatechange=function() {
           if (ajax.readyState==4){
              contenedor.innerHTML = ajax.responseText
              }
        }
        ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajax.send(null)
}

/* **** Fin Reportes de Carlos Ivan Gastos de Entes **** */

/* **** Manual de ayuda rayza **** */
function manual_ayuda(){
 url='views08/ayuda/inicio.html'
	imprimir(url);
}

/* **** Manual de ayuda rayza **** */

 /* A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Copyright (C) Paul Johnston 1999 - 2000.
 * Updated by Greg Holt 2000 - 2001.
 * See http://pajhome.org.uk/site/legal.html for details.
 */

/*
 * Convert a 32-bit number to a hex string with ls-byte first
 */
var hex_chr = "0123456789abcdef";
function rhex(num)
{
  str = "";
  for(j = 0; j <= 3; j++)
    str += hex_chr.charAt((num >> (j * 8 + 4)) & 0x0F) +
           hex_chr.charAt((num >> (j * 8)) & 0x0F);
  return str;
}

/*
 * Convert a string to a sequence of 16-word blocks, stored as an array.
 * Append padding bits and the length, as described in the MD5 standard.
 */
function str2blks_MD5(str)
{
  nblk = ((str.length + 8) >> 6) + 1;
  blks = new Array(nblk * 16);
  for(i = 0; i < nblk * 16; i++) blks[i] = 0;
  for(i = 0; i < str.length; i++)
    blks[i >> 2] |= str.charCodeAt(i) << ((i % 4) * 8);
  blks[i >> 2] |= 0x80 << ((i % 4) * 8);
  blks[nblk * 16 - 2] = str.length * 8;
  return blks;
}

/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally 
 * to work around bugs in some JS interpreters.
 */
function add(x, y)
{
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

/*
 * Bitwise rotate a 32-bit number to the left
 */
function rol(num, cnt)
{
  return (num << cnt) | (num >>> (32 - cnt));
}

/*
 * These functions implement the basic operation for each round of the
 * algorithm.
 */
function cmn(q, a, b, x, s, t)
{
  return add(rol(add(add(a, q), add(x, t)), s), b);
}
function ff(a, b, c, d, x, s, t)
{
  return cmn((b & c) | ((~b) & d), a, b, x, s, t);
}
function gg(a, b, c, d, x, s, t)
{
  return cmn((b & d) | (c & (~d)), a, b, x, s, t);
}
function hh(a, b, c, d, x, s, t)
{
  return cmn(b ^ c ^ d, a, b, x, s, t);
}
function ii(a, b, c, d, x, s, t)
{
  return cmn(c ^ (b | (~d)), a, b, x, s, t);
}

/*
 * Take a string and return the hex representation of its MD5.
 */
function calcMD5(str)
{
  x = str2blks_MD5(str);
  a =  1732584193;
  b = -271733879;
  c = -1732584194;
  d =  271733878;

  for(i = 0; i < x.length; i += 16)
  {
    olda = a;
    oldb = b;
    oldc = c;
    oldd = d;

    a = ff(a, b, c, d, x[i+ 0], 7 , -680876936);
    d = ff(d, a, b, c, x[i+ 1], 12, -389564586);
    c = ff(c, d, a, b, x[i+ 2], 17,  606105819);
    b = ff(b, c, d, a, x[i+ 3], 22, -1044525330);
    a = ff(a, b, c, d, x[i+ 4], 7 , -176418897);
    d = ff(d, a, b, c, x[i+ 5], 12,  1200080426);
    c = ff(c, d, a, b, x[i+ 6], 17, -1473231341);
    b = ff(b, c, d, a, x[i+ 7], 22, -45705983);
    a = ff(a, b, c, d, x[i+ 8], 7 ,  1770035416);
    d = ff(d, a, b, c, x[i+ 9], 12, -1958414417);
    c = ff(c, d, a, b, x[i+10], 17, -42063);
    b = ff(b, c, d, a, x[i+11], 22, -1990404162);
    a = ff(a, b, c, d, x[i+12], 7 ,  1804603682);
    d = ff(d, a, b, c, x[i+13], 12, -40341101);
    c = ff(c, d, a, b, x[i+14], 17, -1502002290);
    b = ff(b, c, d, a, x[i+15], 22,  1236535329);    

    a = gg(a, b, c, d, x[i+ 1], 5 , -165796510);
    d = gg(d, a, b, c, x[i+ 6], 9 , -1069501632);
    c = gg(c, d, a, b, x[i+11], 14,  643717713);
    b = gg(b, c, d, a, x[i+ 0], 20, -373897302);
    a = gg(a, b, c, d, x[i+ 5], 5 , -701558691);
    d = gg(d, a, b, c, x[i+10], 9 ,  38016083);
    c = gg(c, d, a, b, x[i+15], 14, -660478335);
    b = gg(b, c, d, a, x[i+ 4], 20, -405537848);
    a = gg(a, b, c, d, x[i+ 9], 5 ,  568446438);
    d = gg(d, a, b, c, x[i+14], 9 , -1019803690);
    c = gg(c, d, a, b, x[i+ 3], 14, -187363961);
    b = gg(b, c, d, a, x[i+ 8], 20,  1163531501);
    a = gg(a, b, c, d, x[i+13], 5 , -1444681467);
    d = gg(d, a, b, c, x[i+ 2], 9 , -51403784);
    c = gg(c, d, a, b, x[i+ 7], 14,  1735328473);
    b = gg(b, c, d, a, x[i+12], 20, -1926607734);
    
    a = hh(a, b, c, d, x[i+ 5], 4 , -378558);
    d = hh(d, a, b, c, x[i+ 8], 11, -2022574463);
    c = hh(c, d, a, b, x[i+11], 16,  1839030562);
    b = hh(b, c, d, a, x[i+14], 23, -35309556);
    a = hh(a, b, c, d, x[i+ 1], 4 , -1530992060);
    d = hh(d, a, b, c, x[i+ 4], 11,  1272893353);
    c = hh(c, d, a, b, x[i+ 7], 16, -155497632);
    b = hh(b, c, d, a, x[i+10], 23, -1094730640);
    a = hh(a, b, c, d, x[i+13], 4 ,  681279174);
    d = hh(d, a, b, c, x[i+ 0], 11, -358537222);
    c = hh(c, d, a, b, x[i+ 3], 16, -722521979);
    b = hh(b, c, d, a, x[i+ 6], 23,  76029189);
    a = hh(a, b, c, d, x[i+ 9], 4 , -640364487);
    d = hh(d, a, b, c, x[i+12], 11, -421815835);
    c = hh(c, d, a, b, x[i+15], 16,  530742520);
    b = hh(b, c, d, a, x[i+ 2], 23, -995338651);

    a = ii(a, b, c, d, x[i+ 0], 6 , -198630844);
    d = ii(d, a, b, c, x[i+ 7], 10,  1126891415);
    c = ii(c, d, a, b, x[i+14], 15, -1416354905);
    b = ii(b, c, d, a, x[i+ 5], 21, -57434055);
    a = ii(a, b, c, d, x[i+12], 6 ,  1700485571);
    d = ii(d, a, b, c, x[i+ 3], 10, -1894986606);
    c = ii(c, d, a, b, x[i+10], 15, -1051523);
    b = ii(b, c, d, a, x[i+ 1], 21, -2054922799);
    a = ii(a, b, c, d, x[i+ 8], 6 ,  1873313359);
    d = ii(d, a, b, c, x[i+15], 10, -30611744);
    c = ii(c, d, a, b, x[i+ 6], 15, -1560198380);
    b = ii(b, c, d, a, x[i+13], 21,  1309151649);
    a = ii(a, b, c, d, x[i+ 4], 6 , -145523070);
    d = ii(d, a, b, c, x[i+11], 10, -1120210379);
    c = ii(c, d, a, b, x[i+ 2], 15,  718787259);
    b = ii(b, c, d, a, x[i+ 9], 21, -343485551);

    a = add(a, olda);
    b = add(b, oldb);
    c = add(c, oldc);
    d = add(d, oldd);
  }
  return rhex(a) + rhex(b) + rhex(c) + rhex(d);
}
