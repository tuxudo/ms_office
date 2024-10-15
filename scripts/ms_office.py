#!/usr/local/munkireport/munkireport-python3

# Some of the user elements are from the user_sessions.py script
# made by Clayton Burlison and Michael Lynn
# Other parts of the script from Paul Bowden's scripts found on his Github


import subprocess
import os
import sys
import time
import platform
import string

sys.path.insert(0, '/usr/local/munki')
sys.path.insert(0, '/usr/local/munkireport')

from munkilib import FoundationPlist
from CoreFoundation import CFPreferencesCopyAppValue

from SystemConfiguration import SCDynamicStoreCopyConsoleUser

def get_mau_prefs():
    # Get the MAU's config as seen from the current or last person logged in
    # because some settings are user specific, then process global and profile
    # level settings

    mau_config_items = {}
    mau_config_items['msupdate_check_enabled'] = 0 # This is disabled because it doesn't really work

    try:
        mau_config = FoundationPlist.readPlist(get_user_path()+"/Library/Preferences/com.microsoft.autoupdate2.plist")

        # Process user level config
        for item in mau_config:
            if item == 'UpdateCache':
                mau_config_items['updatecache'] = mau_config[item]
            elif item == 'ManifestServer':
                mau_config_items['manifestserver'] = mau_config[item]
            elif item == 'AutoUpdateVersion':
                mau_config_items['autoupdateversion'] = mau_config[item]
            elif item == 'ChannelName':
                mau_config_items['channelname'] = mau_config[item]
                print(mau_config[item])
            elif item == 'HowToCheck':
                mau_config_items['howtocheck'] = mau_config[item]
            elif item == 'LastCheckForUpdates' or item == 'LastUpdate':
                if mau_config[item] != "Dec 29, 1 at 7:03:58 PM":
                    pattern = '%Y-%m-%d %H:%M:%S +0000'
                    mau_config_items['lastcheckforupdates'] = int(time.mktime(time.strptime(str(mau_config[item]).replace(" at ", ", "), pattern)))
            elif item == 'StartDaemonOnAppLaunch':
                mau_config_items['startdaemononapplaunch'] = to_bool(mau_config[item])

            elif item == 'Applications':
                mau_config_items['registeredapplications'] = process_registered_apps(mau_config['Applications'])

            # # Add in update information if enabled
            # msupdate_check_enabled = to_bool(CFPreferencesCopyAppValue('msupdate_check_enabled', 'org.munkireport.ms_office'))
            # if os.path.exists('/Library/Application Support/Microsoft/MAU2.0/Microsoft AutoUpdate.app/Contents/MacOS/msupdate') and msupdate_check_enabled == 1:
            #     mau_config_items = get_msupdate_update_check(mau_config_items)
            #     mau_config_items['msupdate_check_enabled'] = 1
            # else:
            #     mau_config_items['msupdate_check_enabled'] = 0
    except:
        pass

    try:
        # After processing local user's prefs, process global to get profile managed prefs
        UpdateCache = CFPreferencesCopyAppValue('UpdateCache', 'com.microsoft.autoupdate2')
        if UpdateCache is not None:
            mau_config_items['updatecache'] = UpdateCache

        ManifestServer = CFPreferencesCopyAppValue('ManifestServer', 'com.microsoft.autoupdate2')
        if ManifestServer is not None:
            mau_config_items['manifestserver'] = ManifestServer

        ChannelName = CFPreferencesCopyAppValue('ChannelName', 'com.microsoft.autoupdate2')
        if ChannelName is not None:
            mau_config_items['channelname'] = ChannelName
        else:
            mau_config_items['channelname'] = "Current"

        HowToCheck = CFPreferencesCopyAppValue('HowToCheck', 'com.microsoft.autoupdate2')
        if HowToCheck is not None:
            mau_config_items['howtocheck'] = HowToCheck

        StartDaemonOnAppLaunch = CFPreferencesCopyAppValue('StartDaemonOnAppLaunch', 'com.microsoft.autoupdate2')
        if StartDaemonOnAppLaunch is not None:
            mau_config_items['startdaemononapplaunch'] = to_bool(StartDaemonOnAppLaunch)
        else:
            mau_config_items['startdaemononapplaunch'] = 1

        DisableInsiderCheckbox = CFPreferencesCopyAppValue('DisableInsiderCheckbox', 'com.microsoft.autoupdate2')
        if DisableInsiderCheckbox is not None:
            mau_config_items['disableinsidercheckbox'] = to_bool(DisableInsiderCheckbox)
        else:
            mau_config_items['disableinsidercheckbox'] = 0

        SendAllTelemetryEnabled = CFPreferencesCopyAppValue('SendAllTelemetryEnabled', 'com.microsoft.autoupdate2')
        if SendAllTelemetryEnabled is not None:
            mau_config_items['sendalltelemetryenabled'] = to_bool(SendAllTelemetryEnabled)
        else:
            mau_config_items['sendalltelemetryenabled'] = 1

        EnableCheckForUpdatesButton = CFPreferencesCopyAppValue('EnableCheckForUpdatesButton', 'com.microsoft.autoupdate2')
        if EnableCheckForUpdatesButton is not None:
            mau_config_items['enablecheckforupdatesbutton'] = to_bool(EnableCheckForUpdatesButton)
        else:
            mau_config_items['enablecheckforupdatesbutton'] = 1

        LastUpdate = CFPreferencesCopyAppValue('LastUpdate', 'com.microsoft.autoupdate2')
        if LastUpdate is not None and LastUpdate != "Dec 29, 1 at 7:03:58 PM":
            pattern = '%b %d, %Y, %I:%M:%S %p'
            mau_config_items['lastcheckforupdates'] = int(time.mktime(time.strptime(LastUpdate.replace(" at ", ", "), pattern)))

        try:
            Applications = CFPreferencesCopyAppValue('Applications', 'com.microsoft.autoupdate2')
            if Applications is not None:
                mau_config_items['registeredapplications'] = merge_two_dicts(process_registered_apps(Applications), mau_config_items['registeredapplications'])
        except:
            pass

        # Check if the Privileged Helper Tool is installed
        if os.path.exists('/Library/PrivilegedHelperTools/com.microsoft.autoupdate.helper'):
            mau_config_items['mau_privilegedhelpertool'] = 1
        else:
            mau_config_items['mau_privilegedhelpertool'] = 0

    except:
        pass

    return mau_config_items

