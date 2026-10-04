#!/usr/bin/env bash

set -euo pipefail

git pull

docker compose down #due to ram limitation
docker compose up -d --build
docker image prune -f
