# API Documentation
If you want to write your own client, you'll need to know how the server API works. This documentation will help you.

**DISCLAIMER:** The current state of the API is not ready for public use due to the lack of an authentication system, please only use it in closed and regulated environment.

Some actions are disabled in rolling versions either because they aren't used, is insecure or it was there just for testing purposes.

## General communication
#### Sensor handling
When the microcontroller detects a filament on the holder, it sends a simple HTTP request to an URL. The service differentiates the filament rolls using an NFC tag on it which has a unique identification number. You can of course use a different method if you would like to.
```
http://<server-address>/action.php?action=6&nfc=<uuid>&temp=<temperature>&humidity=<humidity>&weight=<weight>
```
Other tasks will be handled by the API.
##### If everything's good, you'll get this response:
`OK.`
#### Reading information from database
##### For the frontend
There are two ways the service can return information.

* For listing all profiles:
	* ```
http://<server-address>/action.php?action=2&id=<human-readable-id>
```
	* Example response:
	```json
[
  {
    "name": "Ultramarine01",
    "image": "https://cdn.shop.prusa3d.com/1454-large_default/prusament-petg-ultramarine-blue-transparent-1kg.jpg",
    "color": "Ultramarine Blue",
    "material": "PETG",
    "diameter": 1.75,
    "manufacturer": "Prusament",
    "model": "PRM-PETG-UMB-1000",
    "nozzleTemp": 250,
    "bedTemp": 75,
    "comment": "Purchased on 2021-02-03"
	}
]
```
* For currently active profile (the only difference is it doesn't return some useless information for the active profile section on the frontend):
	* ```
http://<server-address>/action.php?action=21&id=<human-readable-id>
```
	* Example response:
	```json
[
{
    "color": "Ultramarine Blue",
    "material": "PETG",
    "diameter": 1.75,
    "manufacturer": "Prusament",
    "model": "PRM-PETG-UMB-1000",
    "nozzleTemp": 250,
    "bedTemp": 75,
    "comment": "Purchased on 2021-02-03"
  }
]
```

##### For a display
The API can also return information of the currently active profile if you would like to show it on a display.
```
http://<server-address>/action.php?action=7
```
##### Example response:
```
Manufacturer
Weight
Human_readable_ID
Profile_name
```
#### Reset weight to the current read by sensor
If you recycled an NFC tag from an empty filament roll onto a new one, you would want to reset the original weight to the current weight.
```
http://<server-address>/action.php?action=5&id=<human-readable-id>
```
##### If everything's good, you'll get this response:
`OK.`
#### Delete profile
Delete a profile from the database.
```
http://<server-address>/action.php?action=4&id=<human-readable-id>
```
##### If everything's good, you'll get this response:
`OK.`

## Disabled in rolling
#### Return a language variable
Variables located in the `settings.php` file.
```
http://<server-address>/action.php?action=3&var=<language-variable>
```
##### Example response:
`http://<server-address>/action.php?action=3&var=profileKnown`
```
Profiles
```
#### Return human readable information about a profile (Disabled when using SQLite)
```
http://<server-address>/action.php?action=3&id=<human-readable-id>
```
##### Example response:
```yaml
id: 20
nfcid: 01:01:00:00
name: New Ultramarine
image:
color: Ultramarine Blue
weight: 69
currentWeight: 69
material: PETH
diameter: 1.75
manufacturer: Prusament
model: PRM-PETG-UMB-1000
nozzleTemp: 250
bedTemp: 75
addDate: 1613932770
comment: Purchased on 2021-02-03
setup: 1
```
#### Benchmark database speed (Disabled when using SQLite)
```
http://<server-address>/action.php?action=-2
```
##### Example response:
```
0.29
```