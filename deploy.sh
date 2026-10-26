#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")"

if [ ! -f .env ]; then
    echo "Missing .env - run: cp .env.docker.example .env  and fill it in." >&2
    exit 1
fi

git pull --ff-only

# Build while the current container keeps serving traffic, then swap it.
docker compose build --pull
docker compose up -d --remove-orphans

docker image prune -f
docker compose ps
