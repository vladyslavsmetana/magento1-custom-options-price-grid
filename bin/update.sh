rm -rf var/cache/*
mysqldump -u pdmngr_xps --password='daTee6ja' customoptions_xps > customoptions_xps-`date +'%Y%m'`.sql
du -s *.sql
