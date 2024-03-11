#!/bin/bash

set -ex

export PROJECT_DIR="Docker/local"

function build {
  compose-up
  compose-ps
  deploy-backend
}

function down {
  docker compose --project-directory ${PROJECT_DIR}  stop
  compose-ps
}

function compose-up {
  docker compose --project-directory ${PROJECT_DIR} up --build -d
}

function deploy-backend {
  docker-compose-exec-backend 'composer install --no-cache'
}

function docker-compose-exec-backend {
  docker compose --project-directory ${PROJECT_DIR} exec \
    -u "$(id -u):$(id -g)" \
    -w /app \
     backend \
    /bin/bash -c "${1}"
}


function compose-ps {
  docker compose --project-directory ${PROJECT_DIR}  ps
}

function compose-logs {
  docker compose --project-directory ${PROJECT_DIR} logs -f
}


if [[ $(type -t "${1}") == function ]];
then
   ${1}
else
   file=$(basename "$0")
   echo -e "\e[31mYou need run ./${file} build\e[0m"
fi
