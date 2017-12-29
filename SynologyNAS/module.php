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
		$this->RegisterPropertyString("IPAdresse", "192.168.251.50");
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
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("SystemCPUFanStatus", "System - CPU Fan Status", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableString("SystemModelName", "System - Model Name", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableString("SystemSerialNumber", "System - Serial Number", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableString("SystemVersion", "System - Version", "", 10), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("SystemUpgradeAvailable", "System - Upgrade Available", "", 10), true);
	
		AC_SetLoggingStatus($archiv, $this->RegisterVariableString("Disk1Model", "Disk 1 - Model", "", 1110), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk1Status", "Disk 1 - Status", "", 1120), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk1Temperature", "Disk 1 - Temperatur", "", 1130), true);
		
		AC_SetLoggingStatus($archiv, $this->RegisterVariableString("Disk2Model", "Disk 2 - Model", "", 1210), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk2Status", "Disk 2 - Status", "", 1220), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk2Temperature", "Disk 2 - Temperatur", "", 1230), true);
		
		AC_SetLoggingStatus($archiv, $this->RegisterVariableString("Disk3Model", "Disk 3 - Model", "", 1310), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk3Status", "Disk 3 - Status", "", 1320), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk3Temperature", "Disk 3 - Temperatur", "", 1330), true);
		
		AC_SetLoggingStatus($archiv, $this->RegisterVariableString("Disk4Model", "Disk 4 - Model", "", 1410), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk4Status", "Disk 4 - Status", "", 1420), true);
		AC_SetLoggingStatus($archiv, $this->RegisterVariableInteger("Disk4Temperature", "Disk 4 - Temperatur", "", 1430), true);
		
		$this->Update();
		//Instanz ist aktiv
		$this->SetStatus(102);
	}
	
	//
	private function getSnmpData($oid) {
		return exec("c:\ip-symcon\snmpget.exe -c:home -t:2 -v:2c -q -r:192.168.251.50 -o:".$oid);
	}
	// Aktualisierung der Variablen
	public function Update() {
		$SynologyData['System']['systemStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.1.1.0");
		$SynologyData['System']['temperature'] = $this->getSnmpData("1.3.6.1.4.1.6574.1.2.0");
		$SynologyData['System']['powerStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.1.3.0");
		$SynologyData['System']['systemFanStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.1.4.1.0");
		$SynologyData['System']['cpuFanStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.1.4.2.0");
		$SynologyData['System']['modelName'] = $this->getSnmpData("1.3.6.1.4.1.6574.1.5.1.0");
		$SynologyData['System']['serialNumber'] = $this->getSnmpData("1.3.6.1.4.1.6574.1.5.2.0");
		$SynologyData['System']['version'] = $this->getSnmpData("1.3.6.1.4.1.6574.1.5.3.0");
		$SynologyData['System']['upgradeAvailable'] = $this->getSnmpData("1.3.6.1.4.1.6574.1.5.4.0");
		
		SetValue($this->GetIDforIdent("SystemSystemStatus"), $SynologyData['System']['systemStatus']);
		SetValue($this->GetIDforIdent("SystemTemperature"), $SynologyData['System']['temperature']);
		SetValue($this->GetIDforIdent("SystemPowerStatus"), $SynologyData['System']['powerStatus']);
		SetValue($this->GetIDforIdent("SystemSystemFanStatus"), $SynologyData['System']['systemFanStatus']);
		SetValue($this->GetIDforIdent("SystemCPUFanStatus"), $SynologyData['System']['cpuFanStatus']);
		SetValue($this->GetIDforIdent("SystemModelName"), $SynologyData['System']['modelName']);
		SetValue($this->GetIDforIdent("SystemSerialNumber"), $SynologyData['System']['serialNumber']);
		SetValue($this->GetIDforIdent("SystemVersion"), $SynologyData['System']['version']);
		SetValue($this->GetIDforIdent("SystemUpgradeAvailable"), $SynologyData['System']['upgradeAvailable']);
	
		// DiskIDs holen, da diese nicht 1:1 mit den AbfrageIDs übereinstimmen
		$id0 = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.2.0");
		$id1 = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.2.1");
		$id2 = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.2.2");
		$id3 = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.2.3");
		$id4 = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.2.4");
		$id5 = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.2.5");
		$id6 = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.2.6");
		$id7 = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.2.7");

		$SynologyData[$id0]['diskModel'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.3.0");
		$SynologyData[$id1]['diskModel'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.3.1");
		$SynologyData[$id2]['diskModel'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.3.2");
		$SynologyData[$id3]['diskModel'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.3.3");
		$SynologyData[$id4]['diskModel'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.3.4");
		$SynologyData[$id5]['diskModel'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.3.5");
		$SynologyData[$id6]['diskModel'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.3.6");
		$SynologyData[$id7]['diskModel'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.3.7");

		$SynologyData[$id0]['diskStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.5.0");
		$SynologyData[$id1]['diskStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.5.1");
		$SynologyData[$id2]['diskStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.5.2");
		$SynologyData[$id3]['diskStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.5.3");
		$SynologyData[$id4]['diskStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.5.4");
		$SynologyData[$id5]['diskStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.5.5");
		$SynologyData[$id6]['diskStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.5.6");
		$SynologyData[$id7]['diskStatus'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.5.7");

		$SynologyData[$id0]['diskTemperature'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.6.0");
		$SynologyData[$id1]['diskTemperature'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.6.1");
		$SynologyData[$id2]['diskTemperature'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.6.2");
		$SynologyData[$id3]['diskTemperature'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.6.3");
		$SynologyData[$id4]['diskTemperature'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.6.4");
		$SynologyData[$id5]['diskTemperature'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.6.5");
		$SynologyData[$id6]['diskTemperature'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.6.6");
		$SynologyData[$id7]['diskTemperature'] = $this->getSnmpData("1.3.6.1.4.1.6574.2.1.1.6.7");

		if (array_key_exists('Disk 1', $SynologyData)) {
			SetValue($this->GetIDforIdent("Disk1Model"), $SynologyData['Disk 1']['diskModel']);
			SetValue($this->GetIDforIdent("Disk1Status"), $SynologyData['Disk 1']['diskStatus']);
			SetValue($this->GetIDforIdent("Disk1TemperatureModel"), $SynologyData['Disk 1']['diskTemperature']);
		}
		
		if (array_key_exists('Disk 2', $SynologyData)) {
			SetValue($this->GetIDforIdent("Disk2Model"), $SynologyData['Disk 2']['diskModel']);
			SetValue($this->GetIDforIdent("Disk2Status"), $SynologyData['Disk 2']['diskStatus']);
			SetValue($this->GetIDforIdent("Disk2TemperatureModel"), $SynologyData['Disk 2']['diskTemperature']);
		}


	}
}


