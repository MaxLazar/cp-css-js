# MX CP CSS & JS

ExpressionEngine add-on allows you to add custom CSS and Javascript to the Control Panel pages.


## Installation
* Download the latest version of MX CP CSS & JS and extract the .zip to your desktop.
* Copy *cp_css_js* to */system/user/addons/*


## Compatibility	

* ExpressionEngine 4
* ExpressionEngine 5
* ExpressionEngine 6


## Configuration 

***Enable***

***JS***

***CSS***

## Configuration Overrides

**Main configuration file**

The main configuration file, found at system/user/config/config.php, is loaded every time the system is run, meaning that config overrides set in config.php always affect the system’s configuration.

// Dev config

	$config['css_js_settings'] = [
        'js' => '',
        'css' => '.ee-sidebar__title {background-color:#ff6003} .ee-main-header {background-color:#ff6003}',
        'css_file' => 'custom_dev.css',
        'js_file' => 'custom_dev.js',
        'enable' => true
	];
	
// Production config

	$config['css_js_settings'] = [
        'js' => '',
        'css' => '.ee-sidebar__title {background-color:#A2FF03}',
        'css_file' => 'custom_prod.css',
        'js_file' => 'custom_prod.js',        
        'enable' => true
	];

## Support Policy
This is Communite Edition add-on.

## Contributing To MX CP CSS & JS for ExpressionEngine

Your participation to MX CP CSS & JS development is very welcome!

You may participate in the following ways:

* [Report issues](https://github.com/MaxLazar/cp-css-js/issues)
* Fix issues, develop features, write/polish documentation
Before you start, please adopt an existing issue (labelled with "ready for adoption") or start a new one to avoid duplicated efforts.
Please submit a merge request after you finish development.

# Thanks to


### License

The MX CP CSS & JS is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
