<?php
	
	/*
	https://de.wikipedia.org/wiki/OBIS-Kennzahlen
	
	deviceId address name description unit type len factor offset formula role room poll wp isScale
	3 30775 Power W uint16	be 1 1 0 value true false false
	3 30531 Gesamtertrag Wh uint32	be 2 1 0 value true false false
	3 30535 Tagesertrag Wh uint32	be 2 1 0 value true false false
	3 30521 Operating Time s uint64	be 4 1 0 value.interval true false false
	3 30537 Tagesertrag in KWh KWh uint32	be 2 1 0 value true false false
	3 30777 Power L1 W uint16	be 1 1 0 value true false false
	3 30779 Power L2 W uint16	be 1 1 0 value true false false
	3 30781 Power L3 W uint16	be 1 1 0 value true false false
	
	*/

	$obis = array();
	//# totals
	//			hilfszahl, bez_P 4B, bez_E 8B, unit_4B, unit_8B			
    $obis[1]=array(2,'netz_Power','netz_Import','W','kWh'); 
    $obis[2]=array(2,'Export_Power','netz_Export','W','kWh');
    $obis[3]=array(0,'qconsume','qconsume','VAr','kVArh');
    $obis[4]=array(0,'qsupply','qsupply','VAr','kVArh');    
    $obis[9]=array(0,'sconsume','sconsume','VA','kVAh');
    $obis[10]=array(0,'ssupply','ssupply','VA','kVAh');
    $obis[13]=array(1,'netz_Cosphi','','°');		// gibts nur 4Byte
    $obis[14]=array(1,'netz_frequency','','Hz');	// gibts nur 4Byte
	//# phase 1
	$obis[21]=array(2,'netz_PowerL1','netz_ImportL1','W','kWh');
    $obis[22]=array(2,'Export_PowerL1','netz_ExportL1','W','kWh');
    $obis[23]=array(0,'q1consume','q1consume','VAr','kVArh');
    $obis[24]=array(0,'q1supply','q1supply','VAr','kVArh');
    $obis[29]=array(0,'s1consume','s1consume','VA','kVAh');
    $obis[30]=array(0,'s1supply','s1supply','VA','kVAh');
    $obis[31]=array(1,'netz_CurrentL1','','A');	// gibts nur 4Byte
    $obis[32]=array(1,'netz_VoltageL1','','V');	// gibts nur 4Byte
    $obis[33]=array(1,'netz_CosphiL1','','°');	// gibts nur 4Byte
    //# phase 2
	$obis[41]=array(2,'netz_PowerL2','netz_ImportL2','W','kWh');
    $obis[42]=array(2,'Export_PowerL2','netz_ExportL2','W','kWh');
    $obis[43]=array(0,'q2consume','q2consume','VAr','kVArh');
    $obis[44]=array(0,'q2supply','q2supply','VAr','kVArh');
    $obis[49]=array(0,'s2consume','s2consume','VA','kVAh');
    $obis[50]=array(0,'s2supply','s2supply','VA','kVAh');
    $obis[51]=array(1,'netz_CurrentL2','','A');	// gibts nur 4Byte
    $obis[52]=array(1,'netz_VoltageL2','','V');	// gibts nur 4Byte
    $obis[53]=array(1,'netz_CosphiL2','','°');	// gibts nur 4Byte
	//# phase 3
	$obis[61]=array(2,'netz_PowerL3','netz_ImportL3','W','kWh');
    $obis[62]=array(2,'Export_PowerL3','netz_ExportL3','W','kWh');
    $obis[63]=array(0,'q3consume','q3consume','VAr','kVArh');
    $obis[64]=array(0,'q3supply','q3supply','VAr','kVArh');
    $obis[69]=array(0,'s3consume','s3consume','VA','kVAh');
    $obis[70]=array(0,'s3supply','s3supply','VA','kVAh');
    $obis[71]=array(1,'netz_CurrentL3','','A');	// gibts nur 4Byte
    $obis[72]=array(1,'netz_VoltageL3','','V');	// gibts nur 4Byte
    $obis[73]=array(1,'netz_CosphiL3','','°');	// gibts nur 4Byte

	$fck = array();		// faktor über Einheit
    $fck["W"]=10;
    $fck["VA"]=10;
    $fck["VAr"]=10;
    $fck["kWh"]=3600000;
    $fck["kVAh"]=3600000;
    $fck["kVArh"]=3600000;
    $fck["A"]=1000;
    $fck["V"]=1000;
    $fck["°"]=1000;
    $fck["Hz"]=1000;


?>