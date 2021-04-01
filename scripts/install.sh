#!/bin/bash

# ms_office controller
CTL="${BASEURL}index.php?/module/ms_office/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/ms_office.py" -o "${MUNKIPATH}preflight.d/ms_office.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/ms_office.py"

	# Set preference to include this file in the preflight check
	setreportpref "ms_office" "${CACHEPATH}ms_office.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/ms_office.py"

	# Signal that we had an error
	ERR=1
fi
