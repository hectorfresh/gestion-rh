#!/usr/bin/env bash
# Lance le serveur de developpement CodeIgniter avec les extensions PHP
# chargees sans root (mysqli / pdo_mysql) -> voir ~/.config/php/conf.d/20-mysql.ini
set -e
export PHP_INI_SCAN_DIR="/etc/php/8.3/cli/conf.d:$HOME/.config/php/conf.d"
cd "$(dirname "$0")"
exec php spark serve "$@"
