# projektwahl-php

https://dev.to/open-graphql/building-powerful-graphql-servers-with-rust-3gla
https://github.com/davidpdrsn/juniper-eager-loading
https://github.com/davidpdrsn/graphql-app-example
https://github.com/graphql-rust/juniper # warnings alpha and no subscriptions

https://relay.dev/ (but seems to be required to use with React?)
then maybe also use javascript for the server like sponsorenlauf???

## Installation

## Nginx config

https://nginx.org/en/docs/http/ngx_http_headers_module.html "There could be several add_header directives. These directives are inherited from the previous level if and only if there are no add_header directives defined on the current level."

put these in an include file, see notice above.

```bash
sudo nano /etc/nginx/security.conf
server_tokens off;
add_header X-Frame-Options deny always;
add_header X-Content-Type-Options nosniff always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Content-Security-Policy "default-src 'none'; script-src 'self'; style-src 'self' 'unsafe-inline' cdnjs.cloudflare.com; img-src 'self'; font-src cdnjs.cloudflare.com; upgrade-insecure-requests; block-all-mixed-content; disown-opener; sandbox allow-forms allow-same-origin allow-scripts allow-top-navigation allow-popups; reflected-xss block; referrer no-referrer" always;
# TODO FIXME inline styles
```

then do
```nginx
include /etc/nginx/security.conf;
```
FOR THE SERVER BLOCK AND INSIDE OF EVERY LOCATION BLOCK. I recommend checking it using ZAP proxy or so. It should show an alert if you failed.

## PHP config

```bash
sudo nano /etc/php/7.3/fpm/php.ini
session.cookie_httponly = 1
session.cookie_secure = 1 # if using ssl
session.cookie_samesite = Strict
session.use_only_cookies = 1
```

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