def process_registered_apps(apps):
    registered_apps = {}

    for app in apps:
        app_name = app.split("/")[-1].split(".")[0]
        if app_name != "":
            if app_name == "com":
                continue
            registered_apps[app_name] = {}
            for item in apps[app]:
                if item == 'Application ID':
                    registered_apps[app_name]['application_id'] = apps[app][item]
                    registered_apps[app_name]['applicationpath'] = app
                    try:
                        info_plist = FoundationPlist.readPlist(app+"/Contents/Info.plist")

                        app_name_lower = app_name.lower()
                        if "remote_desktop" in app_name_lower or "windows_app" in app_name_lower or "onedrive" in app_name_lower or "teams" in app_name_lower or "company" in app_name_lower or "edge" in app_name_lower:
                            registered_apps[app_name]['versionondisk'] = info_plist['CFBundleShortVersionString']
                        else:
                            registered_apps[app_name]['versionondisk'] = info_plist['CFBundleVersion']

                       # registered_apps[app_name]['versionondisk'] = info_plist['CFBundleVersion']
                    except Exception:
                        pass
                elif item == 'Last Update Seen':
                    if apps[app][item] != "Dec 29, 1 at 7:03:58 PM":
                        pattern = '%Y-%m-%d %H:%M:%S +0000'
                        registered_apps[app_name]['lastupdateseen'] = str(int(time.mktime(time.strptime(str(apps[app][item]).replace(" at ", ", "), pattern))))

    return registered_apps

def get_msupdate_update_check(mau_update_items):
    # Quickly check for updates as the current or last person logged in
    # This is disabled and unused as of 2024-10-15
    try:
        username=current_user()

        cmd = ['/bin/launchctl', 'asuser', get_uid(username), '/usr/bin/sudo', '-u', username, '/Library/Application Support/Microsoft/MAU2.0/Microsoft AutoUpdate.app/Contents/MacOS/msupdate', '-l', '-f','plist']

        proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                                stdin=subprocess.PIPE,
                                stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        (output, unused_error) = proc.communicate()

        mau_update = FoundationPlist.readPlistFromString(output.split(b"\n",2)[2])

        for app in mau_update:
            app_name = app['ApplicationToBeUpdatedPath'].split("/")[-1].split(".")[0]
            for item in app:
                if item == 'Application ID':
                    mau_update_items['registeredapplications'][app_name]['application_id'] = app[item]
               # elif item == 'ApplicationToBeUpdatedPath':
               #     mau_update_items['registeredapplications'][app_name]['applicationpathtobeupdated'] = app[item]
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

