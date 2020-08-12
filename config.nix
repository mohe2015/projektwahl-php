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
services.httpd = {
    enable = true;
    adminAddr = "admin@localhost";
    enablePHP = true;
    phpOptions = ''
      display_errors = Off;
      
      '';

    virtualHosts = {
        "localhost" = {
            documentRoot = "/home/moritz/Documents/projektwahl-php";

            extraConfig = ''
                DirectoryIndex index.html index.php

                RewriteEngine On
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteCond %{REQUEST_FILENAME} !-d
                RewriteCond %{REQUEST_URI} !/css
                RewriteCond %{REQUEST_URI} !/js
                RewriteCond %{REQUEST_URI} !/fontawesome
                RewriteCond %{REQUEST_URI} !/api
                RewriteRule ^(.*)$ /index.html [L,QSA]
            '';
        };
    };
};
