#!/usr/bin/python
# Some of the user elements are from the user_sessions.py script
# made by Clayton Burlison and Michael Lynn
# Other parts of the script from Paul Bowden's scripts found on his Github
#
# To enable the msupdate parts:
#   sudo defaults write org.munkireport.ms_office msupdate_check_enabled -bool true

import subprocess
import os
import plistlib
import sys
import time
import platform
import string

sys.path.insert(0, '/usr/local/munki')

from munkilib import FoundationPlist
from CoreFoundation import CFPreferencesCopyAppValue

from SystemConfiguration import SCDynamicStoreCopyConsoleUser
from ctypes import (CDLL,
                    Structure,
                    POINTER,
                    c_int64,
                    c_int32,
                    c_int16,
                    c_char,
                    c_uint32)
from ctypes.util import find_library

# constants
c = CDLL(find_library("System"))

class timeval(Structure):
    _fields_ = [
                ("tv_sec",  c_int64),
                ("tv_usec", c_int32),
               ]

class utmpx(Structure):
    _fields_ = [
                ("ut_user", c_char*256),
                ("ut_id",   c_char*4),
                ("ut_line", c_char*32),
                ("ut_pid",  c_int32),
                ("ut_type", c_int16),
                ("ut_tv",   timeval),
                ("ut_host", c_char*256),
                ("ut_pad",  c_uint32*16),
               ]
    
def get_user_config():
# Get the MAU's config as seen from the current or last person logged in
# Because some settings are user specific

    try:
        mau_config = FoundationPlist.readPlist(get_user_path()+"/Library/Preferences/com.microsoft.autoupdate2.plist")
        mau_config_items = {}

        for item in mau_config:
            if item == 'UpdateCache':
                mau_config_items['updatecache'] = mau_config[item]
            elif item == 'ManifestServer':
                mau_config_items['manifestserver'] = mau_config[item]
            elif item == 'AutoUpdateVersion':
                mau_config_items['autoupdateversion'] = mau_config[item]
            elif item == 'ChannelName':
                mau_config_items['channelname'] = mau_config[item]
            elif item == 'HowToCheck':
                mau_config_items['howtocheck'] = mau_config[item]
            elif item == 'LastCheckForUpdates' or item == 'LastUpdate':
                if mau_config[item] != "Dec 29, 1 at 7:03:58 PM":
                    pattern = '%Y-%m-%d %H:%M:%S +0000'
                    mau_config_items['lastcheckforupdates'] = int(time.mktime(time.strptime(str(mau_config[item]).replace(" at ", ", "), pattern)))
            elif item == 'StartDaemonOnAppLaunch':
                mau_config_items['startdaemononapplaunch'] = to_bool(mau_config[item])

            elif item == 'Applications':
                mau_config_items['registeredapplications'] = process_registered_apps(mau_config)        
            
        # Add in update information if enabled
        msupdate_check_enabled = to_bool(CFPreferencesCopyAppValue('msupdate_check_enabled', 'org.munkireport.ms_office'))
        if os.path.exists('/Library/Application Support/Microsoft/MAU2.0/Microsoft AutoUpdate.app/Contents/MacOS/msupdate') and msupdate_check_enabled == 1:
            mau_config_items = get_msupdate_update_check(mau_config_items)
                
        return (mau_config_items)

    except Exception:
        return {}
    
def process_registered_apps(mau_config):
    apps = mau_config['Applications']
    registered_apps = {}
        
    for app in apps:
        app_name = app.split("/")[-1].split(".")[0]
        registered_apps[app_name] = {}
        for item in apps[app]:
            if item == 'Application ID':
                registered_apps[app_name]['application_id'] = apps[app][item]
                registered_apps[app_name]['applicationpath'] = app
                try:
                    info_plist = FoundationPlist.readPlist(app+"/Contents/Info.plist")
                    registered_apps[app_name]['versionondisk'] = info_plist['CFBundleVersion']
                except Exception:
                    pass
    return registered_apps

