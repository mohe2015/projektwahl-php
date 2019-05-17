# projektwahl-php


## Logging sql queries
sudo nano /etc/mysql/mariadb.conf.d/50-server.cnf
uncomment general_log_file and general_log
sudo systemctl restart mariadb
tail -f /var/log/mysql/mysql.log
