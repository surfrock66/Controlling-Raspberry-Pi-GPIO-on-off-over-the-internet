#!/bin/bash
# 
# Title:	RGB.sh
# Author:	surfrock66
# E-Mail:	surfrock66@surfrock66.com
# Website:	https://github.com/surfrock66/RPi-RGB-Web
# Description:	This script polls a file on the local web host and
#		updates the local GPIO based on the contents of that
#		file.  This is intended to direct R, G, and B color 
#		values to addressable GPIO pins.
#
#		This script is intended to be run as any user, and
#		will reference the pigpiod daemon which is a dependency.
#		
#		The GPIO pins are modifiable, and the polling interval
#		can be modified.
#
#		I suggest running this as a command on boot. To do that:
#		#> sudo mv RGB.sh /etc/init.d/RGB
#		#> sudo chmod 755 /etc/init.d/RGB
#		#> sudo update-rc.d RGB defaults
#
### BEGIN INIT INFO
# Provides:          <your script name>
# Required-Start:    $all
# Required-Stop:     
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Manage my cool stuff
### END INIT INFO

PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/opt/bin

. /lib/init/vars.sh
. /lib/lsb/init-functions

gpioPinRed=24
gpioPinGreen=25
gpioPinBlue=22
pollingInterval=".25"	# Value In Seconds


case "$1" in
  start)
    log_begin_msg "Starting listener for RPi-RGB-Web"
    gpiopins=($gpioPinRed $gpioPinGreen $gpioPinBlue)
    while true; do
        i=0
        for j in `wget -qO- http://localhost/RGB.txt`; do
            pigs p ${gpiopins[$i]} $j
            i=$(($i+1))
        done
        sleep $pollingInterval
    done
    log_end_msg $?
    exit 0
    ;;
  stop)
    log_begin_msg "Stopping listener for RPi-RGB-Web"
    killall RGB
    log_end_msg $?
    exit 0
    ;;
  *)
    echo "Usage: /etc/init.d/RGB {start|stop}"
    exit 1
    ;;
esac
