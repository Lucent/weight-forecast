#!/bin/bash
set -e

source .env

echo "Dumping database..."
docker compose exec -T db mysqldump \
  --single-transaction \
  -u "root" \
  -p"$DB_ROOT_PASS" \
  "$DB_NAME" > db-dump/fitcast.sql

echo "Backup saved to db-dump/fitcast.sql"
