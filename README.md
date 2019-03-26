# ConformanceRegressionTestFW

This repository contains Web-based regression test tool for Conformance-Software.
The tool is used to create Conformance test results(References) or compare existing References with the new test results.

Installation-

Clone this repo in the folder where 'DASH-IF-Conformance' is kept and provide permissions.
Go into the cloned repo and install PhpSpreadsheet using the command
        composer require phpoffice/phpspreadsheet

(If composer is not installed, install it first using
        sudo curl -s https://getcomposer.org/installer | php
        sudo mv composer.phar /usr/local/bin/composer
)

Usage-

Launch the page on the browser and provide a list of DASH test vectors that need to be tested.
Options like "Create References" can be selected to create original test results, which can then be compared with the next batch of test results.

