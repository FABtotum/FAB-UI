FAB-UI
=====
the FABtotum User Interface Repo

FABUI 0.9 (08/06/2014)

GENERAL
- Improved Apache configuration to optimize server perfomance
- Removed some unnecessary services that consume memory
- Added "Emergency Button" on the top bar to stop immediately all the running operation on the FABtotum
- Moved "Plugins" and "Support" button from the top bar to the sidebar navigation menu

PROFILE
- Added "Menu on Top" on layout settings

DASHBOARD
- Cam Widget: improved widget to get more control of the RaspiCam

SCAN
- Corrected scan descriptions
- Modified "Rotating Scan" quality parameters
- Improved user experience that now take advantage of responsiveness of web sockets technology (where supported by the browser)

CREATE
- Added parmetric Z Height controller
- Removed the information of the current layer during printing, it will be added back in future updates

OBJECTMANAGER
- Added bulk selection for bulk actions on table views

SETTINGS
- Added support for direct upload from Slic3r (by Tohara)
- Added new new Feature: custom functions on hitting both Y or Z endstops (need firmware version: 1.0.008) (by Imarin2)
- Added support for custom activate and deactivate methods for plugins (by Tohara)
- Added new feature: configurable extruder steps per unit (by Imarin2)


==================================================================

FABUI 0.875 (17/03/2015)

CREATE
- Fixed bug which prevented the printing to start if was chosen auto bed leveling procedure for the calibration

MAINTENANCE
- Fixed blocked button on "Probe Calibration"

UPDATE
- Fixed bug that did not properly update the progress bar during the updating task

==================================================================

FABUI 0.85 (13/03/2015)

GENERAL
The primary focus has been improving the user experience by increasing the responsiveness of the application to user inputs as well as internal logging.
Web Sockets have been added for leaner communication with the device, where supported by the browser.
If the browser doesn't support Web Sockets, the old Ajax Json interaction mode has been provided for backward compatibility on older browsers.
You can get a web socket compatibility list here: http://en.wikipedia.org/wiki/WebSocket#Browser_support.
The Boot process, initially based on a cron event and a arbitrary timeout of 60 seconds, is now controlled by a init script that check whenever all the required services for the FAB UI are started or not.
The unit ambient light will turn to it's default color only when the FAB UI is really accessible. 
This has also slightly reduced boot times and will avoid any instance when a service (e.g. Mysql) is not yet available.
Improved in general user experience using the potential of the web socket 

YOU HAVE TO RESTART THE FABTOTUM FOR APPLY THE NEW FEATURES
In order to improve the machine performances some parameters will be updated

SCAN
- Minor bugfixes

CREATE
- Improved user experience that now take advantage of the changes outlined in the [General] Section. Overrides will respond faster and the lag between the input and the actual execution on the Totumduino controller is reduced as a result
- Minor bugfixes

JOG
- Added realtime temperature feedback (available only if supported by the browser)
- Controls, console, functions and temperature will now response faster.
- Improved user experience
- Minor bugfixes

OBJECTMANAGER
- Optimized slicing presets
- Fixed bug on uploading files from local or USB disk
- Minor bugfixes


SETTINGS
- General: Added feeder disengage offset editable parameter
- Minor bugfixes

==================================================================

FABUI 0.8 (26/01/2015)

SCAN
- Fixed reconstruction order and size
- Increased postprocessing speed by changing the laser detection method<
- Dynamic Brightness Treshold introduced
- Minor works toward introduction of perspective correction and camera undistort algorithm

CREATE
- Improved user experience
- Fixed ojects list on second page

JOG
- Improved user experience

OBJECTMANAGER
- Added 2 new presets config for the slicing "PLA Generic" and "ABS Generic - Small pieces"
- Added manual helper for the slicing parameters

MAINTENANCE
- Performed Load and Unload spool functions

SETTINGS
- General: Added selection switch, Left or Right, for homing (need firmware 1.0.007)

GENERAL
- Minor bugfixes
- Fixed bug on "Recovery Password" procedure

==================================================================

FABUI 0.75 (05/01/2015)

GENERAL
- Added module SUPPORT
- Improved recovery section for a better user experience