def vl_license_detect():
    # Detect if there is a volumne license installed and what kind it is

    if os.path.exists('/Library/Preferences/com.microsoft.office.licensingV2.plist'):
        try:
            office_vl = open('/Library/Preferences/com.microsoft.office.licensingV2.plist', "r").read()
        except:
            office_vl = open('/Library/Preferences/com.microsoft.office.licensingV2.plist', "rb").read().decode("utf-8", errors="ignore")

        if 'Bozo+MzVxzFzbIo+hhzTl41DwAFJEitHSg5IiCEeuI' in office_vl:
            vl_license = "Office 2024 Volume License"
        elif 'Bozo+MzVxzFzbIo+hhzTl4hlrSMvpMqJ/gUHjvPE8/' in office_vl:
            vl_license = "Office 2024 Preview Volume License"
        elif 'Bozo+MzVxzFzbIo+hhzTl4xkRZSjOUX8J8nIgpXuMa' in office_vl:
            vl_license = "Office 2021 Volume License"
        elif 'Bozo+MzVxzFzbIo+hhzTl43O7w5oMsJ7M3Q4vhvz/j' in office_vl:
            vl_license = "Office 2021 Preview Volume License"
        elif 'A7vRjN2l/dCJHZOm8LKan11/zCYPCRpyChB6lOrgfi' in office_vl:
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

        try:
            office_vl = open('/Library/Preferences/com.microsoft.office.licensing.plist', "r").read()
        except:
            office_vl = open('/Library/Preferences/com.microsoft.office.licensing.plist', "rb").read().decode("utf-8", errors="ignore")

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
    o365_user_accounts = ""

    # Get all users' home folders
    cmd = ['dscl', '.', '-readall', '/Users', 'NFSHomeDirectory']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
            stdin=subprocess.PIPE,
            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()

    # Check in all users' home folders for Office 365 license
    for user in output.decode("utf-8", errors="ignore").split('\n'):
        if 'NFSHomeDirectory' in user and '/var/empty' not in user:
            userpath1 = user.replace("NFSHomeDirectory: ", "")+'/Library/Group Containers/UBF8T346G9.Office/com.microsoft.Office365V2.plist'
            # userpath2 = user.replace("NFSHomeDirectory: ", "")+'/Library/Group Containers/UBF8T346G9.Office/Licenses/5'
            userpath3 = user.replace("NFSHomeDirectory: ", "")+'/Library/Group Containers/UBF8T346G9.Office/com.microsoft.O4kTOBJ0M5ITQxATLEJkQ40SNwQDNtQUOxATL1YUNxQUO2E0e.plist'
            userpath4 = user.replace("NFSHomeDirectory: ", "")+'/Library/Group Containers/UBF8T346G9.Office/O4kTOBJ0M5ITQxATLEJkQ40SNwQDNtQUOxATL1YUNxQUO2E0e'

            if (os.path.exists(userpath1)) or (os.path.exists(userpath3)) or (os.path.exists(userpath4)):
            # if (os.path.exists(userpath1)) or (os.path.exists(userpath2)) or (os.path.exists(userpath3)) or (os.path.exists(userpath4)):
                o365_count = o365_count + 1
                o365_detect = 1

                out = []
                for pref in get_user_prefs().split('\n'):
                    pl = FoundationPlist.readPlist(pref)
                    for item in pl:
                        if item == 'OfficeActivationEmailAddress':
                            out.append(pref.replace("/Library/Preferences/com.microsoft.office.plist", "").replace("/Users/", "") + " - " + (pl[item]))
                o365_user_accounts = ", ".join(out)

    return {"o365_license_count":o365_count,"o365_detected":o365_detect,"o365_user_accounts":o365_user_accounts}

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
            # Check for Office 2011 apps
            info_plist = FoundationPlist.readPlist(app_path.replace("Applications", "Applications/Microsoft Office 2011/")+"/Contents/Info.plist")
        elif ( "Microsoft Remote Desktop" in app_path) and os.path.exists(app_path.replace("Microsoft Remote Desktop", "Windows App")+"/Contents/Info.plist"):
            # Check for Windows App (ex-Microsoft Remote Desktop) what a silly name change
            info_plist = FoundationPlist.readPlist(app_path.replace("Microsoft Remote Desktop", "Windows App")+"/Contents/Info.plist")
        else:
             return {}

        app_name = app_path.split("/")[-1].split(".")[0].replace("Microsoft ", "").replace(" Beta", "").replace(" Canary", "").replace(" Dev", "").replace(" ", "_").lower()

        app_data = {}
        if "remote_desktop" in app_name or "windows_app" in app_name or "onedrive" in app_name or "teams" in app_name or "company" in app_name or "edge" in app_name:
            app_data[app_name+'_app_version'] = info_plist['CFBundleShortVersionString']
        else:
            app_data[app_name+'_app_version'] = info_plist['CFBundleVersion']

        gencheck = '.'.join(info_plist['CFBundleShortVersionString'].split(".")[:2])

        # Check generation of Office
        if (14.7 >= float(gencheck)) and ( "excel" in app_name or "outlook" in app_name or "onenote" in app_name or "powerpoint" in app_name or "word" in app_name ):
            app_data[app_name+'_office_generation'] = 2011
        elif (15.11 <= float(gencheck) <= 16.16) and ( "excel" in app_name or "outlook" in app_name or "onenote" in app_name or "powerpoint" in app_name or "word" in app_name ):
            app_data[app_name+'_office_generation'] = 2016
        elif (16.17 <= float(gencheck) <= 16.78) and ( "excel" in app_name or "outlook" in app_name or "onenote" in app_name or "powerpoint" in app_name or "word" in app_name ):
            app_data[app_name+'_office_generation'] = 2019
        elif (16.79 <= float(gencheck) <= 16.89) and ( "excel" in app_name or "outlook" in app_name or "onenote" in app_name or "powerpoint" in app_name or "word" in app_name ):
            app_data[app_name+'_office_generation'] = 2021
        elif (16.90 <= float(gencheck)) and ( "excel" in app_name or "outlook" in app_name or "onenote" in app_name or "powerpoint" in app_name or "word" in app_name ):
            app_data[app_name+'_office_generation'] = 2024

        # Check if app is a Mac App Store app
        if os.path.exists(app_path+"/Contents/_MASReceipt") and "autoupdate" not in app_name and "skype" not in app_name and "company" not in app_name and "edge" not in app_name and "defender" not in app_name and "yammer" not in app_name:
            app_data[app_name+'_mas'] = 1
        elif (( "excel" in app_name or "outlook" in app_name or "powerpoint" in app_name or "word" in app_name ) and app_data[app_name+'_office_generation'] == 2011) or "autoupdate" in app_name or "skype" in app_name or "company" in app_name or "edge" in app_name and "defender" in app_name and "yammer" in app_name:
            # Do nothing as app is an Office 2011 app or not in the app store
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

