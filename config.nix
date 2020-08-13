/*
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
  
  security.pki.certificates = [ ''
    mkcert
    =========
    -----BEGIN CERTIFICATE-----
    MIIEejCCAuKgAwIBAgIRAL843FlmOTQHaJrnpDkuUa8wDQYJKoZIhvcNAQELBQAw
    VTEeMBwGA1UEChMVbWtjZXJ0IGRldmVsb3BtZW50IENBMRUwEwYDVQQLDAxtb3Jp
    dHpAbml4b3MxHDAaBgNVBAMME21rY2VydCBtb3JpdHpAbml4b3MwHhcNMjAwODEz
    MTUyODEyWhcNMzAwODEzMTUyODEyWjBVMR4wHAYDVQQKExVta2NlcnQgZGV2ZWxv
    cG1lbnQgQ0ExFTATBgNVBAsMDG1vcml0ekBuaXhvczEcMBoGA1UEAwwTbWtjZXJ0
    IG1vcml0ekBuaXhvczCCAaIwDQYJKoZIhvcNAQEBBQADggGPADCCAYoCggGBALKA
    nqIjmlrP2UV7wl1JAQu1ku6MeeB0e+ajpPduw9OBNXfr3BplFgb3vDAsLj0qa9LI
    F5pHkcF3zS28tmxNpXD+t/z0d8i3MhwgkWGribwroggaQcJFNgvQS4WT+YFo5e1v
    X6WSd/6IY6/i6wQfrq+Sg4x/8nWHh54pQfkGUGn8/h+t1EbXjMoGJGq3JrAXOdxn
    iRXSiQ9c+OylmInlJaXcvXIFMAe+hZKS5y2tNSfXb70sbA56UQ3+MD8OcCZij4ME
    sVHcZyhZXXgfe8FQCmB6EDhtdwKxCCrcC9rxUDem7VgK6yqs6DhwGYENXPCEMGfS
    fVppw9+wIAVSC18gjW1jOXyBU1zbs7B+rkE8mGMledLjP/l3mjC0T6RvsT6rSow/
    TeDT3ge4AqIo2CVJegDU5D7/m5TjpXBynmmwr0LW6s9LZ3fSstc9QP14K34gQU30
    aY5MRDy/NTykz6QOWpGdJvti4YAV4LLwUDArwTQ/gbtGwhEe9ifuQGqXnXz27QID
    AQABo0UwQzAOBgNVHQ8BAf8EBAMCAgQwEgYDVR0TAQH/BAgwBgEB/wIBADAdBgNV
    HQ4EFgQUfT3F/uW3cIR5f28cFxGIQwKOQKMwDQYJKoZIhvcNAQELBQADggGBAKGg
    XTlPJ6AtyYk6GUzE9DZCp5NWv062SghYKsQMgTRGOebZnIfNJKsXrXsVPNo+UIJi
    2NH6S33dQDBVMIgIuOAzg9zUUo23ZColbEB4SXz/Xpc3XXlDpAQzqVzUhtEf/OZT
    bkh4nHYhc8swcagSFpqgERSsURcR0VSsoNEKV3PoGx4RsgMeiez/1cHPmpQrgL6I
    XmvcikL2ZgnIETKV8pyEj7pTbNYEestuzFUNvnVd80z++GfFqgC/ZLlIpJC9iV1H
    hn9sMevClHSDJ2v+cxtLQK25pxCVxDOucIE95oPEuSo1bLSSq8U84UgoauZVLXH/
    NBlsQdm2dR1GtnP6XhFkOwkMNRLdB2KCMDSboOjJpszUyNE75VrmrPNOFBJ3aTGe
    AiEWNbAF1IYUtmeyM9fM+LMw8uwhsN5/URWmm/bPLgd0WB24mVEeTfToqjlETLdS
    WVUNihDUWr1Ddb1/Gi7W8h2P6PDMAHSCAmTQjFmUm7LOO0ZFlc3kp45Ia7L/6w==
    -----END CERTIFICATE-----
    ''
  ];
  
  services.httpd = {
    enable = true;
    adminAddr = "admin@localhost";
    extraModules = [ "proxy_fcgi" ];
    virtualHosts.projektwahl = {
      http2 = true;
      forceSSL = true;
      sslServerCert = "/etc/nixos/localhost.pem";
      sslServerKey = "/etc/nixos/localhost-key.pem";
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
