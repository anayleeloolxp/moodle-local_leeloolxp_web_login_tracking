# Leeloo LXP Login and Attendance Tracking #

Login and Attendance | Real-Time Tracking

## Description ##
This plugin allows administrators and authorized personnel to track chosen users time spent in Moodle LMS, from login (clock
in) to logout (clock out) in Real-Time. It will then sync that data to Leeloo LXP for Learning Analytics, Gamification and Student
Relationship Management purposes.

Based on your settings, the students’ attendance will be compared against a student schedule, either set by the institution or
the students themselves. Managers can see and export reports of time spent during the set schedule and/or out of it.

The Leeloo LXP Login and Attendance Tracking module is the first engagement booster in the Actionable Learning Analytics and
the Advanced Gamification modules.

Use it in tandem with the Leeloo LXP Attendance block!

Your users will see a Real-Time clock with their time spent today, their break time and their expected schedule.
This plugin requires the Leeloo LXP SSO plugin and the Leeloo LXP Synchronizer plugin.

Setup

Go to Admin > Plugins > Local plugins > Leeloo LXP Login and Attendance Integration

- Add your license key

- Enable the plugin

- Enable Leeloo LXP Login/Logout Popups:

o If set to Yes, the users with time-tracking activated will see a popup notification on login; they can decide
whether they want to track their time or not. They will also see a popup on log out.

o If set to no, the time spent by the users with time-tracking activated will simply be tracked without popup
notifications.

- Idle time auto-logout (in minutes): time between the idle-time popup and the automatic logout, if no activity registered by the user. To be used in tandem with the Idle-time popup (see below).

- Additional feature - Idle time popup: this setting is available under the user settings in the Leeloo LXP administration panel. It’s the amount of time before the idle-time popup shows. The users with time-tracking activated will see a popup after this set amount of time without any activity (mouse movements or keystrokes).

How it works

• Once the plugin is enabled: whenever users log in Moodle LMS, it will check whether they exist in Leeloo LXP and if they are activated for tracking.

• If yes, the attendance tracking will start, with or without the confirmation popup (according to your settings)

• If the user is not doing anything (idle), (s)he will first see the idle time popup. If (s)he doesn’t confirm, (s)he will be logged out. Both these times are customizable.

• If login/logout popups are enabled, they also get a popup on log out
