Controlling-Raspberry-Pi-GPIO-on-off-over-the-internet
======================================
###Licensed under the MIT license.

This code allows you to control Raspberry Pi's GPIO Via the internet, specifically with the intention of controlling RGB LED Strips.  The idea is that an HTML5 Canvas of an image provides the RGB sample set, and based on a user's click, a color sample is detected, converted to RGB, and sent to the Pi's GPIO where it's converted to signals for the LED Light Strips.

##Packages Needed and Web Server
You will need pigpiod, available here: http://abyz.co.uk/rpi/pigpio/index.html
Install Instructions:
    rm pigpio.tar
    sudo rm -rf PIGPIO
    wget abyz.co.uk/rpi/pigpio/pigpio.tar
    tar xf pigpio.tar
    cd PIGPIO
    make -j4
    sudo make install
    
To run pigpiod at startup, add the following to the root crontab with "sudo crontab -e":
    @reboot              /usr/local/bin/pigpiod

You need a LAMP stack/web server on the pi:

    sudo apt-get install apache2 php5 mysql-client mysql-server vsftpd


If you want to run the web server locally on the pi you will need to install and configure "LAMP" or "LEMP" with out the MySQL, if not a regular web server that works with php will do fine

LAMP how to: https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-debian

##What to do

1. Place all the Files in the "For Web Server" folder in the Root of your web server.

2. Place All the Files in the "For Pi" in to the Raspberry Pi.

4. On the Pi in the Poll.py file find "http://YourWebsite.com/test.php" that are lines 42,46 and replace the it with the URL that leads to test.php file on your web server.

5. CD to the file containing the python scrip "poll.py"

6. start the python script with sudo as you are using the the GPIO

7. To change the on off status go to http://yourwebsite.com/contoller.php

##Extras
If you are going and testing and changing things constantly and you are sick of seeing the GPIO warning you can uncomment line 23. This will disable the warnings but be careful if you are running more then 2 things at once. Or add

    GPIO.setwarnings(False)
