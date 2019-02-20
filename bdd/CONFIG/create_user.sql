use mysql;
create user 'replicator'@'%' identified by 'repl1234or';
grant replication slave on *.* to 'replicator'@'%';
FLUSH PRIVILEGES;
CREATE USER 'bx'@'%' IDENTIFIED BY 'toto';
GRANT ALL PRIVILEGES ON * . * TO 'bx'@'%';
FLUSH PRIVILEGES;
