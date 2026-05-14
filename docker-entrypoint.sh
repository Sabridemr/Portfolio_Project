#!/bin/bash
set -e

# Railway assigns a dynamic PORT — configure Apache to listen on it
PORT="${PORT:-80}"

# Update Apache ports config
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-enabled/000-default.conf

# Disable conflicting MPM modules
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

exec apache2-foreground
