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

// This class processess export files downloaded from your bank account and creates
// a csv file that can be imported to the Account Tracker iPad app.
//
// This is the core functionality. Separate classes exist for each Bank's format.
class createIpadImportCore {

	// base path for all files
	protected $base_path;

	// read files here
	protected $import_path;

	// and write to here
	protected $export_path;

	// data we read
	protected $imported_data;

	// data we write
	protected $export_data;

	// our own accounts
	protected $my_account;

	// categories
	protected $billing_category;

	// category
	protected $default_billing_category;

	// bank name
	protected $bank_name;

	// remove duplicate transactions
	protected $remove_duplicate_transactions;

	// constructor
	public function __construct(
					$bank_name,
					$billing_category,
					$my_account,
					$date_default_timezone = "Europe/Amsterdam",
					$default_billing_category = "Unknown",
					$debug = false,
					$remove_duplicate_transactions = true
				) {
		// bank name
		$this->bank_name = $bank_name;

		// set paths
		$this->base_path = dirname('../');

		$this->import_path = $this->base_path . "/files/exported_from_bankaccount/" . $bank_name;
		$this->export_path = $this->base_path . "/files/generated_ipad_import";

		// default category
		$this->default_billing_category = $default_billing_category;

		// debug mode
		$this->debug = $debug;

		// time zone
		date_default_timezone_set($date_default_timezone);

		// set billing_categories
		if(!is_array($billing_category)) {
			echo "Error: please supply billing_category array.\n";
			return false;
		}
		$this->billing_category = $billing_category;

		// set my accounts - this should match the names in the ipad app
		if(!is_array($my_account)) {
			echo "Error: please supply my_account array.\n";
			return false;
		}
		$this->my_account = $my_account;

		// this controls whether or not to remove duplicate transactions (between two accounts you own)
		$this->remove_duplicate_transactions = $remove_duplicate_transactions;
	}

	// this function is called from script
	public function process() {
		// read
		$this->readFiles();
		// process
		$this->generateExportData();
		// clean up
		$this->cleanUpExportFile();
		// export
		$this->writeExportFile();
	}

	// open dir, read files on by one, combine them, set result in class
	protected function readFiles() {
		if(!is_dir($this->import_path)) {
			echo "Warning: directory not found: " . $this->import_path . "\nDon't worry, we will create it for you now.\n";
			mkdir($this->import_path);
			echo "You should now put your .csv files into this directory and run this script again.\n";
			// since the directory is empty we cannot process anyway
			return false;
		}

		if ($handle = opendir($this->import_path)) {
			while (false !== ($entry = readdir($handle))) {
				// match csv files only
				$pattern = '/\.csv$/';
				if (preg_match($pattern, $entry)) {
					$csv_files[]=$entry;
				}
			}
			closedir($handle);

			// process data
			if(!is_array($csv_files)) {
				echo "Error: No import files found.\nPlease add them to: " . $this->import_path ." \n";
				echo "These are the files you exported from your Bank Account. This script will then make them ready for the Account Tracker iPad app to import them.\n";
				return false;
			}
			foreach($csv_files as $csv_file) {
				$data = file_get_contents($this->import_path . "/" . $csv_file);
				$csv_data .= $data;
			}

			// set imported data
			$this->imported_data = explode("\n",$csv_data);

			// debug
			if($this->debug == true) {
				print_r($this->imported_data);
			}
		}
	}

	// try to find the right category, otherwise use default
	protected function matchCategory($line) {
		foreach($this->billing_category as $match=>$cat) {
			$pattern = "/".$match."/i";
			if (preg_match($pattern, $line)) {
				return $cat;
			}
		}
		echo "Warning: no category found (you should add it to the settings.inc.php file); Will now use '". $this->default_billing_category ."' for: \n\t". $line ."\n";
		return $this->default_billing_category;
	}

	// match account numers to names
	protected function matchAccount($line) {
		foreach($this->my_account as $match=>$account) {
			$pattern = "/".$match."/i";
			if (preg_match($pattern, $line)) {
				return $account;
			}
		}
	}

	// mark transactions between accounts
	protected function matchInternalTransfers($line) {
		foreach($this->my_account as $match=>$account) {
			$pattern = "/".$match."/i";
			if (preg_match($pattern, $line)) {
				return "[" . $account . "]";
			}
		}
	}

	// clean up
	protected function cleanUpExportFile() {
		// loop all lines we imported
		if(!is_array($this->export_data)) {
                        return false;
                }
		foreach($this->export_data as $export_data) {

			// Internal transfers: only handle transfer OUT; the corresponding IN transfer is handled automatically by the app
			if(preg_match('/^\[/',$export_data['details']) && $export_data['amount'] >0 && $this->remove_duplicate_transactions==true ) {
				continue;
			}
			// Overrule category when intenal transfer
			if(preg_match('/^\[/',$export_data['details'])) {
				$export_data['category'] = "Internal Transfer";
			}
			// debug
			if($this->debug == true) {
				print_r($data);
				print_r($export_data);
			}

			// save line for export
			$export[] = $export_data;
		}

		// save export data
		$this->export_data = $export;
	}

	// write result to csv file
	protected function writeExportFile() {
		if(!$this->export_data) {
			return false;
		}

		if(!is_writable($this->export_path)) {
			echo "Error: path " . $this->export_path . " is not writable!\n";
			return false;
		}

		$filename = $this->export_path . '/import_' . $this->bank_name . '_' . date("YmdHis").'.csv';
		$fp = fopen($filename, 'w');
		foreach ($this->export_data as $fields) {\
			fputcsv($fp, $fields, ',', '"');
		}
		fclose($fp);

		echo "Done! Wrote file " . $filename . "\n";
		echo "Please open the file for manual review, then upload it to the Account Tracker iPad app.\n";
	}
}
