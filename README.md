# ConformanceRegressionTestFW

This repository contains Web-based regression test tool for DASH-IF-Conformance software.
The tool is used to create Conformance test results(References) and/or compare existing References with the new test results.

## Installation

* Place this repository in your local device in the folder where 'DASH-IF-Conformance' is kept and provide read/write/execute permissions.
* Go into your local repository and install PhpSpreadsheet using the command

  * `composer require phpoffice/phpspreadsheet`

  * __NOTE:__ If composer is not installed, install it using

    * `sudo apt-get update`
    * `sudo apt-get install curl php-cli php-mbstring git unzip`
    * `cd ~`
    * `curl -sS https://getcomposer.org/installer -o composer-setup.php`
    * `sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer`
    * `php composer-setup.php`
    * `sudo mv composer.phar /usr/local/bin/composer`

## Usage

Launch the page on a web browser and provide a list of DASH test vectors (MPD files) that need to be tested.
"Create References" option can be selected to create original test results, which can then be compared with the next batch of test results.
