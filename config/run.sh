#!/bin/bash
#$(docker rm -f ARBD_MASTER1 ARBD_MASTER2 ARBD_PMA ARBD_GEN ARBD_HAPROXY ARBD_API1 ARBD_API2 ARBD_RABBITMQ)
docker-compose up -d;
docker exec -it ARBD_GEN rm -rf composer.lock;
docker exec -it ARBD_GEN php composer.phar install;
docker exec -it ARBD_API1 rm -rf composer.lock;
docker exec -it ARBD_API1 php composer.phar install;
docker exec -it ARBD_API2 rm -rf composer.lock;
docker exec -it ARBD_API2 php composer.phar install;
sleep 60
infos=$((docker exec -it ARBD_MASTER1 mysql -p2d74VSGwvMtop4f -e "use mysql; SHOW MASTER STATUS;") | grep bin)
names[1]="ARBD_MASTER2";
names[2]="ARBD_MASTER1";
bin[2]=$(echo $infos | cut -d "|" -f2 | sed 's/ //g')
position[2]=$(echo $infos | cut -d "|" -f3 | sed 's/ //g')
infos=$((docker exec -it ARBD_MASTER2 mysql -p2d74VSGwvMtop4f -e "use mysql; SHOW MASTER STATUS;") | grep bin)
bin[1]=$(echo $infos | cut -d "|" -f2 | sed 's/ //g')
position[1]=$(echo $infos | cut -d "|" -f3 | sed 's/ //g')
for i in {1..2}
do
    tmp[$i]="stop slave; CHANGE MASTER TO MASTER_HOST = '${names[$i]}', MASTER_USER = 'replicator', MASTER_PASSWORD = 'repl1234or', MASTER_LOG_FILE = '${bin[$i]}', MASTER_LOG_POS = ${position[$i]}; start slave;";
done
docker exec -it ARBD_MASTER1 mysql -p2d74VSGwvMtop4f -e "source /backup/create_user.sql";
docker exec -it ARBD_MASTER2 mysql -p2d74VSGwvMtop4f -e "source /backup/create_user.sql";
docker exec -it ARBD_MASTER1 mysql -p2d74VSGwvMtop4f -e "${tmp[1]}"
docker exec -it ARBD_MASTER2 mysql -p2d74VSGwvMtop4f -e "${tmp[2]}"
docker exec -it ARBD_MASTER1 mysql -p2d74VSGwvMtop4f -e "source /dump/dump_speedbouffe.sql";
sleep 65;
docker exec -dit ARBD_API1 php console/console.php consumecommande;
docker exec -dit ARBD_API2 php console/console.php consumecommande;
echo "#-----------------------------------------------------------------------------------------------------#";
echo "|                                             SPEEDBOUFFE                                             |";
echo "#-----------------------------------------------------------------------------------------------------#";
echo "#-----------------------------------------------------------------------------------------------------#";
echo "|       Route           |     Container    | Login |  MdP  | Description                              |";
echo "#-----------------------------------------------------------------------------------------------------#";
echo "| http://localhost:9501 |     ARBD_PMA     |   bx  | toto  | PhpMyAdmin                               |";
echo "| http://localhost:9500 |     ARBD_API     |-------|-------| API Backend Silex                        |";
echo "| http://localhost:8080 |   ARBD_HAPROXY   |   bx  | toto  | HAPROXY Stats                            |";
echo "|-----------------------| ARBD_MASTER[1-2] |   bx  | toto  | Base de données répliquées MASTER-MASTER |";
echo "|-----------------------|     ARBD_GEN     |-------|-------| Générateur SpeedBouffe => commande API   |";
echo "#-----------------------------------------------------------------------------------------------------#";
