/*****************************************************************
 * Questa classe è stata costruita da Daniele Ortu docente dell'itts "C.Grassi" di torino
 * email: daniele.ortu@itisgrassi.edu.it
********************************************************************/

function vai(){
	//alert("vai");
	var msg=document.getElementById('txtMessaggioDaCercare');
	if( msg.value=="configura" ){
		location.href="./inserisci.php";
		return;
	}
	var param="cerca="+msg.value;
	//alert(param);
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", "ascolta.php", true);
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//document.getElementById(div).innerHTML = "prova ajax";
			//alert(this.responseText);
			document.getElementById("testoRisposta").value=this.responseText;
			msg.value="";
		}
	};	
	//alert(xmlhttp);
	xmlhttp.send(param);
}

function evkpress(){
	//alert(e);
	document.getElementById('txtMessaggioDaCercare').onkeypress = function(e) {
		var charCode = e.which;
		
		if (charCode ==13){
			//alert(charCode);
			vai();
		}
			
	}
					
}

