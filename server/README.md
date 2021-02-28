# Database Server
If you would like to make your own frontend, navigate to the ./docs folder for documentation.

## REQUIREMENTS
- PHP (was created on 7.4 but should work with newer versions aswell), with these extensions loaded: curl, mbstring, sqlite3, pdo_sqlite, (Optional) mysqli
- Webserver (e.g. NGINX, Apache)
- (Optional) MySQL Server (with full rights to a specified database)

## USAGE
1) Copy the files to your server.
2) You'll need to provide your own webserver pointed to the /public folder.
3) (Optional) A MySQL server is required, with a database and a user with full access to said database.
4) (Optional) Configure MySQL and other settings in public/settings.php
