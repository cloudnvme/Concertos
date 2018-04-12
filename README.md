# Concertos
## Table of Contents

1. [Some Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Packages](#packages)
5. [Security](#security)
6. [Contributing](#contributing)
7. [License](#license)
8. [Screenshots](#screenshots)
9. [Homestead](#homestead)
10. [Patreon](#patreon)

## <a name="features"></a> Some Features

UNIT3D currently offers the following features:
  - Internal Forums System
  - Staff Dashboard
  - Faceted Ajax Torrent Search System
  - BON Store
  - Torrent Request Section with BON Bounties
  - Freeleech System
  - Double Upload System
  - Featured Torrents System
  - Polls System
  - Extra-Stats
  - PM System
  - Multilingual Support
  - TwoStep Auth System
  - DB + Files Backup Manager
  - and MUCH MORE!

## <a name="requirements"></a> Requirements

- A Web server (NGINX is recommended)
- PHP 7.1.3 + is required (7.2 for Argon2 Support)
- Dependencies for PHP,
  -   php-curl -> This is specifically needed for the various APIs we have running.
  -   php-zip -> This is required for the Backup Manager.
- Crontab access
- A Redis server
- MySQL 5.7
- TheMovieDB API Key: https://www.themoviedb.org/documentation/api
- TheTVDB API Key: https://api.thetvdb.com/swagger
- OMDB API Key: http://www.omdbapi.com/
- A decent dedicated server. Dont try running this on some crappy server!
<pre>
Processor: Intel  Xeon E3-1245v2 -
Cores/Threads: 4c/8t
Frequency: 3.4GHz /3.8GHz
RAM: 32GB DDR3 1333 MHz
Disks: SoftRaid  2x240 GB   SSD
Bandwidth: 250 Mbps
Traffic: Unlimited
<b>Is Under $50 A Month</b>
</pre>

## <a name="installation"></a> Installation
Prerequisites Example:
1. Install OS

    `Ubuntu Server 17.10 "Artful Aardvark" (64bits)`

    or

    `Ubuntu Server 16.04.4 LTS "Xenial Xerus" (64bits)`
2. Install MySQL:

    `sudo apt-get install mysql-server`

    `mysql_secure_installation`
3. Get repositories for the latest software:

    `sudo add-apt-repository -y ppa:nginx/development`

    `sudo add-apt-repository -y ppa:ondrej/php`

    `sudo apt-get update`
4. Then we'll install the needed software (Basics/Redis/NGINX/PHP):

    #Basics `sudo apt-get install -y git tmux vim curl wget zip unzip htop nano`

    #Redis `sudo apt-get install redis-server`

    #Nginx `sudo apt-get install -y nginx`

    #PHP `sudo apt-get install php-pear php7.2-curl php7.2-dev php7.2-gd php7.2-mbstring php7.2-zip php7.2-mysql php7.2-xml php7.2-fpm`
5. Configure PHP:

    `sudo nano /etc/php/7.2/fpm/php.ini`

    FIND->`;cgi.fix_pathinfo=1` REPLACE WITH->`cgi.fix_pathinfo=0`

    Save and close

    `sudo systemctl restart php7.2-fpm`
6. Configure Nginx:

    `sudo nano /etc/nginx/sites-available/default`

    ```
    server {
    listen 80 default_server;

    root /var/www/html/public;

    index index.html index.htm index.php;

    server_name example.com;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
       include snippets/fastcgi-php.conf;
       fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
    }
    }
    ```
    Save and close.

    `sudo systemctl reload nginx`
7. Secure Nginx with Let's Encrypt

    https://www.digitalocean.com/community/tutorials/how-to-secure-nginx-with-let-s-encrypt-on-ubuntu-16-04


Main:
1. First grab the source-code and upload it to your web server. (If you have Git on your web server installed then clone it directly on your web server.)
2. Open a terminal and SSH into your server.
3. cd to the sites root directory
4. Run `sudo chown -R www-data: storage bootstrap public config` and `sudo find . -type d -exec chmod 0755 '{}' + -or -type f -exec chmod 0644 '{}' +`
5. Run `php -r "readfile('http://getcomposer.org/installer');" | sudo php -- --install-dir=/usr/bin/ --filename=composer`
6. Edit `.env.example` to `.env` and fill it with your APP, DB, REDIS and MAIL info.
7. Run `composer install` to install dependencies.
8. Edit `config/api-keys.php`, `config/app.php` and `config/other.php` (These house some basic settings. Be sure to visit the config manager from staff dashboard after up and running.)
9. Add   `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1` to crontab. `/path/to/artisan` becomes whatever directory you put the codebase on your server. Like `* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1` .
10. Run `php artisan key:generate` to generate your cipher key.
11. Run `php artisan migrate --seed` (Migrates All Tables And Foreign Keys)
12. Suggest that you run `php artisan route:cache`. (Keep in mind you will have to re-run it anytime changes are made to the `routes/web.php` but it is beneficial with page load times).
13. `sudo chown -R www-data: storage bootstrap public config`
14. Go to your sites URL.
15. Login with the username `UNIT3D` and the password `UNIT3D`. (Or whatever you set in the .env if changed from defaults.) (This is the default owner account.)
16. Enjoy using UNIT3D.

## <a name="packages"></a> Packages
Here are some packages that are built for UNIT3D.
- [An artisan package to import a XBTIT database into UNIT3D](https://github.com/pxgamer/xbtit-to-unit3d).
- [An artisan package to import a Gazelle database into UNIT3D](https://github.com/pxgamer/gazelle-to-unit3d).
- [An artisan package to import a U-232 database into UNIT3D](https://github.com/pxgamer/u232-to-unit3d).

## <a name="security"></a> Security

If you discover any security related issues, please email unit3d@protonmail.com instead of using the issue tracker.

## <a name="contributing"></a> Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## <a name="license"></a> License

UNIT3D is open-sourced software licensed under the [GNU General Public License v3.0](https://github.com/HDVinnie/UNIT3D/blob/master/LICENSE).

<b> As per license do not remove the license from sourcecode or from footer in /resources/views/partials/footer.blade.php</b>


<hr>

## <a name="screenshots"></a> Screenshots

<p align="center">
User Profile (Galactic Theme)
    <img src="https://i.imgur.com/NyLolmJ.gif" alt="User Profile Page">
User Profile (Light Theme)
    <img src="https://i.imgur.com/94XCo3Q.gif" alt="User Profile Page">
</p>

## <a name="homestead"></a> Homestead

<a href="https://laravel.com/docs/5.6/homestead#installation-and-setup">Install and Setup Homestead </a>
### Example `Homestead.yaml`
```yaml
folders:
    - map: ~/projects
      to: /home/vagrant/projects

sites:
    ...
    - map: unit3d.site
      to: /home/vagrant/projects/www/unit3d/public

databases:
    - homestead
    - unit3d
```

### Example `/etc/hosts`
```
127.0.0.1       localhost
127.0.1.1       3rdtech-gnome

...
192.168.10.10   unit3d.site

```

1. run `cd ~/Homestead && vagrant up --provision`
2. run `vagrant ssh`
3. cd to the unit3d project root directory
4. copy `.env.example` to `.env`
5. run `php artisan key:generate`
6. run `composer install`
7. run `npm install`
8. run `php artisan migrate:refresh --seed`
9. visit <a href="http://unit3d.site">unit3d.site</a>
10. Login u: `UNIT3D` p: `UNIT3D`