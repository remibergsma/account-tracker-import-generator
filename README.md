account-tracker-import-generator
================================

Generate import file (based on export from Bank Account) for iPad Account Tracker app. Graham Haley wrote this great app and for me to start using it I wrote this import genenerator. It currently supports ASN Bank and Triodos Bank from The Netherlands. Although you can add your own
bank easily (see below).

More info on how I use this:

http://blog.remibergsma.com/2012/09/18/import-your-bank-account-transactions-into-the-account-tracker-ipad-app

You can find the app on iTunes:

http://itunes.apple.com/artist/graham-haley/id315200686

Getting started
===============

This is the file structure:
- ./ (Any .php script can be run)
- ./conf (settings file)
- ./inc (php classes)
- ./files/exported_from_bankaccount/bankname (place your csv files in a subfolder matching your bankname)
- ./files/generated_ipad_import (the csv to import into the app is generated in this folder)

Be sure to edit 'settings.inc.php': add your Accounts and Billing Categories.

The Accounts you specify in the settings need to already exist in the app. The categories, however,
will be created on the fly while importing.

Step-by-step:
- 1. Download csv file(s) from your bank's website
- 2. Put the csv file in 'files/exported_from_bankaccount/bankname' subfolder, where 'bankname' should
match the name of you bank.
- 3. Create a settings.inc.php file, based on settings.example.inc.php (rename or copy file, then edit it)
- 4. run 'php run_import_bankname.php'. Select the script with your bank's name.
You will need to have PHP installed for this to work.
- 5. Find the generated file in the 'generated_ipad_import_files' folder
- 6. Upload file to iPad (see below)
- 7. If everything went well, move your downloaded files to the 'processed' folder or delete them.

I have tested this both on Max OSX and Linux. It should work on Windows as well, you'll need to use
'php.exe' instead of 'php' as executable. As I have no Windows pc, I cannot test this.

How to import to iPad
=====================

Open Account Tracker app

Launch the app, go to 'Settings', click 'Backups' at the right corner. Then use 'Import by WiFi'
to get this onto your device. You may also use iTunes file sharing, Dropbox or iCloud if you like.
Select the file in the app choose Import CSV File.

See my blog for an example with images:

http://blog.remibergsma.com/2012/09/18/import-your-bank-account-transactions-into-the-account-tracker-ipad-app

How to add support for your own bank
====================================

Currently this script supports:
- ASN Bank (www.asnbank.nl)
- Triodos Bank (www.triodos.nl)
- Rabobank (www.rabobank.nl)
More banks will be added once I have more details abount their csv format. You can add your
own bank like this:

Copy these two files:
- ./inc/createIpadImportExampleBank.class.php
- ./run_import_examplebank.php

Name them after your bank name.

Then create a folder with the name of your bank here: ./exported_files_from_bankaccount
Place your csv file(s) (the ones you downloaded from your bank) in this folder.

Edit ./inc/createIpadImportExampleBank.class.php and be sure to rename the class to match
your bank's name.

Locate the 'START HERE' mark. At this point in the code, the csv format is defined. You need
to look at the csv file you downloaded and match the fields to the right place in the code.
It'll be trial and error but that's fun. Soon enough you'll have support for another bank!

Bottom line: there are just eight fields that need to have the right data.

Next, edit the './run_import_examplebank.php' script; be sure to rename all the ExampleBank
settings to match your bank name.

If you want, you can send me a Pull request on Github, or e-mail me your code. I'll then
add it to the release so others can benefit from it as well. Thanks!

Contact me via http://blog.remibergsma.com/

License
=======

iPad Account Tracker app import generator

Copyright 2012 by Remi Bergsma
http://blog.remibergsma.com

This file is part of 'iPad Account Tracker app import generator'.

'iPad Account Tracker app import generator' is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3
of the License, or (at your option) any later version.

'iPad Account Tracker app import generator' is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with 'Import file generator for iPad
Account Tracker App'. If not, see http://www.gnu.org/licenses/.
