#!/bin/bash
set -e

# 🔧 Ustawienia
LOCAL_URL="odt.local"
REMOTE_URL="odt.ohsofresh.top"
DB_FILE="local-db.sql"
REMOTE_USER="u679500043"
REMOTE_HOST="147.93.60.77"
REMOTE_PORT="65002"
REMOTE_PATH="/home/u679500043/domains/odt.ohsofresh.top/public_html"
WP_PATH="/Volumes/iDisc/Sites/odt/app/public"
LOCAL_DB_PATH="$WP_PATH/wp-content/themes/odt/deploy/$DB_FILE"

DB_NAME="local"
DB_USER="root"
DB_PASS="root"
SOCKET_PATH="/Users/arek/Library/Application Support/Local/run/ETA4LNPht/mysql/mysqld.sock"
MYSQLDUMP=$(find ~/Library/Application\ Support/Local/lightning-services/mysql-*/bin/*/bin/mysqldump -type f | head -n 1)


# 1. Eksport bazy danych
echo "🛠 Eksport bazy danych lokalnie..."
"$MYSQLDUMP" \
  --user=$DB_USER \
  --password=$DB_PASS \
  --socket="$SOCKET_PATH" \
  "$DB_NAME" > "$LOCAL_DB_PATH"

# 2. Zamiana adresów
echo "🔁 Podmiana adresów domeny..."
sed -i '' "s|http://$LOCAL_URL|https://$REMOTE_URL|g" "$LOCAL_DB_PATH"
sed -i '' "s|https://$LOCAL_URL|https://$REMOTE_URL|g" "$LOCAL_DB_PATH"

# 3. Wysyłka pliku
echo "🚀 Wysyłanie bazy danych na serwer..."
scp -P $REMOTE_PORT "$LOCAL_DB_PATH" $REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/$DB_FILE

# 4. Import na serwerze — działa zawsze
echo "📦 Importowanie bazy danych na serwerze..."
ssh -p $REMOTE_PORT $REMOTE_USER@$REMOTE_HOST <<'EOF'
cd /home/u679500043/domains/odt.ohsofresh.top/public_html
export PATH=$PATH:/usr/local/bin
wp db export backup-before-import-$(date +%Y%m%d-%H%M%S).sql
wp db import local-db.sql
wp search-replace 'http://odt.local' 'https://odt.ohsofresh.top' --skip-themes --skip-plugins
wp search-replace 'http://odt.local' 'https://odt.ohsofresh.top' --skip-themes --skip-plugins
rm local-db.sql
EOF

# 5. Czyszczenie lokalne
rm "$LOCAL_DB_PATH"
echo "✅ Gotowe!"
