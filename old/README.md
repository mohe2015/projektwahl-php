# projektwahl-php

TODO ADD types to function and classes

## Model classes

have list of fields with types (for generating forms)

Edit, DELETE, FORM, ... get generated from that list

Validation also adds errors to that list so they can be shown inline

Hopefully this will clean up the code






## Installation

Edit copy config.sample.php to config.sample and edit settings. Then open /install.php

# TODO convert to Apache

server_tokens off;
add_header X-Frame-Options deny always;
add_header X-Content-Type-Options nosniff always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Content-Security-Policy "default-src 'none'; script-src 'self'; style-src 'self' 'unsafe-inline' cdnjs.cloudflare.com; img-src 'self'; font-src cdnjs.cloudflare.com; upgrade-insecure-requests; block-all-mixed-content; disown-opener; sandbox allow-forms allow-same-origin allow-scripts allow-top-navigation allow-popups; reflected-xss block; referrer no-referrer" always;
# TODO FIXME inline styles
```

## PHP config

TODO FIXME this options can now be passed in session_start (probably?)
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