OBJECTMANAGER
- Fixed characters encoding on show list

MAINTENANCE
- Self Test: improved script on heating test 

SETTINGS
- General : fixed issue on safety front door lock option (need firmware version 1.0.006)
- Network : fixed some bugs on WI-FI connection. Now is possible to connect the FABtotum to an open wifi connection or to a WEP wifi connection 

==================================================================
FABUI 0.7 (04/12/2014)

SCAN
- Fixed end scan procedure
- Minor bugfixes
	
CREATE
- Fixed wrong behavior of the wizard buttons after calling "Engage Feeder" procedure
- Fixed additive print end procedure
- Added Tips system during print. For example a tip message will appear if the print seems to start slowly
- Fixed and improved some UI experience
- Minor bugfixes

MAINTENANCE
- First Setup: fixed bug on bed leveling which prevented to continue with the wizard

SETTINGS
- General : added option (for experts users only) that permits to avoid safety front door lock (need firmware version 1.0.006)
- Network : added ethernet static ip address configurator
- Network : improved wifi network settings section. Avoided some ambiguous button behaviors

PLUGIN
Realeased first beta version of "Plugin" module. With this first version is possible to upload and install a plugin

GENERAL
- Minor bugfixes


==================================================================
FABUI 0.655 (14/11/2014)

- Fixed some missing plugins dependencies

CREATE
- added new feature to raise or lower the bed during printing (realtime z override)

GENERAL
- moved "maintenance" from settings as a single module with its own menu
- added calibration wizard for the first setup
- all plugins and frameworks of the ui updated to their latest version
- added twitter and instagram feeds on login

MAINTENANCE
- bed calibration: Bug "140 turns" fixed
- added "4 axis" to disengage the extruder manually

JOG
- manual: Improved mcode and gcode search

SCAN
- added memory optimization during rotative laserscanning
- added dynamic z height correction during probing (drastically reduces probing times by adapting to the object height.)
- corrected xy coordinates in the probing preparation menu

PROFILE
- added "pixels smash" theme skin
- added "glass" theme skin
- added new layouts: Fixed header - fixed navigation - fixed ribbon - fixed footer

RECOVERY
- [devs] added macro simulator to simulate actions from the macro python script
- added "eth config" to manually change the dhcp server address in lan mode.

==================================================================

FABUI 0.64 (23/10/2014)

SETTINGS
- Maintenance: added "Bed Calibration" procedure: now you can level the plane for an optimal printing conditions

CREATE
- Fixed erratic behaviour preparing mill
- Fixed and improved "Stop" print function

OBJECTMANAGER
- Added STL file viewer 

GENERAL
- Implemented emergency error codes description
- Fixed emergency dialog

==================================================================

FABUI 0.635 (20/10/2014)

JOG
- Fixed an annoying bug that it was setting relative mode on movements 

==================================================================

FABUI 0.63 (17/10/2014)

SCAN
- Fixed bug on finalizing procedure

CREATE
- Improved mill preparation procedure (more instructions, possibility to set steps and feedrate on jog)

JOG
- Improved and optimized GCode execution

OBJECTMANAGER
- Added functionality for uploading, removing, saving and download slicer config files on "Slicing" section

OTHER
- Fixed sidebar menu vulnerability
- Improved re-installation procedure
- Added error 404 page handler<br>
- Renamed "Marlin Firmware" to "FABlin Firmware"
- Improved "Engage Feeder" instructions

==================================================================

FABUI 0.62 (09/10/2014)

SETTINGS - Maintenance - Probe Calibration
- Added Fine probe calibration procedure

CREATE
- Optimized GCode (faster print start)
- nozzle and heated bed will start heating before printing to reduce waits
- heated bed check control moved to warning level. If heating is required it will

SCAN
- sweep mode disabled until next geometry fix
- more instructions on probe mode
- fixed an instance where the rotating laser scanner triggered the emergency mode
- fixed an instance where the Z-probe could crash on the platform

OTHER
- updated the default slic3r configs with newer and improved versions
- updated the Marvin sample gcode on newer installations
- fixed a bug with subtractive file recognition
- added a sample bracelet
- minor bugfixes
- added "Request a feature" button
- added "Report a bug" button

==================================================================
