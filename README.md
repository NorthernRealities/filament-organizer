# Filament Organizer
Have you ever found yourself disorganized, because you have different filament rolls and don't know every bit of information about them off the top of your head? Well, here's the solution!

Introducing: Filament Organizer

The program can automatically detect which filament are you using and displays some important details about it, either on the website or a display. You only need a webserver and ESP8266 with an NFC Reader.

## Some key features
* Automatic detection system
* Custom naming, images and other metadata (color, material, diameter, manufacturer, model, nozzle temperature, bed temperature, comment)
* Human readable identification number for physical roll recognition
* Display current temperature, weight and humidity
* And the best: It's all Open-Source.

## Requirements
Hardware:
* ESP8266
Software:
* PHP (was created on 7.4 but should work with newer versions aswell), with these extensions loaded: curl, mbstring, sqlite3, pdo_sqlite
* A webserver (e.g. NGINX, Apache)

## FAQ:
* The webpage only shows 'Filament Organizer', what do I do? Please make sure you've set up your client correctly and if nothing works, go to the GitHub Issues page, and create a new issue.
* X or Y is not working as intended, what do I do? Go to the GitHub Issues page, and create a new issue.
