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
#               The current list of supported modes:
#               Solid	Sets the lights to a solid color selected from
#			the input file
#               Strobe	Sets the lights to blink a solid color on and off
#			selected from the input file 
#               Fade	Adjustable fade of all RGB colors. Speed and
#                       smoothness can be adjusted below
#               Step	Steps between 6 RGB color combinations
#               Party	Random color choise and flashing
#
#		I suggest running this as a command on boot. To do that:
#		#> sudo mv RGB.sh /etc/RGB.sh
#		#> sudo chmod 755 /etc/RGB.sh
#               #> su -
#               #> crontab -e 
#		#> # add "@reboot /etc/RGB.sh"
#

PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/opt/bin

. /lib/init/vars.sh
. /lib/lsb/init-functions

# USER CONFIGURATION OPTIONS
gpioPinRed=24
gpioPinGreen=25
gpioPinBlue=22
defaultPollingInterval=".25"	# Value In Seconds

# Initialize some global varibles
count=0
gpiopins=($gpioPinRed $gpioPinGreen $gpioPinBlue)
pollingInterval=$defaultPollingInterval

# Initialize global variables for fade macro
fade=(255 0 0)

# Initialize global variables for step macro
stepR=(255 255 0 0 0 255)
stepG=(0 255 255 255 0 0)
stepB=(0 0 0 255 255 255)

#Initialize global variables for party macro
partyVals=(0 63 127 191 255)
partyR=0
partyG=0
partyB=0

while true; do
    i=0
    for j in `wget -qO- http://localhost/RGB.txt`; do
        readline[$i]=$j
        i=$(($i+1))
    done
    if [ ${readline[0]} == "Strobe" ]; then
        pollingInterval=".125"
        if [ ${readline[1]} == "0" ] && [ ${readline[2]} == "0" ] && [ ${readline[3]} == "0" ]; then
            strobeR=(255 0)
            strobeG=(255 0)
            strobeB=(255 0)
        else
            strobeR=(${readline[1]} 0)
            strobeG=(${readline[2]} 0)
            strobeB=(${readline[3]} 0)
        fi
        k=$(($count%2))
        pigs p ${gpiopins[0]} ${strobeR[$k]} p ${gpiopins[1]} ${strobeG[$k]} p ${gpiopins[2]} ${strobeB[$k]}
        count=$(($count+1))
    elif [ ${readline[0]} == "Fade" ]; then
        pollingInterval="0"
        steps="5" #Possible values are factors of 255: 3, 5, 15, 17, 51, 85
        i=0
        while [ $i -lt 15 ]; do
            if [ ${fade[0]} -eq 255 ] && [ ${fade[2]} -eq 0 ] && [ ${fade[1]} -lt 255 ]; then
                fade[1]=$(( fade[1] + steps ))
            elif [ ${fade[1]} -eq 255 ] && [ ${fade[2]} -eq 0 ] && [ ${fade[0]} -gt 0 ]; then
                fade[0]=$(( fade[0] - steps ))
            elif [ ${fade[0]} -eq 0 ] && [ ${fade[1]} -eq 255 ] && [ ${fade[2]} -lt 255 ]; then
                fade[2]=$(( fade[2] + steps ))
            elif [ ${fade[0]} -eq 0 ] && [ ${fade[2]} -eq 255 ] && [ ${fade[1]} -gt 0 ]; then
                fade[1]=$(( fade[1] - steps ))
            elif [ ${fade[1]} -eq 0 ] && [ ${fade[2]} -eq 255 ] && [ ${fade[0]} -lt 255 ]; then
                fade[0]=$(( fade[0] + steps ))
            elif [ ${fade[0]} -eq 255 ] && [ ${fade[1]} -eq 0 ] && [ ${fade[2]} -gt 0 ]; then
                fade[2]=$(( fade[2] - steps ))
            fi
            pigs p ${gpiopins[0]} ${fade[0]} p ${gpiopins[1]} ${fade[1]} p ${gpiopins[2]} ${fade[2]}
            i=$(($i+1))
        done
    elif [ ${readline[0]} == "Step" ]; then
        pollingInterval="1"
        k=$(($count%6))
        count=$(($count+1))
        pigs p ${gpiopins[0]} ${stepR[$k]} p ${gpiopins[1]} ${stepG[$k]} p ${gpiopins[2]} ${stepB[$k]}
    elif [ ${readline[0]} == "Solid" ]; then
        pollingInterval=$defaultPollingInterval
        count=0
        pigs p ${gpiopins[0]} ${readline[1]} p ${gpiopins[1]} ${readline[2]} p ${gpiopins[2]} ${readline[3]}
    elif [ ${readline[0]} == "Party" ]; then
         count=0
        while [ $partyR -eq 0 ] && [ $partyG -eq 0 ] && [ $partyB -eq 0 ] || [ $count -eq 0 ]; do
            partyR=${partyVals[$(($RANDOM%5))]}
            partyG=${partyVals[$(($RANDOM%5))]}
            partyB=${partyVals[$(($RANDOM%5))]}
            count=$(($count+1))
        done
        pigs p ${gpiopins[0]} $partyR p ${gpiopins[1]} $partyG p ${gpiopins[2]} $partyB
    else
        pollingInterval=$defaultPollingInterval
        count=0
        pigs p ${gpiopins[0]} 0 p ${gpiopins[1]} 0 p ${gpiopins[2]} 0
    fi
    sleep $pollingInterval
done
