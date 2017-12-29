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
		$archiv = IPS_GetInstanceIDByName("Archiv", 0 );
				
		// Variablen anlegen / aktualisieren
		// Systemvariablen
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("SystemSystemStatus", "System - System Status", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("SystemTemperature", "System - Temperatur", "Temperatur", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("SystemPowerStatus", "System - Power Status", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("SystemSystemFanStatus", "System - System Fan Status", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("SystemCPUStatus", "System - CPU Fan Status", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableString("SystemModelName", "System - Model Name", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableString("SystemSerialNumber", "System - Serial Number", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableString("SystemVersion", "System - Version", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("SystemUpgradeAvailable", "System - Upgrade Available", "", 10), true);
	
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk1Model", "Disk 1 - Model", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk1Model", "Disk 1 - Status", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk1Model", "Disk 1 - Temperatur", "", 10), true);
		
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


