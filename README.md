# Concertos

## Requirements

- A Web server (NGINX is recommended)
- PHP 7.1.3 + is required (7.2 for Argon2 Support)
- Dependencies for PHP,
  -   php-curl -> This is specifically needed for the various APIs we have running.
  -   php-zip -> This is required for the Backup Manager.
- Crontab access
- A Redis server
- MySQL 5.7

## Example Installation on Ubuntu Servers

1. Install the dependencies

```sh
sudo apt-get install mysql-server
mysql_secure_installation
sudo add-apt-repository -y ppa:nginx/development
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update
sudo apt-get install -y git tmux vim curl wget zip unzip htop nano redis-server\
    nginx php-pear php7.2-curl php7.2-dev php7.2-gd php7.2-mbstring php7.2-zip\
    php7.2-mysql php7.2-xml php7.2-fpm
```

2. Configure Nginx:

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

3. (Optional) Use an Let's Encrypt Certificate

https://www.digitalocean.com/community/tutorials/how-to-secure-nginx-with-let-s-encrypt-on-ubuntu-16-04

## Configuring the Software

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

## Packages

Here are some packages that are built for Concertos/UNIT3D.
- [An artisan package to import a XBTIT database into UNIT3D](https://github.com/pxgamer/xbtit-to-unit3d).
- [An artisan package to import a Gazelle database into UNIT3D](https://github.com/pxgamer/gazelle-to-unit3d).
- [An artisan package to import a U-232 database into UNIT3D](https://github.com/pxgamer/u232-to-unit3d).

## Security

If you discover any security related issues, please contact Ryuu directly (you
know where to find me :^)) instead of using the issue tracker. For an issue
that's also in UNIT3D please additionally contact HDVinnie.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

Concertos/UNIT3D is open-sourced software licensed under the
[GNU General Public License v3.0](https://github.com/HDVinnie/UNIT3D/blob/master/LICENSE).
*As per license do not remove the license from sourcecode or from footer in
/resources/views/partials/footer.blade.php*

## Homestead

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
192.168.10.10   unit3d.site
```

1. Run `cd ~/Homestead && vagrant up --provision`
2. Run `vagrant ssh`
3. Cd to the unit3d project root directory
4. Copy `.env.example` to `.env`
5. Run `php artisan key:generate`
6. Run `composer install`
7. Run `npm install`
8. Run `php artisan migrate:refresh --seed`
9. Visit <a href="http://unit3d.site">unit3d.site</a>
10. Login with username `UNIT3D` and password `UNIT3D`
