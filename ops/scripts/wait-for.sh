#!/usr/bin/env sh
set -e
host="$1"; port="$2"
shift 2
until nc -z "$host" "$port"; do
  echo "waiting for $host:$port..."
  sleep 1
done
exec "$@"
