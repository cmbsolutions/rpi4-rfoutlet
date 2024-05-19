# rpi4-rfoutlet
A modified/upgraded version of Tim Lelands "Wireless Power Outlets"

This version will run on a 64bit system like the Raspberry Pi 4. The files in the src/ folder are altered so they can be compiled on the rpi4.

## Requirements
- Raspberry Pi 4
- webserver (apache/lighttpd/etc...)
- php8
- wiringPi library*

#### Note on wiringPi

If you have the newer Raspbian installation on the pi you already have wiringPi installed, but if u have some other linux distro installed you need to get wiringPi first.
On ubuntu you can just do `sudo apt install -y wiringPi`


### Steps to make it work
1. Install a webserver and make it run
2. Install php8* and make it work with the webserver you installed
3. copy all files to a folder in your webroot folder, for example /var/www/html/rfoutlet
4. set permission on the app/codesend file so it can be called from the php files
   1. `sudo chown root:root /var/www/html/rfoutlet/app/codesend`
   2. `sudo chmod 4755 /var/www/html/rfoutlet/app/codesend` use the 4755 here and not just 755. it wont work otherwise.
5. Put your found codes in the cfg/config.php file
6. open the browser and navigate to the webaddress you setup in the webserver and click away.
