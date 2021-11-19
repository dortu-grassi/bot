<?php
/*****************************************************************
 * Questa classe è stata costruita da Daniele Ortu docente dell'itts "C.Grassi" di torino
 * email: daniele.ortu@itisgrassi.edu.it
********************************************************************/

class CChatBot{
	private $server="localhost";
	private $utente="misterno";
	private $passwd="OrtuOrtu66";
	private $schema="chatbot";
	
	private $tbDialogo="tbDialogo";
	private $livello="";
	private $menu="";
	private $risposta="";
	private $livprec="";
	
	private $cn;
	
	private $selRisposte="selRisposta";
	private $txtRisposte="txtRisposta";
	
	private $selChiave="selChiave";
	private $txtChiave="txtChiave";
	
	/*********************************** ASCOLTA   **********************************************/
	
	public function ascolta($cerca){
		//echo "Che minchia è: $cerca";
			if( $e=$this->connetti() ){
				return  "<h1>$e</h1>";
			}
			$q="
				SELECT distinct testo
				FROM tbChiave inner join risposta on (fkRisposta=idRisposta) 
				WHERE chiave like '%".$cerca."%'"
			;

			$tb=$this->cn->query($q);
			if( !$tb){
				return $this->cn->error;
			}
			
			if( $this->cn->affected_rows==0){
				$q="
					SELECT * 
					FROM tbChiave inner join risposta on (fkRisposta=idRisposta) 
					WHERE chiave like 'menu'"
				;
				$tb=$this->cn->query($q);
				$riga=$tb->fetch_array(MYSQLI_ASSOC);
				$r="NON ho trovato ".$cerca."\n".$riga['testo'];
			}
			else if( $this->cn->affected_rows==1 ){
				$riga=$tb->fetch_array(MYSQLI_ASSOC);
				$r=$riga['testo'];
			}
			else if( $this->cn->affected_rows>=1 ){
				$q="
					SELECT *
					FROM tbChiave inner join risposta on (fkRisposta=idRisposta) 
					WHERE chiave like '%".$cerca."%'"
				;

				$tb=$this->cn->query($q);
				$r="";
				while( $riga=$tb->fetch_array(MYSQLI_ASSOC) ){
					$r.=$riga['chiave']."\n";
				}
				$r="Sono confuso.\nHo trovato più di una risposta per la chiave $cerca che hai inserito\nSii più preciso: scegli una fra le seguenti:\n".$r;
			}
			$this->chiudiConnessione();
			return $r;
	}
	
	/*********************************** INSERISCI **********************************************/
	private function pulisciStringa($s){
		return $s; //str_replace("\n","<acapo>",$s);
	}
	
	public function prendiTXTChiave(){return $this->txtChiave;}
	public function prendiSELChiave(){return $this->selChiave;}
	public function prendiListaChiavi($idRisposta){
		$r="
			<SELECT id='$this->selChiave' name='$this->selChiave' size='25' style='width:425px'>";
			if( $e=$this->connetti() ){
				return  "<h1>$e</h1>";
			}
			
			$tb=$this->cn->query(
				"SELECT * FROM tbChiave WHERE fkRisposta=".$idRisposta
			);
			//echo "<h1>TABELLA: $tb</h1>";
			
			if( !$tb){
				$r.="<option value='-1' >".$this->cn->error."</option>";
			}
			else{
				$riga=$tb->fetch_array(MYSQLI_ASSOC);
				if( !$riga ){
					$r.="<option value='-1' >NESSUN VALORE</option>";
				}
				else{
					do{
						$r.="<option value='".$riga['idChiave']."' >".$riga['chiave']."</option>";
					}
					while( $riga=$tb->fetch_array(MYSQLI_ASSOC) );
				}
			}
			$r.="</SELECT>"; 
			$this->chiudiConnessione();
		return $r;
	}
	public function ajaxListaChiavi($idRisposta){
		//$idRisposta=1;
		if( $e=$this->connetti() ){
			return  "<h1>$e</h1>";
		}
		
		$tb=$this->cn->query(
			"SELECT * FROM tbChiave WHERE fkRisposta=".$idRisposta
		);
		//echo "<h1>TABELLA: $tb</h1>";
		$r=array();
		if( !$tb){
			$r[]=array('value'=>'-1','testo'=>$this->cn->error);
		}
		else{
			$riga=$tb->fetch_array(MYSQLI_ASSOC);
			if( !$riga ){
				$r[]=array('value'=>'-1', 'txt'=>'NESSUN VALORE');
			}
			else{
				do{
					$r[]=array('value'=>$riga['idChiave'],'txt'=>$riga['chiave']);
				}
				while( $riga=$tb->fetch_array(MYSQLI_ASSOC) );
			}
		}
		$this->chiudiConnessione();
		
		//print_r($r);
		echo json_encode($r);
	}	
	public function prendiTextAreaChiavi(){
		return "<TEXTAREA cols='50' rows='5' name='$this->txtChiave' id='$this->txtChiave'></TEXTAREA>";
	}
	public function cancellaChiave($id){
		if( $e=$this->connetti() ){
				return  "<h1>$e</h1>";
			}
			//echo $risp;
			//echo $this->pulisciStringa($risp);
			//return;
			$q="delete from tbChiave where idChiave=$id";
			//echo $q;
			$this->cn->query( $q );
		//echo "ERRORE: ".$this->cn->error;
		$r=$this->cn->error;
		$this->chiudiConnessione();
		return $r;
	
	}
	public function nuovaChiave($idRisposta,$chiave){
			if( $e=$this->connetti() ){
				return  "<h1>$e</h1>";
			}
			//echo $risp;
			//echo $this->pulisciStringa($risp);
			//return;
			$this->cn->query(
				"INSERT INTO tbChiave(chiave,fkRisposta) VALUES ('".$this->pulisciStringa($chiave)."',".$idRisposta.")"
			);
		//echo "ERRORE: ".$this->cn->error;
		$r=$this->cn->error;
		$this->chiudiConnessione();
		return $r;
	}



