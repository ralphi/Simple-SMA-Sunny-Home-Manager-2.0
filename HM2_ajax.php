<?php
	
	
	
	function bin_dec($bin, $len) { //char in hex in dec 
		$hex_num = bin2hex($bin);
		$val = hexdec( "0x".$hex_num);
		return $val;
	}
	
	include "HM2_config.php"; // Register

// **************************************************************************
// ************************ OPEN SOCKET GET DATA ****************************
// **************************************************************************
	// Horchen nach 600 Zeichen Multicast
	$len = 600 ; //Empfang eines Datensatzes mit 600 Byte Länge, Soviel gib SMA EM von sich
	$flags = 0 ;
	$from = "" ;
	$adress = "239.12.255.254"; //Das ist die MultiCast IP auf die das SMA EM "sendet"
	$port = 9522 ; // Port auf dem gelauscht wird
	$grpparms = array("group"=>$adress,"interface"=>0) ;  // hier werden die relevanten Sockets zusammengestellt
	$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP); // Einzelerklärungen lassen sich gut im Netz nachlesen
	socket_bind($socket,"0.0.0.0",$port);
	socket_set_option($socket,IPPROTO_IP,MCAST_JOIN_GROUP,$grpparms);
	socket_recvfrom($socket,$raw,$len,$flags,$from,$port); // der empfangene Datensatz wird in Variable $raw geschrieben
	socket_close($socket);

// **************************************************************************
// ************************ START VALUES ****************************
	$html = '<tr><td colspan="3">'.date("G:i:s").' Uhr</td></tr>\n';
	$html = '';

	$arr_PE_out = array();	// array Power & Energy mehrdimensional
	$sma_reg = 	array();
	$val_raw = 	array();
	$data = 	substr($raw,28); // header weg 
	$size_4 = 0x04;	// reg & Power
	$size_8 = 0x08;	// Energy

	$i=0; $z=0; $phase=0; // counter für arrays
	$tmp = 0;	
	$sval = 0;
	
// **************************************************************************
// ************************ SPLIT & ZUORDNEN DATA ***************************
// **************************************************************************	
	while ($i<561) {		// alle erhaltenen Bytes durchlaufen
	
		$sma_reg[$z] = substr($data,$i,$size_4);	// reg head 4 Byte
		$OBIS = ord($sma_reg[$z][1]);				// kuck welche obis nr
		if ($OBIS < 1 || $OBIS > 73) continue;		// die brauch ich nicht
		
		$val_size = ord($sma_reg[$z][2]);			// Wert länge in Byte
		if ($val_size == $size_4) { 				// wenn P
			$item = $obis[$OBIS][1];				// Bez P
			$unit = $obis[$OBIS][3];				// unit
			
		}else if ($val_size == $size_8) {			// wenn E
			$item = $obis[$OBIS][2];				// Bez E
			$unit = $obis[$OBIS][4];				// unit
		}
		
		$fakt = $fck[$unit];						// faktor
		$val_raw[$z] = substr($data,($i+4),$val_size);	// nachfolgende 4 bzw 8 Byte
		$val = bin_dec($val_raw[$z], $val_size)/$fakt; // value *********
		
	// **************************************************************************	
	// ************************ ZUORDNEN DATA ***************************
	// ***************** Was auch immer ausgegeben werden soll ************
		$arr_all_out[$item] = $val;	// array all
		
		// Power 4B
		if ($val_size == $size_4 && $obis[$OBIS][0] > 1) { // ..1 = import
			if ($OBIS % 10 == 1) {
				$arr_PE_out['Power']['IMP'][$item] = $val; 
				$tmp = $val;
				// Folgewert ist immer Export
			}
			if ($OBIS % 10 == 2) {			// ..2 = export
				$arr_PE_out['Power']['EXP'][$item] = $val;	
				// Input - Output: positiv Import / negativ Einspeisung
				$sval = round($tmp-$val); 
				$arr_PE_out['Power'][("L".$phase)] = $sval;
				
				// zB. html output 
				if ($phase == 0 ) $html .= '<tr><td>Power</td><td class="werte"><b>'.$sval.'</b></td><td>'.$unit.'</td></tr>\n';
				if ($phase == 1 ) $html .= '<tr><td>Power L1</td><td class="werte">'.$sval.'</td><td>'.$unit.'</td></tr>\n';
				if ($phase == 2 ) $html .= '<tr><td>Power L2</td><td class="werte">'.$sval.'</td><td>'.$unit.'</td></tr>\n';
				if ($phase == 3 ) $html .= '<tr><td>Power L3</td><td class="werte">'.$sval.'</td><td>'.$unit.'</td></tr>\n';
				

				$phase++;
			}
		// Energy 8B
		}else if ($val_size == $size_8 && $obis[$OBIS][0] > 1) {
			if ($OBIS % 10 == 1) $arr_PE_out['Energy']['IMP'][$item] = $val;
			if ($OBIS % 10 == 2) $arr_PE_out['Energy']['EXP'][$item] = $val;	
		}
		
		$i += $size_4 + $val_size; 	// nächstes Reg
		$z++;						// array +1
	} // alle durch
		
// **************************************************************************
// ************************ AUSGABE *****************************************
// **************************************************************************	
	
		echo $html."\n";
		/*
		if($out_files) {
			$json_PE_out = json_encode($arr_PE_out, JSON_PRETTY_PRINT); 
			echo $json_PE_out."\n";
		}
		*/

?>