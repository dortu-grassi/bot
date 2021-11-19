<?php
	include "CChatBot.php";
	
	$cb=new CChatBot();
	$ch="-1";
	
	
	if( isset($_GET['ajax']) ){
		$cb->ajaxListaChiavi($_GET['idRisposta']);
		return;
	}
	
	if( isset($_GET['stato']) ){
		//echo "<h1>ciao: ".$_GET['stato']." </h1>";
		if( $_GET['stato']=="nuovaRisposta" ){
			//echo "<h1>ciao: ".$_GET['txtRisposta']." </h1>";
			if( $r=$cb->nuovaRisposta($_GET['txtRisposta']) ){
				echo $r;
				return;
			}
		}
		else if( $_GET['stato']=="cancellaRisposta" ){
			//echo "<h1>ciao: ".$_GET['txtRisposta']." </h1>";
			if( $r=$cb->cancellaRisposta($_GET['selRisposta']) ){
				echo $r;
				return;
			}
		}
		else if( $_GET['stato']=="nuovaChiave" ){
			//echo "<h1>ciao: ".$_GET['txtRisposta']." </h1>";
			$ch=$_GET[$cb->prendiSELRisposta()];
			if( $r=$cb->nuovaChiave($ch,$_GET[$cb->prendiTXTChiave()]) ){
				echo $r;
				return;
			}
		}
		else if( $_GET['stato']=="cancellaChiave" ){
			//echo "<h1>ciao: ".$_GET['selChiave']." </h1>";
			if( $r=$cb->cancellaChiave($_GET['selChiave']) ){
				echo $r;
				return;
			}
		}

	}
	$r= "
		<html>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
			<meta name='autore' content='Daniele ortu' mail='daniele.ortu@itisgrassi.edu.it'>
			<title>Gestisci</title>
			<link rel='stylesheet' href='gestisci.css' type='text/css'>
			<script type='text/javascript' src='gestisci.js'></script>
		</head>
		<body>
			<form id='idFRM' action='inserisci.php' method='GET'>
			<table>
			<tr>
				<td>
					<table bgcolor='lightblue'>
					<tr>
						<td>
							RISPOSTE
	
						</td>
					</tr>
					<tr>
						<td>
							".$cb->prendiListaRisposte()."
	
						</td>
					</tr>
					<tr>
						<td>
							".$cb->prendiTextArea()."
						</td>
					</tr>
					<TR>
						<td>
							<div name='Aggiungi' class='pulsante' onclick=nuovaRisposta('idFRM','txtRisposta','idStato')>NUOVO</div>
							<div name='cancella' class='pulsante' onclick=cancellaRisposta('idFRM','selRisposta','idStato')>CANCELLA</div>
						</td>
					</TR>
					</table >
					
				</td>
				<td valign='top'>
					<table bgcolor='brown'>
					<tr>
						<td>
							CHIAVI	
						</td>
					</tr>
					<tr>
						<td>
							".$cb->prendiListaChiavi($ch)."
						</td>
					</tr>
					<tr>
						<td>
							".$cb->prendiTextAreaChiavi()."
						</td>
					</tr>
					<TR>
						<td>
							<div name='Aggiungi' class='pulsante' onclick=nuovaChiave('idFRM','".$cb->prendiSELRisposta()."','".$cb->prendiTXTChiave()."','idStato')>NUOVO</div>
							<div name='cancella' class='pulsante' onclick=cancellaChiave('idFRM','".$cb->prendiSELChiave()."','idStato')>CANCELLA</div>
						</td>
					</TR>
					</table>
					
				</td>
			</tr>
			</table>
			<input type='hidden' name='stato' id='idStato' value=''>
			</form>			
		</body>
		</html>";
		//$cb->ajaxListaChiavi("13");
		echo $r;
?>
