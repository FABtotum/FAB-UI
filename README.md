FAB-UI
=====
the FABtotum User Interface Repo

FABUI 0.9485 (hotfix) (26/08/2015)

GENERAL
- Fixed configuration file for hardware with version 3

==================================================================


FABUI 0.948 (hotfix) (21/08/2015)

GENERAL
- Minor bugfixes

OBJECTMANAGER
- Fixed error that hides "Print" function on "Manage File" view

==================================================================

FABUI 0.9475 (hotfix) (21/08/2015)

GENERAL
- Minor bugfixes

SCAN
- Fixed error on bed checking before starting sweep scan


==================================================================

FABUI 0.945 (hotfix) (20/08/2015)

JOG
- Fixed error on manual extrusion button

==================================================================

FABUI 0.94 (19/08/2015)

GENERAL
Out of the three first capabilities of the FABtotum, scanning option has always been difficult to be used. Results were not always reliable and satisfying. We worked hard on this as we wanted to offer you a better product. In the upcoming months we will focus even more to have you less stressed when postprocessing. Shapes will be less deformed, the camera is going to add colours and details to the final result. We will tell you more as soon as we have news and we will always update our development log on the forum as well.

The FABtotum hardware can be updated with addons and so on.
The latest FABUI will check the hardware revision number (Saved in the Totumduino EEPROM) and will apply custom preferences, show or hide menu options etc.)
You can set or view the hardware revision of the FABtotum by going to the MDI  and looking up the Gcode M763 in the gcode table.
This is pretty usefull if you want to change some components of the machine or if you follow the active project keeping the FABtotum up to date.
This first step in revision numbering will be also used for the introduction of the Hybrid head v2.

SCAN
- Added photogrammetry Scanning module and Java Server: you can now scan an object and have the images stored on the PC/MAC/Linux running java (need java version 1.8). you can later use the images in a photogrammetry reconstruction software for textured results. 
- Fixed perspective distortion  in Rotative Scan overall quality improvements.
- Sweeping Laser Scanning is back and improved.
- Overall Improvement in laser line recognition at low light conditions
- Scan images are now Jpeg instead of PNGs: performance boost
- Removed reconstruction button as this feature will be back in another form

CREATE
- Minor bugfixes

JOG
- Minor bugfixes

OBJECTMANAGER
- Minor bugfixes

SETTINGS
- Modified "Advanced" tab for managing customs hardware settings
- Minor bugfixes

MAINTENANCE
- Hide "Self Test" from menu
- Minor bugfixes



==================================================================

FABUI 0.93 (hotfix) (14/07/2015)

GENERAL
- Fixed php error showed on version 0.925


==================================================================

FABUI 0.925 (14/07/2015)

GENERAL
- Modified boot script to manage hardware id<br>
- Minor bugfixes
	
JOG
- Added keyboard shortcuts:
	- for XY use arrows
	- for Z use Page Down/Page Up 

OBJECTMANAGER
- Improved user experience during uploading files process
	
MAINTENANCE
- Fixed engage feeder procedure



==================================================================

FABUI 0.91 (16/06/2015)

GENERAL
- Added "Restart" button on top menu bar
- Automated operations have been improved in speed and reliability.
- Fixed wrong message and behavior on error code "110". This error code is now treated as an information alert 

CREATE
- Modified Fan slider to set a min value equal to 50%

OBJECTMANAGER
- Fixed bug on folders navigation on USB Drives

MAINTENANCE
- Spool: improved load and unload spool procedure

==================================================================

FABUI 0.9 (08/06/2015)

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