def get_msupdate_update_check(mau_update_items):
# Quickly check for updates as the current or last person logged in
    try:    
        cmd = ['/Library/Application Support/Microsoft/MAU2.0/Microsoft AutoUpdate.app/Contents/MacOS/msupdate', '-l', '-f','plist']
        proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                                preexec_fn=demote(),
                                stdin=subprocess.PIPE,
                                stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        (output, unused_error) = proc.communicate()
        
        mau_update = plistlib.readPlistFromString(output.split("\n",2)[2])

        for app in mau_update:
            app_name = app['ApplicationToBeUpdatedPath'].split("/")[-1].split(".")[0]
            for item in app:
                if item == 'Application ID':
                    mau_update_items['registeredapplications'][app_name]['application_id'] = app[item]
#                elif item == 'ApplicationToBeUpdatedPath':
#                    mau_update_items['registeredapplications'][app_name]['applicationpathtobeupdated'] = app[item]
                elif item == 'Baseline Version':
                    mau_update_items['registeredapplications'][app_name]['baseline_version'] = app[item]
                elif item == 'Date':
                    mau_update_items['registeredapplications'][app_name]['date'] = app[item]
                elif item == 'FullUpdaterLocation':
                    mau_update_items['registeredapplications'][app_name]['fullupdaterlocation'] = app[item]
                elif item == 'FullUpdaterSize':
                    mau_update_items['registeredapplications'][app_name]['fullupdatersize'] = app[item]
                elif item == 'Location':
                    mau_update_items['registeredapplications'][app_name]['deltalocation'] = app[item]
                elif item == 'Payload':
                    mau_update_items['registeredapplications'][app_name]['payload'] = app[item]
                elif item == 'Size':
                    mau_update_items['registeredapplications'][app_name]['deltasize'] = app[item]
                elif item == 'Title':
                    mau_update_items['registeredapplications'][app_name]['title'] = app[item]
                elif item == 'Update Version':
                    mau_update_items['registeredapplications'][app_name]['update_version'] = app[item]
        return (mau_update_items)

    except Exception:
        return {}

