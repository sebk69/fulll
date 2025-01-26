-- Create replication user
CREATE USER 'replicator'@'%' IDENTIFIED WITH mysql_native_password BY '6e1c0182a9f224141f2db6b2ae3b8837';
GRANT REPLICATION SLAVE ON *.* TO 'replicator'@'%';
FLUSH PRIVILEGES;
