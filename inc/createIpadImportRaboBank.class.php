<?php

// iPad Account Tracker app import generator
//
// Copyright 2012 by Remi Bergsma
// http://blog.remibergsma.com
//
// This file is part of 'iPad Account Tracker app import generator'.
//
// 'iPad Account Tracker app import generator' is free software: you can redistribute it and/or modify it
// under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3
// of the License, or (at your option) any later version.
//
// 'iPad Account Tracker app import generator' is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License along with 'Import file generator for iPad
// Account Tracker App'. If not, see http://www.gnu.org/licenses/.
//

// we extend this class so we include it first
require 'createIpadImportCore.class.php';

// class to process RaboBank csv file and convert it to format readable by Account Tracker ipad app
class createIpadImportRaboBank extends createIpadImportCore {

	// process imported data and generate export data
	protected function generateExportData() {

		// we need the data
		if(!$this->imported_data) {
			return false;
		}
		// loop all lines we imported
		foreach($this->imported_data as $line) {

			// turn into array
			$data = str_getcsv($line);

			// process non empty lines
			if($data[0]) {
				// format csv as required by Account Tracker app

				//
				// START HERE
				// Set the right field id's in your csv: first field is 0, second one is 1, etc.
				//

				$export_data['account'] = $this->matchAccount($data[0]); // field with your account number
				$export_data['date'] = date("d/m/Y", strtotime($data[2])); // field with date of transaction
				$export_data['details'] = $this->matchInternalTransfers($data[5]); // field with account number of other party
				$export_data['category'] = $this->matchCategory($data[6] . " " . $data[10] . " " . $data[11] . " " . $data[12] . " " . $data[13] . " " . $data[14] . " " . $data[15]); // fields with description, company name etc. Used to match categories.
				$export_data['notes'] = "Note line 1: " . $data[10] . "\nNote line 2: " . $data[11] . "\nNote line 3: " . $data[12]  . "\nNote line 4: " . $data[13] . "\nNote line 5: " . $data[14] . "\nNote line 6: " . $data[15]   ."\nName: " . $data[6] ."\nAccount: " . $data[5] . "\nCode: " . $data[8] . "\nMy account: " . $data[0]; // All other fields that you want to appear in the notes.
				$export_data['chequenr'] = ''; // you can leave this empty but it needs to be at least an empty row

				// debet and credit handling
				switch($data[3]) {
					case 'D':
						$debetcredit = "-";
						break;
					case 'C':
						$debetcredit = "";
						break;
				}

				$export_data['amount'] = str_replace('.',',',$debetcredit . $data[4]); // the amount of money: 123,45 with no separators for 1000's: 1234,56

				$export_data['reconciled'] = 'Y'; // since we are importing from bankstatements this is always Y.

				//
				// END HERE
				// You don't need to change anything else below.
				//

				// debug
				if($this->debug == true) {
					print_r($data);
					print_r($export_data);
				}

				// save line for export
				$export[] = $export_data;

				// reset factor
				unset($factor);
			}
		}

		// save export data
		$this->export_data = $export;
	}
}
