# projektwahl-php


## Logging sql queries
sudo nano /etc/mysql/mariadb.conf.d/50-server.cnf
uncomment general_log_file and general_log
sudo systemctl restart mariadb
tail -f /var/log/mysql/mysql.log

sudo nano /etc/postgresql/11/main/postgresql.conf
logging_collector = on
log_directory = 'log'
log_filename = 'postgresql-%Y-%m-%d_%H%M%S.log'
log_statement = 'all'
sudo systemctl restart postgresql
sudo tail -f /var/lib/postgresql/11/main/log/postgresql-2019-06-12_185549.log
