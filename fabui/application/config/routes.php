<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "dashboard";
$route['404_override']       = 'error/show_404';


/** plugin route */
$route['plugin/remove/(:any)']     = "plugin/remove/$1";
$route['plugin/activate/(:any)']   = "plugin/activate/$1";
$route['plugin/deactivate/(:any)'] = "plugin/deactivate/$1";
$route['plugin/add']               = "plugin/add";
$route['plugin/upload']            = "plugin/upload";
$route['plugin/(:any)']            = "$1";

/** maintenance route */
$route['maintenance/4-axis']            = "maintenance/fourthaxis";
$route['maintenance/self-test']         = "maintenance/selftest";
$route['maintenance/bed-calibration']   = "maintenance/bedcalibration";
$route['maintenance/probe-calibration'] = "maintenance/probecalibration";
$route['maintenance/first-setup']       = "maintenance/firstsetup";
$route['maintenance/system-info']       = "maintenance/systeminfo";

$route['settings/set-eth']    = "settings/seteth";
$route['settings/set-wifi']   = "settings/setwifi";
$route['settings/raspi-cam']   = "settings/raspicam";
$route['controller/stop-all'] = "controller/stop_all";


/** make route */
$route['make/print'] = "create";
$route['make/mill']  = "create/index/subtractive";
$route['make/scan']  = 'scan';

/* End of file routes.php */
/* Location: ./application/config/routes.php */