def current_user():

    # local constants
    username = (SCDynamicStoreCopyConsoleUser(None, None, None) or [None])[0]
    username = [username,""][username in ["loginwindow", None, ""]]

    # If we can't get the current user, get last console login
    if username == "":
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
    else:
        return username

def get_uid(username):
    cmd = ['/usr/bin/id', '-u', username]
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    return output.decode("utf-8", errors="ignore").strip()

def get_user_path():

    # Attempt to get currently logged in person
    username = (SCDynamicStoreCopyConsoleUser(None, None, None) or [None])[0]
    username = [username,""][username in ["loginwindow", None, ""]]
    
    # If we can't get the current user, get last console login
    if username == "":
        username = get_last_user()

    # Get the user's home folder
    cmd = ['dscl', '.', '-read', '/Users/'+username, 'NFSHomeDirectory']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()                

    return output.decode("utf-8", errors="ignore").split(" ")[1].strip()

def get_user_prefs():

    user_prefs = ""

    # Get all users' home folders
    cmd = ['dscl', '.', '-readall', '/Users', 'NFSHomeDirectory']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1,
                            stdin=subprocess.PIPE,
                            stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()
    
    for user in output.decode("utf-8", errors="ignore").split('\n'):
        if 'NFSHomeDirectory' in user and '/var/empty' not in user:
            user_pref  = user.replace("NFSHomeDirectory: ", "")+'/Library/Preferences/com.microsoft.office.plist'
            if os.path.isfile(user_pref):
                user_prefs = user_pref + "\n" + user_prefs  

    return user_prefs[:-1]

def main():

    # Get results
    result = dict()

    result = merge_two_dicts(get_mau_prefs(), vl_license_detect())
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
    result = merge_two_dicts(result, get_app_data("/Applications/Company Portal.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft Defender ATP.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Yammer.app"))

    # Edge has four different channels, get them in priority
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft Edge Canary.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft Edge Dev.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft Edge Beta.app"))
    result = merge_two_dicts(result, get_app_data("/Applications/Microsoft Edge.app"))
    result = merge_two_dicts(result, get_app_data("/Library/Application Support/Microsoft/MAU2.0/Microsoft AutoUpdate.app"))

    # Write office results to cache
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    output_plist = os.path.join(cachedir, 'ms_office.plist')
    FoundationPlist.writePlist(result, output_plist)
#    print FoundationPlist.writePlistToString(result)

if __name__ == "__main__":
    main()
