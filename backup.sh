#!/usr/bin/env bash
# Consistent SQLite backup (safe while the site is running, also in WAL mode).
# Restore:  gunzip -c backups/db-XXXX.sql.gz | sqlite3 new.sqlite
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")"

KEEP="${KEEP:-14}"
mkdir -p backups
out="backups/db-$(date +%Y%m%d-%H%M%S).sql.gz"

if ! docker compose exec -T app sh -c 'sqlite3 "$DB_DATABASE" .dump' | gzip > "$out"; then
    rm -f "$out"
    echo "Backup failed." >&2
    exit 1
fi
gzip -t "$out"
echo "Backup written: $out"

# Keep only the newest $KEEP backups
ls -1t backups/db-*.sql.gz | tail -n +"$((KEEP + 1))" | xargs -r rm --
