#!/bin/bash
$(docker-compose down)
$(rm -rf ../bdd/MASTER1/LOG/* ../bdd/MASTER2/LOG/*)