def get_mau_prefs():
# Get system level preferences from /Library/ and config profiles

    try:
        if os.path.exists('/Library/Preferences/com.microsoft.autoupdate2.plist'):
            mau_plist = FoundationPlist.readPlist("/Library/Preferences/com.microsoft.autoupdate2.plist")
        else:
            mau_plist = {}
    
        mau_prefs = {}
                
        if 'UpdateCache' in mau_plist:
            mau_prefs['updatecache'] = mau_plist['UpdateCache']
        elif CFPreferencesCopyAppValue('UpdateCache', 'com.microsoft.autoupdate2'):
            mau_prefs['updatecache'] = CFPreferencesCopyAppValue('UpdateCache', 'com.microsoft.autoupdate2')
    
        if 'ChannelName' in mau_plist:
            mau_prefs['channelname'] = mau_plist['ChannelName']
        elif CFPreferencesCopyAppValue('ChannelName', 'com.microsoft.autoupdate2'):
            mau_prefs['channelname'] = CFPreferencesCopyAppValue('ChannelName', 'com.microsoft.autoupdate2')
        else:
            mau_prefs['channelname'] = "Production"
    
        if 'HowToCheck' in mau_plist:
            mau_prefs['howtocheck'] = mau_plist['HowToCheck']
        elif CFPreferencesCopyAppValue('HowToCheck', 'com.microsoft.autoupdate2'):
            mau_prefs['howtocheck'] = CFPreferencesCopyAppValue('HowToCheck', 'com.microsoft.autoupdate2')
    
        if 'ManifestServer' in mau_plist:
            mau_prefs['manifestserver'] = mau_plist['ManifestServer']
        elif CFPreferencesCopyAppValue('ManifestServer', 'com.microsoft.autoupdate2'):
            mau_prefs['manifestserver'] = CFPreferencesCopyAppValue('ManifestServer', 'com.microsoft.autoupdate2')
    
        if 'LastUpdate' in mau_plist:
            if mau_plist['LastUpdate'] != "Dec 29, 1 at 7:03:58 PM":
                pattern = '%b %d, %Y, %I:%M:%S %p'
                mau_prefs['lastcheckforupdates'] = int(time.mktime(time.strptime(mau_plist['LastUpdate'].replace(" at ", ", "), pattern)))
        elif CFPreferencesCopyAppValue('LastUpdate', 'com.microsoft.autoupdate2'):
            if CFPreferencesCopyAppValue('LastUpdate', 'com.microsoft.autoupdate2') != "Dec 29, 1 at 7:03:58 PM":
                pattern = '%b %d, %Y, %I:%M:%S %p'
                mau_prefs['lastcheckforupdates'] = int(time.mktime(time.strptime(mau_plist['LastUpdate'].replace(" at ", ", "), pattern)))
    
        if 'LastService' in mau_plist:
            mau_prefs['lastservice'] = mau_plist['LastService']
        elif CFPreferencesCopyAppValue('LastService', 'com.microsoft.autoupdate2'):
            mau_prefs['lastservice'] = CFPreferencesCopyAppValue('LastService', 'com.microsoft.autoupdate2')
        
        if 'EnableCheckForUpdatesButton' in mau_plist:
            mau_prefs['enablecheckforupdatesbutton'] = to_bool(mau_plist['EnableCheckForUpdatesButton'])
        elif CFPreferencesCopyAppValue('EnableCheckForUpdatesButton', 'com.microsoft.autoupdate2'):
            mau_prefs['enablecheckforupdatesbutton'] = to_bool(CFPreferencesCopyAppValue('EnableCheckForUpdatesButton', 'com.microsoft.autoupdate2'))
        else:
            mau_prefs['enablecheckforupdatesbutton'] = 1
            
        if 'SendAllTelemetryEnabled' in mau_plist:
            mau_prefs['sendalltelemetryenabled'] = to_bool(mau_plist['SendAllTelemetryEnabled'])
        elif CFPreferencesCopyAppValue('SendAllTelemetryEnabled', 'com.microsoft.autoupdate2'):
            mau_prefs['sendalltelemetryenabled'] = to_bool(CFPreferencesCopyAppValue('SendAllTelemetryEnabled', 'com.microsoft.autoupdate2'))
        else:
            mau_prefs['sendalltelemetryenabled'] = 1
            
        if 'DisableInsiderCheckbox' in mau_plist:
            mau_prefs['disableinsidercheckbox'] = to_bool(mau_plist['DisableInsiderCheckbox'])
        elif CFPreferencesCopyAppValue('DisableInsiderCheckbox', 'com.microsoft.autoupdate2'):
            mau_prefs['disableinsidercheckbox'] = to_bool(CFPreferencesCopyAppValue('DisableInsiderCheckbox', 'com.microsoft.autoupdate2'))
        else:
            mau_prefs['disableinsidercheckbox'] = 0
            
        if 'StartDaemonOnAppLaunch' in mau_plist:
            mau_prefs['startdaemononapplaunch'] = to_bool(mau_plist['StartDaemonOnAppLaunch'])
        elif CFPreferencesCopyAppValue('StartDaemonOnAppLaunch', 'com.microsoft.autoupdate2'):
            mau_prefs['startdaemononapplaunch'] = to_bool(CFPreferencesCopyAppValue('StartDaemonOnAppLaunch', 'com.microsoft.autoupdate2'))
        else:
            mau_prefs['startdaemononapplaunch'] = 1
        
        if os.path.exists('/Library/PrivilegedHelperTools/com.microsoft.autoupdate.helper'):
            mau_prefs['mau_privilegedhelpertool'] = 1 
        else:
            mau_prefs['mau_privilegedhelpertool'] = 0
            
        if 'Applications' in mau_plist:
            mau_prefs['registeredapplications'] = process_registered_apps(mau_config)  

        return mau_prefs
    except Exception:
        return {}
    
