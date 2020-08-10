services.httpd = {
    enable = true;
    adminAddr = "admin@localhost";
    enablePHP = true;


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
