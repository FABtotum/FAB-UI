FAB-UI
======

the FABtotum User Interface Repo


FABUI 0.655 (14/11/2014)

- Fixed some missing plugins dependencies

create
- added new feature to raise or lower the bed during printing (realtime z override)

general
- moved "maintenance" from settings as a single module with its own menu
- added calibration wizard for the first setup
- all plugins and frameworks of the ui updated to their latest version
- added twitter and instagram feeds on login

maintenance
- bed calibration: Bug "140 turns" fixed
- added "4 axis" to disengage the extruder manually

jog
- manual: Improved mcode and gcode search

scan
- added memory optimization during rotative laserscanning
- added dynamic z height correction during probing (drastically reduces probing times by adapting to the object height.)
- corrected xy coordinates in the probing preparation menu

profile
- added "pixels smash" theme skin
- added "glass" theme skin
- added new layouts: Fixed header - fixed navigation - fixed ribbon - fixed footer

recovery
-[devs] added macro simulator to simulate actions from the macro python script
-added "eth config" to manually change the dhcp server address in lan mode.


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

================================================== ================

FABUI 0.635 (20/10/2014)

JOG
- Fixed an annoying bug that it was setting relative mode on movements 

================================================== ================

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

================================================== ================

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

================================================== ================