def vl_license_detect():
# Detect if there is a volumne license installed and what kind it is

    if os.path.exists('/Library/Preferences/com.microsoft.office.licensingV2.plist'):
        office_vl = open('/Library/Preferences/com.microsoft.office.licensingV2.plist').read()

        if 'A7vRjN2l/dCJHZOm8LKan11/zCYPCRpyChB6lOrgfi' in office_vl:
            vl_license = "Office 2019 Volume License"
        elif 'Bozo+MzVxzFzbIo+hhzTl4JKv18WeUuUhLXtH0z36s' in office_vl:
            vl_license = "Office 2019 Preview Volume License"
        elif 'A7vRjN2l/dCJHZOm8LKan1Jax2s2f21lEF8Pe11Y+V' in office_vl:
            vl_license = "Office 2016 Volume License"
        elif 'DrL/l9tx4T9MsjKloHI5eX' in office_vl:
            vl_license = "Office 2016 Home and Business License"
        elif 'C8l2E2OeU13/p1FPI6EJAn' in office_vl:
            vl_license = "Office 2016 Home and Student License"
        elif 'Bozo+MzVxzFzbIo+hhzTl4m' in office_vl:
            vl_license = "Office 2019 Home and Business License"
        elif 'Bozo+MzVxzFzbIo+hhzTl4j' in office_vl:
            vl_license = "Office 2019 Home and Student License"

        return {"vl_license_type":vl_license}
    
    elif os.path.exists('/Library/Preferences/com.microsoft.office.licensing.plist'):
        office_vl = open('/Library/Preferences/com.microsoft.office.licensing.plist').read()
        
        if 'A7vRjN2l/dCJHZOm8LKan1E3WP6ExkrygJtGyujbPR' in office_vl:
            vl_license = "Office 2011 Volume License"
        else:
            vl_license = "Office 2011 License"
            
        return {"vl_license_type":vl_license}  
    else:
        return {}
        
def o365_license_detect():
# Check all users' home folders for Office 365 license

    o365_count = 0
    o365_detect = 0
    
    # Get all users' home folders
    cmd = ['dscl', '.', '-readall', '/Users', 'NFSHomeDirectory']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
            stdin=subprocess.PIPE,
            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    
    # Check in all users' home folders for Office 365 license
    for user in output.split('\n'):
        if 'NFSHomeDirectory' in user and '/var/empty' not in user:
            userpath1 = user.replace("NFSHomeDirectory: ", "")+'/Library/Group Containers/UBF8T346G9.Office/com.microsoft.Office365.plist'
            userpath2 = user.replace("NFSHomeDirectory: ", "")+'/Library/Group Containers/UBF8T346G9.Office/com.microsoft.e0E2OUQxNUY1LTAxOUQtNDQwNS04QkJELTAxQTI5M0JBOTk4O.plist'
            userpath3 = user.replace("NFSHomeDirectory: ", "")+'/Library/Group Containers/UBF8T346G9.Office/e0E2OUQxNUY1LTAxOUQtNDQwNS04QkJELTAxQTI5M0JBOTk4O'
            
            if (os.path.exists(userpath1)) or (os.path.exists(userpath2)) or (os.path.exists(userpath3)):
                o365_count = o365_count + 1
                o365_detect = 1

    return {"o365_license_count":o365_count,"o365_detected":o365_detect}
     
def shared_o365_license_detect():
# Check if there is a shared Office 365 license in use

    if (os.path.exists("/Library/Application Support/Microsoft/Office365/com.microsoft.Office365.plist")):
        shared_o365 = {"shared_o365_license":1}
    else:
        shared_o365 = {"shared_o365_license":0}
    
    return shared_o365   
    
def get_app_data(app_path):
    
    # Read in Info.plist for processing 
    try:
        if os.path.exists(app_path+"/Contents/Info.plist"):
            info_plist = FoundationPlist.readPlist(app_path+"/Contents/Info.plist")
        elif ( "Excel" in app_path or "Outlook" in app_path or "PowerPoint" in app_path or "Word" in app_path ) and os.path.exists(app_path.replace("Applications", "Applications/Microsoft Office 2011/")+"/Contents/Info.plist"):
            info_plist = FoundationPlist.readPlist(app_path.replace("Applications", "Applications/Microsoft Office 2011/")+"/Contents/Info.plist")
        else:
             return {}
            
        app_name = app_path.split("/")[-1].split(".")[0].replace("Microsoft ", "").replace(" ", "_").lower()
        
        app_data = {}
        if "remote_desktop" in app_name or "onedrive" in app_name or "teams" in app_name:
            app_data[app_name+'_app_version'] = info_plist['CFBundleShortVersionString']
        else:
            app_data[app_name+'_app_version'] = info_plist['CFBundleVersion']
            
        gencheck = '.'.join(info_plist['CFBundleShortVersionString'].split(".")[:2])
        
        # Check generation of Office
        if (14.7 >= float(gencheck)) and ( "excel" in app_name or "outlook" in app_name or "onenote" in app_name or "powerpoint" in app_name or "word" in app_name ):
            app_data[app_name+'_office_generation'] = 2011
        elif (15.11 <= float(gencheck) <= 16.16) and ( "excel" in app_name or "outlook" in app_name or "onenote" in app_name or "powerpoint" in app_name or "word" in app_name ):
            app_data[app_name+'_office_generation'] = 2016
        elif (16.17 <= float(gencheck)) and ( "excel" in app_name or "outlook" in app_name or "onenote" in app_name or "powerpoint" in app_name or "word" in app_name ):
            app_data[app_name+'_office_generation'] = 2019
        
        # Check if app is a Mac App Store app
        if os.path.exists(app_path+"/Contents/_MASReceipt") and "autoupdate" not in app_name and "skype" not in app_name:
            app_data[app_name+'_mas'] = 1
        elif (( "excel" in app_name or "outlook" in app_name or "powerpoint" in app_name or "word" in app_name ) and app_data[app_name+'_office_generation'] == 2011) or "autoupdate" in app_name or "skype" in app_name:
            # Do nothing as app is an Office 2011 app
            pass
        else:
            app_data[app_name+'_mas'] = 0
            
        return app_data
    except Exception:
        return {}

