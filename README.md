<!--
SPDX-FileCopyrightText: 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>
SPDX-License-Identifier: AGPL-3.0-or-later

Diese Software kann eine Projektwahl verwalten, wie sie beispielsweise für eine Projektwoche benötigt wird. 
Copyright (C) 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published
by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
-->

[![REUSE status](https://api.reuse.software/badge/github.com/mohe2015/projektwahl-php)](https://api.reuse.software/info/github.com/mohe2015/projektwahl-php)

sudo tail -f /var/log/httpd/error-projektwahl.log

nix-env -iA nixos.reuse

reuse-lint

https://api.reuse.software/register

https://reuse.software

# WebAuthn

https://github.com/herrjemand/awesome-webauthn

https://github.com/MasterKale/SimpleWebAuthn/tree/master/example
https://github.com/speakeasyjs/speakeasy

https://github.com/duo-labs/webauthn
https://github.com/pquerna/otp # recovery codes


https://webauthn.guide/

libraries according to https://marmelab.com/blog/2019/06/24/web-authn-fido2-open-source-package.html pretty bad ones:
https://webauthn.io/

https://marmelab.com/blog/2019/06/24/web-authn-fido2-open-source-package.html
npm i @webauthn/server

TODO
https://github.com/herrjemand/awesome-webauthn

go
https://www.herbie.dev/blog/webauthn-basic-web-client-server/

php https://thephp.cc/presentations/2019-international-php-conference-spring-edition-the-future-of-authentication-webauthn-with-php.pdf

https://github.com/web-auth/webauthn-framework 
omes with a ready to use Symfony Bundles

https://github.com/asbiin/laravel-webauthn
LaravelWebauthn is an adapter to use Webauthn on Laravel
Uses the above library

https://github.com/Firehed/u2f-php
An implementation of the FIDO U2F server protocol in PHP

https://github.com/lbuchs/WebAuthn
A simple PHP WebAuthn (FIDO2) server library

https://github.com/madwizard-thomas/webauthn-server
Early stage of development


https://github.com/solokeys/solo


https://medium.com/@herrjemand/introduction-to-webauthn-api-5fd1fb46c285

I need a key with some kind of authentication

# Type Checking JavaScript Files

https://www.typescriptlang.org/docs/handbook/type-checking-javascript-files.html

https://www.typescriptlang.org/docs/handbook/jsdoc-supported-types.html

https://www.typescriptlang.org/docs/handbook/declaration-files/dts-from-js.html

#   projektwahl-php

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
