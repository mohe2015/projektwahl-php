# projektwahl-php

## Installation

php composer.phar install
# set apc.enabled=false


## Nginx config

https://nginx.org/en/docs/http/ngx_http_headers_module.html "There could be several add_header directives. These directives are inherited from the previous level if and only if there are no add_header directives defined on the current level."

put these in an include file, see notice above.

sudo nano /etc/nginx/security.conf
server_tokens off;
add_header X-Frame-Options deny always;
add_header X-Content-Type-Options nosniff always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Content-Security-Policy "default-src 'none'; frame-ancestors 'none'; img-src 'self'; script-src 'self'; style-src 'self'" always;

then do
include /etc/nginx/security.conf;
FOR THE SERVER BLOCK AND INSIDE OF EVERY LOCATION BLOCK. I recommend checking it using ZAP proxy or so. It should show an alert if you failed.

// Content-Security-Policy: default-src 'none'; script-src 'self'; style-src 'self'; img-src 'self'; font-src 'self'; connect-src 'self'; form-action 'self'; upgrade-insecure-requests; block-all-mixed-content; disown-opener; require-sri-for script style; sandbox allow-forms allow-same-origin allow-scripts allow-popups; reflected-xss block; referrer no-referrer

## PHP config

sudo nano /etc/php/7.3/fpm/php.ini
session.cookie_httponly = 1
session.cookie_secure = 1 # if using ssl

## Logging sql queries
```bash
sudo nano /etc/mysql/mariadb.conf.d/50-server.cnf
# uncomment general_log_file and general_log
sudo systemctl restart mariadb
tail -f /var/log/mysql/mysql.log
```

```bash
sudo nano /etc/postgresql/11/main/postgresql.conf
logging_collector = on
log_directory = 'log'
log_filename = 'postgresql-%Y-%m-%d_%H%M%S.log'
log_statement = 'all'
sudo systemctl restart postgresql
sudo tail -f /var/lib/postgresql/11/main/log/postgresql-2019-06-12_185549.log
```

## Licenses

https://www.eff.org/files/2016/09/08/eff_short_wordlist_2_0.txt
