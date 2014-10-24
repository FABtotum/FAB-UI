FAB-UI
======

the FABtotum User Interface Repo


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
