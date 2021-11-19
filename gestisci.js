//alert("gestisci.js");

function nuovaChiave(frm,selRisposte,chiave,stato){
	//alert("nuovaChiave");
	txt=document.getElementById(chiave);
	if( txt.value=="" || txt.value==null ){
		alert("Inserisci una chiave");
		return;
	}
	lst=document.getElementById(selRisposte);
	if( lst.selectedIndex<0 ){
		alert("Seleziona risposta a cui associare la chiave");
		return;
	}
	yn=confirm("Sei sicuro di voler inserire la nuova chiave:\n"+txt.value);
	if(!yn){
		return;
	}
	document.getElementById(stato).value="nuovaChiave";
	document.getElementById(frm).submit();

}
function cancellaChiave(frm,sel,stato){
	//alert("cancella");
	lst=document.getElementById(sel);
	if( lst.selectedIndex<0  ){
		alert("Seleziona la chiave da cancellare");
		return;
	}
	
	yn=confirm("Sei sicuro di voler cancellare la chiave:\n"+lst.options[lst.selectedIndex].text.replaceAll("-","\n-"));
	if(!yn){
		return;
	}
	document.getElementById(stato).value="cancellaChiave";
	document.getElementById(frm).submit();
	
}

function rispostaSelezionata(sel,txt,selChiavi){
	//alert("rispostaSelezionata");
	lst=document.getElementById(sel);
	lstChiavi=document.getElementById(selChiavi);
	document.getElementById(txt).value=lst.options[lst.selectedIndex].text.replaceAll("-","\n-");
	//document.getElementById(txt).value=lst.selectedIndex;
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			
			//alert(this.responseText);
			var r=JSON.parse(this.responseText);
			lstChiavi.options.length = 0;
			for(i=0;i<r.length;i++){
				var opt=document.createElement("option");
				opt.text=r[i].txt;
				opt.value=r[i].value;
				lstChiavi.options.add(opt); 
				console.log(r[i].value);
			}
			//alert(r[0].value);
			//alert(this.responseText);
		}
	};
	xmlhttp.open("GET", "inserisci.php?ajax=1&idRisposta="+lst.options[lst.selectedIndex].value, true);
	xmlhttp.send();
	
}
function nuovaRisposta(frm,risposta,stato){
	//alert("nuova");
	txt=document.getElementById(risposta);
	if( txt.value=="" || txt.value==null ){
		alert("Inserisci una risposta");
		return;
	}
	yn=confirm("Sei sicuro di voler inserire la nuova risposta:\n"+txt.value);
	if(!yn){
		return;
	}
	document.getElementById(stato).value="nuovaRisposta";
	document.getElementById(frm).submit();
}
function cancellaRisposta(frm,sel,stato){
	//alert("cancella");
	lst=document.getElementById(sel);
	if( lst.selectedIndex<0  ){
		alert("Seleziona la risposta da cancellare");
		return;
	}
	
	yn=confirm("Sei sicuro di voler cancellare la risposta:\n"+lst.options[lst.selectedIndex].text.replaceAll("-","\n-"));
	if(!yn){
		return;
	}
	document.getElementById(stato).value="cancellaRisposta";
	document.getElementById(frm).submit();
	
}
