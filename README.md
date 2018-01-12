RPi-RGB-Web
======================================
###Licensed under the MIT license.

This code allows you to control Raspberry Pi's GPIO Via the internet, specifically with the intention of controlling RGB LED Strips.  The idea is that an HTML5 Canvas of an image provides the RGB sample set, and based on a user's click, a color sample is detected, converted to RGB, and sent to the Pi's GPIO where it's converted to signals for the LED Light Strips.

I have provided a bunch of sample RGB images, however any image you desire can be used.  Resizing images in html breaks the canvas, so make sure your source image is the correct size for your needs.  Additionally, avoid compressed images because the RGB information will be inconsistent.

Demonstration: [My Youtube Demo](https://www.youtube.com/watch?v=0ZzMoECYPew)

##Packages Needed and Web Server

You will need pigpiod, available here: [PIGPIOD homepage](http://abyz.co.uk/rpi/pigpio/index.html)

Install Instructions:

    rm pigpio.tar
    sudo rm -rf PIGPIO
    wget abyz.co.uk/rpi/pigpio/pigpio.tar
    tar xf pigpio.tar
    cd PIGPIO
    make -j4
    sudo make install
    
To run pigpiod at startup, add the following to the root crontab with "crontab -e" from a root terminal:

    @reboot              /usr/local/bin/pigpiod

You need a LAMP stack/web server on the pi:

    sudo apt-get install apache2 php mysql-client mysql-server vsftpd

LAMP how to: [DigitalOcean LAMP Guide](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-debian)

##Hardware Required

I've used the hardware outlined in this guide, and I'll summarize it here: [Project which inspired the circuit and pigs code](http://popoklopsi.github.io/RaspberryPi-LedStrip/#!/)

Parts needed:

* [Amazon: MOSFET IRLZ34N](https://www.amazon.com/MAJOR-BRANDS-MOSFET-IRLZ34N-TO-220ABN-CHANNEL/dp/B00CHTJOSG)

Parts that are helpful:

* [Aamzon: Female Breadboard Wire](https://www.amazon.com/Elegoo-120pcs-Multicolored-Breadboard-arduino/dp/B01EV70C78/ref=sr_1_2?ie=UTF8&qid=1474174642&sr=8-2)
* [Amazon: LED Strip Connector](https://www.amazon.com/ZITRADES-Light-Strips-Female-Connector/dp/B00D0Y8SG6/ref=sr_1_7?ie=UTF8&qid=1474175095&sr=8-7)
* [Amazon: Female Barrel Plug](https://www.amazon.com/gp/product/B01G6EAZOO/ref=oh_aui_detailpage_o03_s00?ie=UTF8&psc=1)

The LED strips I use, since I got a ton of them at costco years ago:

* [Amazon: Sylvania Mosiac LED Strip Kit](https://www.amazon.com/Sylvania-72350-Flexible-Mosaic-Starter/dp/B00PWXNVRO)
    
How to assemble the support circuit (you can use whatever GPIO pins you want):

![LED Circuit](http://popoklopsi.github.io/RaspberryPi-LedStrip/img/rgb/small/pi_4.png)

##Setup And Install

1. Place all the Files in the "For Web Server" folder in the Root of your web server. (Default is /var/www/html/)
2. Select the desired PNG image you want to use in your interface, rename it to "RGB.png".  Remove other RGB-*.png files if desired.  I have included the base .xcf file, so swatches can be rearranged or modified in GIMP.
3. Ensure the web files are owned by the web user: 
  * sudo chown www-data:www-data /var/www/html/RGB*
4. Ensure the files have correct permissions: 
  * sudo chmod 644 /var/www/html/RGB*
5. Place RGB.sh in the "For Pi" somewhere on the Raspberry Pi. (For example, your home directory, ~/)
6. Edit the RGB.sh file with the GPIO Pins you're using.  Modify the polling interval if needed.
7. Set the RGB.sh script to run at boot by running the following code:
  * sudo cp ~/RGB.sh /etc/RGB.sh
  * sudo chmod 755 /etc/RGB.sh
  * sudo chown root:root /etc/RGB.sh
  * su - 
  * crontab -e
  * Add "@reboot	/etc/RGB.sh &"
8. To change the on off status go to http://raspberry-pi-ip/RGB.php

##References

* [PIGPIO Documentation](http://abyz.co.uk/rpi/pigpio/index.html)
* [DigitalOcean LAMP Guide](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-debian)
* [Original GPIO Project with Hardware](http://popoklopsi.github.io/RaspberryPi-LedStrip/#!/)
* [The source idea, GPIO control over the web](https://github.com/vlee489/Controlling-Raspberry-Pi-GPIO-on-off-over-the-internet)
* [HTML5 Canvas RGB picker](http://ravingroo.com/decoded/download-html5-canvas-hex-rgb-color-picker.php)