	public function prendiTXTRissposta(){return $this->txtRisposte;}
	public function prendiSELRisposta(){return $this->selRisposte;}
	public function cancellaRisposta($id){
		if( $e=$this->connetti() ){
				return  "<h1>$e</h1>";
			}
			//echo $risp;
			//echo $this->pulisciStringa($risp);
			//return;
			$this->cn->query(
				"delete from risposta where idrisposta=$id"
			);
		//echo "ERRORE: ".$this->cn->error;
		$r=$this->cn->error;
		$this->chiudiConnessione();
		return $r;
	
	}
	public function nuovaRisposta($risp){
			if( $e=$this->connetti() ){
				return  "<h1>$e</h1>";
			}
			//echo $risp;
			//echo $this->pulisciStringa($risp);
			//return;
			$this->cn->query(
				"INSERT INTO .risposta (`testo`) VALUES ('".$this->pulisciStringa($risp)."')"
			);
		//echo "ERRORE: ".$this->cn->error;
		$r=$this->cn->error;
		$this->chiudiConnessione();
		return $r;
	}
	public function prendiTextArea(){
		return "<TEXTAREA cols='50' rows='20' name='$this->txtRisposte' id='$this->txtRisposte'></TEXTAREA>";
	}
	public function prendiListaRisposte(){
		$r="
			<SELECT id='$this->selRisposte' name='$this->selRisposte' size='25' style='width:425px' 
					onclick=rispostaSelezionata('$this->selRisposte','$this->txtRisposte','$this->selChiave')>";
			if( $e=$this->connetti() ){
				return  "<h1>$e</h1>";
			}
			
			$tb=$this->cn->query(
				"SELECT * FROM risposta"
			);
			//echo "<h1>TABELLA: $tb</h1>";
			
			if( !$tb){
				$r.="<option value='-1' >".$this->cn->error."</option>";
			}
			else{
				$riga=$tb->fetch_array(MYSQLI_ASSOC);
				if( !$riga ){
					$r.="<option value='-1' >NESSUN VALORE</option>";
				}
				else{
					do{
						$r.="<option value='".$riga['idRisposta']."' >".$riga['testo']."</option>";
					}
					while( $riga=$tb->fetch_array(MYSQLI_ASSOC) );
				}
			}
			$r.="</SELECT>"; 
			$this->chiudiConnessione();
		return $r;
	}
	
	
	
	private function connetti(){
		$this->cn = new mysqli($this->server,$this->utente,$this->passwd,$this->schema);

		if ($this->cn->connect_error) {
    		return $this->cn->connect_error;
		}	
		return "";
	}
	private function chiudiConnessione(){
		$this->cn->close();
	}
	public function testConnessione(){
		if( $r=$this->connetti() ){
			echo "<h1>$r</h1>";
			return;
		}
		echo "<h1>connesso sono</h1>";
		$this->chiudiConnessione();
	}
	
}
/*
	$t=new CChatBot();
	$t->testConnessione();
*/
?>
