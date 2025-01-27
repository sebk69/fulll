CHANGE MASTER TO
    MASTER_HOST='mysql-writer',
    MASTER_USER='replicator',
    MASTER_PASSWORD='6e1c0182a9f224141f2db6b2ae3b8837',
    MASTER_LOG_FILE='mysql-bin.000001',
    MASTER_LOG_POS=157;
START SLAVE;
GRANT SELECT ON `fleet-manager`.* TO 'backoffice'@'%';