jmx-wizard-nagios allow you to **easily monitor JMX metrics** through **Nagios**.

## Requirements
* Nagios XI
* JMX plugin (nagios-jmx-plugin.zip in *requirements/* folder).

## Installation

Just **zip** the repo & **upload** and install it through *Admin => Manage Config Wizards.*

## Usage

Go to *Configure => Configuration Wizards => JMX Metrics* page :+1:

#### Metrics available

* Memory
  * Heap Memory Used
  * Heap Memory Max
  * Non Heap Memory Used
  * Non Heap Memory Max
* Threading
  * Deamon Thread Count
  * Peak Thread Count
  * Thread Count
  * Total Started Thread Count
* Operating System :
  * Max File Descriptor Count
  * Open File Descriptor Count
  * System Load Average

## Contributing

See CONTRIBUTING file.

## TODO 

* Reduce code duplication
* Serialize data 
* Find ```<input type="hidden" ... >``` alternative ($_SESSION[...] ?)
* Change password type input

## Credits

* Nagios :heart:

## Contributor Code of Conduct

Please note that this project is released with a [Contributor Code of Conduct](http://contributor-covenant.org/). By participating in this project you agree to abide by its terms. See CODE_OF_CONDUCT file.

## Licence

jmx-wizard-nagios is released under the MIT License. See the bundled LICENSE file for details.
