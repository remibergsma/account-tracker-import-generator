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

// This class processess the ASNBank export files downloaded from triodos.nl and creates
// a csv file that can be imported to the Account Tracker iPad app.

// Script to handle Triodos Bank, Netherlands

// include settings
require './conf/settings.inc.php';

// include class
require './inc/createIpadImportTriodosBank.class.php';

// name of our bank; used to find path with exported files
$bank_name = "triodosbank";

// init class
$triodos = new createIpadImportTriodosBank (
				$bank_name,
				$_SETTING['billing_category'],
				$_SETTING['my_account'],
				$_SETTING['date_default_timezone'],
				$_SETTING['default_billing_category'],
				$_SETTING['debug'],
				$_SETTING['remove_duplicate_transactions']
			);

// process data
$triodos->process();
