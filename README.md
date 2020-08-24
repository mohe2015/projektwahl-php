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

# Type Checking JavaScript Files

https://www.typescriptlang.org/docs/handbook/type-checking-javascript-files.html

https://www.typescriptlang.org/docs/handbook/jsdoc-supported-types.html

https://www.typescriptlang.org/docs/handbook/declaration-files/dts-from-js.html

# projektwahl-php

## Installation

Edit copy config.sample.php to config.sample and edit settings. Then open /install.php

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
