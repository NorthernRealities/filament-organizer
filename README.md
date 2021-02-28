# Automatic Filament Organizer System
Have you ever found yourself disorganized, because you have a bunch of filament rolls and don't know every bit of information about them off the top of your head? Well, here's the solution!

Introducing: Filament Organizer

The spool holder can automatically detect which filament are you using and displays some important details about it (e.g. current weight, material, brand, age), either on the website or the display. You only need a webserver and an ESP8266 with some extra modules. Currently there are .STLs for the Ender 3S (the current version contains the original Ender 3 spool holder), but in the future a spool holder will be designed that is universally compatible with other printers.

![header2](https://github.com/szalovszky/filament-organizer/blob/main/.images/header2.png?raw=true)

## Some key features
* Automatic detection system
* Custom naming, images and other metadata (color, material, diameter, manufacturer, model, nozzle temperature, bed temperature, comment)
* Human readable identification number for physical roll recognition
* Display current temperature, weight and humidity
* And the best: It's all Open-Source.

## How does it work?
When you first want to setup a new filament you need to place an NFC Tag on the roll.
You place your filament roll onto the holder, the NFC Reader detects which roll you're using, the ESP8266 sends information about it to the server. You can save new filaments to the database using the wonderful web interface (under development).

## Requirements
Hardware:
* Microcontroller
* NFC reader module
* (Optional) Display
* NFC Tag stickers

Software:
* PHP (was created on 7.4 but should work with newer versions aswell), with these extensions loaded: curl, mbstring, sqlite3, pdo_sqlite
* A webserver (e.g. NGINX, Apache)

## Pictures
![webpage](https://github.com/szalovszky/filament-organizer/blob/main/.images/webapp.png?raw=true)

More pictures in the ./images directory.

## FAQ:
* The webpage only shows 'Filament Organizer', what do I do? Please make sure you've set up your client correctly and if nothing works, go to the GitHub Issues page, and create a new issue.
* X or Y is not working as intended, what do I do? Go to the GitHub Issues page, and create a new issue.
