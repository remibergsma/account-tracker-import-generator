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

// This class processess the Triodos export files downloaded from triodos.nl and creates
// a csv file that can be imported to the Account Tracker iPad app.

// we extend this class so we include it first
require 'createIpadImportCore.class.php';

// class to process Triodos csv file and convert it to format readable by Account Tracker ipad app
class createIpadImportTriodosBank extends createIpadImportCore {

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
				$export_data['account'] = $this->matchAccount($data[1]);
				$export_data['date'] = date("d/m/Y", strtotime($data[0]));
				$export_data['details'] = $this->matchInternalTransfers($data[5]);
				$export_data['category'] = $this->matchCategory($data[4] . " " . $data[7]);
				$export_data['notes'] = "Note: " . $data[7] ."\nName: " . $data[4] ."\nAccount: " . $data[5] . "\nCode: " . $data[6] . "\nMy account: " . $data[1];
				$export_data['chequenr'] = '';

				// debet and credit handling
				switch($data[3]) {
					case 'Debet':
						$debetcredit = "-";
						break;
					case 'Credit':
						$debetcredit = "";
						break;
				}

				$export_data['amount'] = str_replace('.','',$debetcredit . $data[2]);
				$export_data['reconciled'] = 'Y';

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
