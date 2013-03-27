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

// This is the settings file in which you can specify categories for spendings and define your accounts.

// Base path (trailing shash not needed)
// This is the path to the folder where you extracted/cloned 'account-tracker-import-generator'
$_SETTING['base_path'] = "~/account-tracker-import-generator";

// About categories:
//
// $_SETTING['billing_category']['Albert Heijn'] = "Groceries";
// Every transaction that is imported that has 'Albert Heijn' in its description is set to category Groceries.
// You can specify as many as you like. The first match found will be used. If no match is found, the default
// category "Unknown" (configurable) is used. A warning is printed in this case. You can either ignore the
// warning and edit the transaction in the app, or add a $_SETTING['billing_category'] to match it and run the import
// again.
$_SETTING['billing_category']['Albert Heijn'] = "Groceries";
$_SETTING['billing_category']['Digros'] = "Groceries";
$_SETTING['billing_category']['BP '] = "Fuel";
$_SETTING['billing_category']['Shell'] = "Fuel";

// default category (when no match is found)
$_SETTING['default_billing_category'] = "Unknown";

// About accounts:
//
// $_SETTING['my_account']['0707771122'] = "Personal account";
// Match the account numbers that are in your csv export files, with the names of these accounts in the app.
// Have a look at the exported file from your account to see if you need to add dots between the numbers.
// You can specify as many accounts as you like.
// When you set multiple, transactions between these accounts are detected and configured accordingly.
$_SETTING['my_account']['0707711122'] = "ASN Account";
$_SETTING['my_account']['300.03.83.213'] = "Triodos Account";

// remove duplicate transactions (when both IN/OUT transaction is on file)
$_SETTING['remove_duplicate_transactions'] = false;

// timezone
$_SETTING['date_default_timezone'] = "Europe/Amsterdam";

// be verbose, or not
$_SETTING['debug'] = false;
