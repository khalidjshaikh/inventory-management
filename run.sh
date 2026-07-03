#!/bin/bash
# Inventory Management System - Run Script
# Usage: ./run.sh [--port=PORT] [--host=HOST]

set -e

PHP_BIN="/opt/homebrew/opt/php@8.3/bin/php"
ARTISAN="$(dirname "$0")/artisan"

# Defaults
HOST="127.0.0.1"
PORT="8000"

# Parse arguments
for arg in "$@"; do
    case $arg in
        --port=*) PORT="${arg#*=}" ;;
        --host=*) HOST="${arg#*=}" ;;
        --help|-h)
            echo "Usage: $0 [--port=PORT] [--host=HOST]"
            echo ""
            echo "Starts the Inventory Management System Laravel dev server."
            echo ""
            echo "Options:"
            echo "  --port=PORT    Port to listen on (default: 8000)"
            echo "  --host=HOST    Host to bind to (default: 127.0.0.1)"
            echo "  --help, -h     Show this help message"
            exit 0
            ;;
    esac
done

echo "======================================"
echo " Inventory Management System"
echo "======================================"
echo ""

# Check if artisan exists
if [ ! -f "$ARTISAN" ]; then
    echo "ERROR: artisan not found. Are you in the project root?" >&2
    exit 1
fi

# Run migrations if not up-to-date
echo ">>> Running migrations..."
$PHP_BIN $ARTISAN migrate --force 2>&1 | tail -5

echo ""
echo ">>> Starting server at http://${HOST}:${PORT}"
echo ">>> Press Ctrl+C to stop."
echo ""

$PHP_BIN $ARTISAN serve --host="$HOST" --port="$PORT"
