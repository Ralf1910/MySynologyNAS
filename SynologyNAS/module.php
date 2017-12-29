<?
//  Modul für ein Synology NAS
//  gestestet mit DS 1815+
//
//	Version 0.8
//
// ************************************************************

class SynologyNAS extends IPSModule {
	public function Create() {
		// Diese Zeile nicht löschen.
		parent::Create();
		// IP Adresse
		$archiv = IPS_GetInstanceIDByName("Archiv", 0 );
		$this->RegisterPropertyString("IPAdresse", "127.0.0.1");
		$this->RegisterPropertyInteger("Update", 1);
		// Updates einstellen
		$this->RegisterTimer("Update", $this->ReadPropertyInteger("Update")*5*60*1000, 'NAS_Update($_IPS[\'TARGET\']);');
	}
	// Überschreibt die intere IPS_ApplyChanges($id) Funktion
	public function ApplyChanges() {
		// Diese Zeile nicht löschen
		parent::ApplyChanges();
		$this->SetTimerInterval("Update", $this->ReadPropertyInteger("Update")*5*60*1000);
		
		// Module anlegen
		$self = $_IPS['SELF']
		$disk1ID = IPS_CreateInstance("{485D0419-BE97-4548-AA9C-C083EB82E61E}");
		IPS_SetName($disk1ID, "Disk 1");
		IPS_SetParent($disk1ID, $self);
		
		// Variablen aktualisieren
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk1Model", "Model", "", 10), true);
		
		$this->Update();
		//Instanz ist aktiv
		$this->SetStatus(102);
	}
	// Aktualisierung der Variablen
	public function Update() {
		// $power = Sys_Ping($this->ReadPropertyString("IPAdresse"), 1000 );
		// SetValue($this->GetIDForIdent("Power"), $power);
	}
}


