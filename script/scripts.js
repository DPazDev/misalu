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

function buscar(){
	var d1,contenedor;
	contenedor = document.getElementById('contenedor');

	d1 = document.c.cedula.value;
	if(d1.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "buscar.php?c="+d1,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.send(null);
	}
}
