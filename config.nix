/* 
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
*/
  users.users.projektwahl = {
    group = config.services.httpd.group;
    isSystemUser = true;
  };

  services.phpfpm.pools.projektwahl = {
    user = "projektwahl";
    group = config.services.httpd.group;
    settings = {
      "listen.owner" = config.services.httpd.user;
      "listen.group" = config.services.httpd.group;
      "pm" = "dynamic";
      "pm.max_children" = 32;
      "pm.start_servers" = 2;
      "pm.min_spare_servers" = 2;
      "pm.max_spare_servers" = 4;
      "pm.max_requests" = 500;
      "php_admin_value[error_log]" = "stderr";
      "php_admin_flag[log_errors]" = true;
      "catch_workers_output" = true;
      #"log_level" = "notice";
      "access.log" = "/var/log/$pool.access.log";
    };
  };

  services.httpd = {
    enable = true;
    adminAddr = "admin@localhost";
    extraModules = [ "proxy_fcgi" ];
    virtualHosts.projektwahl = {
      documentRoot = lib.mkForce "/var/www/projektwahl-php";
      extraConfig = ''
        <Directory "/var/www/projektwahl-php">
          <FilesMatch "\.php$">
            <If "-f %{REQUEST_FILENAME}">
              SetHandler "proxy:unix:${config.services.phpfpm.pools.projektwahl.socket}|fcgi://localhost/"
            </If>
          </FilesMatch>

          LogLevel notice

          RewriteEngine On
          RewriteCond %{REQUEST_FILENAME} !-f
          RewriteCond %{REQUEST_FILENAME} !-d
          RewriteCond %{REQUEST_URI} !/css
          RewriteCond %{REQUEST_URI} !/js
          RewriteCond %{REQUEST_URI} !/fontawesome
          RewriteCond %{REQUEST_URI} !/api
          RewriteRule ^(.*)$ /index.html [L,QSA]

          DirectoryIndex index.html index.php
          Require all granted
          Options +FollowSymLinks
        </Directory>
      '';
    };
  };
