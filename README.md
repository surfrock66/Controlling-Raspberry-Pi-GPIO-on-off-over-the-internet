Controlling-Raspberry-Pi-GPIO-on-off-over-the-internet
======================================
###Licensed under the MIT license.

This code allows you to control Raspberry Pi's GPIO Via the internet, specifically with the intention of controlling RGB LED Strips.  The idea is that an HTML5 Canvas of an image provides the RGB sample set, and based on a user's click, a color sample is detected, converted to RGB, and sent to the Pi's GPIO where it's converted to signals for the LED Light Strips.

I have provided a bunch of sample RGB images, however any image you desire can be used.  Resizing images in html breaks the canvas, so make sure your source image is the correct size for your needs.  Additionally, avoid compressed images because the RGB information will be inconsistent.

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

LAMP how to: https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-debian

##Setup And Install

1. Place all the Files in the "For Web Server" folder in the Root of your web server. (Default is /var/www/html/)
2. Select the desired PNG image you want to use in your interface, rename it to "RGB.png".  Remove other RGB-*.png files if desired.
3. Ensure the web files are owned by the web user: 

    sudo chown www-data:www-data /var/www/html/RGB*
    
4. Ensure the files have correct permissions: 

    sudo chmod 644 /var/www/html/RGB*

5. Place RGB.sh in the "For Pi" somewhere on the Raspberry Pi. (For example, your home directory, ~/)
6. Edit the RGB.sh file with the GPIO Pins you're using.  Modify the polling interval if needed.
7. Set the RGB.sh script to run at boot by running the following code:

    sudo cp ~/RGB.sh /etc/init.d/RGB
    sudo chmod 755 /etc/init.d/RGB
    sudo update-rc.d RGB defaults
    
8. To change the on off status go to http://raspberry-pi-ip/RGB.php
