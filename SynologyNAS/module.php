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
		// Variablen aktualisieren
		$this->MaintainVariable("Power", "Power", 0, "", 10, true);
		$this->Update();
		//Instanz ist aktiv
		$this->SetStatus(102);
	}
	// Aktualisierung der Variablen
	public function Update() {
		$power = Sys_Ping($this->ReadPropertyString("IPAdresse"), 1000 );
		SetValue($this->GetIDForIdent("Power"), $power);
	}
}


if ($this->ReadPropertyInteger("LeistungWR1")>0) {
				if (strlen($this->ReadPropertyString("WR1Pac"))>0) 	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1LeistungAC", "WR1 Leistung AC", "Elektrizitaet.Leistung", 100), true);
				if (strlen($this->ReadPropertyString("WR1DaySum"))>0) 	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1ErzeugteEnergie", "WR1 erzeugte Energie", "Elektrizitaet.Verbrauch", 110), true);
				if (strlen($this->ReadPropertyString("WR1DaySum"))>0)	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1RollierenderJahresertrag", "WR1 rollierender Jahresertrag", "Elektrizitaet.Verbrauch", 120), true);
				if (strlen($this->ReadPropertyString("WR1Pdc1"))>0) 	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1LeistungDC1", "WR1 Leistung DC1", "Elektrizitaet.Leistung", 130), true);
				if (strlen($this->ReadPropertyString("WR1Pdc2"))>0) 	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1LeistungDC2", "WR1 Leistung DC2", "Elektrizitaet.Leistung", 130), true);
				if (strlen($this->ReadPropertyString("WR1Pdc3"))>0) 	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1LeistungDC3", "WR1 Leistung DC3", "Elektrizitaet.Leistung", 130), true);
				if (strlen($this->ReadPropertyString("WR1Udc1"))>0) 	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1SpannungDC1", "WR1 Spannung DC1", "Elektrizitaet.Spannung_DC", 140), true);
				if (strlen($this->ReadPropertyString("WR1Udc2"))>0) 	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1SpannungDC2", "WR1 Spannung DC2", "Elektrizitaet.Spannung_DC", 140), true);
				if (strlen($this->ReadPropertyString("WR1Udc3"))>0) 	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1SpannungDC3", "WR1 Spannung DC3", "Elektrizitaet.Spannung_DC", 140), true);
				if (strlen($this->ReadPropertyString("WR1Temp"))>0) 	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1Temperatur", "WR1 Temperatur", "~Temperature", 150), true);
				if (strlen($this->ReadPropertyString("WR1Uac"))>0)  	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1Wirkungsgrad", "WR1 Wirkungsgrad", "Elektrizitaet.Wirkungsgrad", 160), true);
				if (strlen($this->ReadPropertyString("WR1Uac"))>0)  	AC_SetLoggingStatus($archiv, $this->RegisterVariableFloat("WR1SpannungAC", "WR1 Spannung AC", "Elektrizitaet.Spannung_230V", 170), true);
				if (strlen($this->ReadPropertyString("WR1Status"))>0)  	AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("WR1Status", "WR1 Status", "", 180), true);
				if (strlen($this->ReadPropertyString("WR1Error"))>0)  	AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("WR1Fehlermeldung", "WR1 Fehlermeldung", "", 190), true);
			}