def to_bool(s):
    if s == True:
        return 1
    else:
        return 0    
    
def merge_two_dicts(x, y):
    z = x.copy()
    z.update(y)
    return z

def get_uid(username):
    cmd = ['/usr/bin/id', '-u', username]
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    output = output.strip()
    return int(output)

def get_gid(username):
    cmd = ['/usr/bin/id', '-gr', username]
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    output = output.strip()
    return int(output)

def demote():
# Get user id and group id for msupdate command
    def result():
        # Attempt to get currently logged in person
        username = (SCDynamicStoreCopyConsoleUser(None, None, None) or [None])[0]
        username = [username,""][username in [u"loginwindow", None, u""]]
        # If we can't get the current user, get last console login
        if username == "":
            username = get_last_user()
        os.setgid(get_gid(username))
        os.setuid(get_uid(username))
    return result

def get_last_user():

    # local constants
    setutxent_wtmp = c.setutxent_wtmp
    setutxent_wtmp.restype = None
    getutxent_wtmp = c.getutxent_wtmp
    getutxent_wtmp.restype = POINTER(utmpx)
    endutxent_wtmp = c.setutxent_wtmp
    endutxent_wtmp.restype = None
    # initialize
    setutxent_wtmp(0)
    entry = getutxent_wtmp()
    while entry:
        e = entry.contents
        entry = getutxent_wtmp()
        if (e.ut_type == 7 and e.ut_line == "console" and e.ut_user != "root" and e.ut_user != ""):
            endutxent_wtmp()
            return e.ut_user
    
def get_user_path():
    
    # Attempt to get currently logged in person
    username = (SCDynamicStoreCopyConsoleUser(None, None, None) or [None])[0]
    username = [username,""][username in [u"loginwindow", None, u""]]
    
    # If we can't get the current user, get last console login
    if username == "":
        username = get_last_user()
                    
    # Get the user's home folder
    cmd = ['dscl', '.', '-read', '/Users/'+username, 'NFSHomeDirectory']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()                
                    
    return output.split(" ")[1].strip()
        
def main():
    """Main"""
    # Create cache dir if it does not exist
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    if not os.path.exists(cachedir):
        os.makedirs(cachedir)

    # Skip manual check
    if len(sys.argv) > 1:
        if sys.argv[1] == 'manualcheck':
            print 'Manual check: skipping'
            exit(0)
            
    # Get results
    result = dict()

    result = merge_two_dicts(get_user_config(), get_mau_prefs())
    result = merge_two_dicts(result, vl_license_detect())
    result = merge_two_dicts(result, o365_license_detect())
    result = merge_two_dicts(result, shared_o365_license_detect())
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft Excel.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft PowerPoint.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft Outlook.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft OneNote.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft Remote Desktop.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft Word.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/OneDrive.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft Teams.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Skype for Business.app"))
    result = merge_two_dicts(result, get_app_data("/Library/Application Support/Microsoft/MAU2.0/Microsoft AutoUpdate.app"))

    # Write office results to cache
    output_plist = os.path.join(cachedir, 'ms_office.plist')
    FoundationPlist.writePlist(result, output_plist)
#    print FoundationPlist.writePlistToString(result)

if __name__ == "__main__":
    main()
