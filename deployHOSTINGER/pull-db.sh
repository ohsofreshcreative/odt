#!/bin/bash
set -e

# 🔧 Ustawienia serwera
REMOTE_URL="odt.ohsofresh.top"
LOCAL_URL="odt.local"
DB_FILE="remote-db.sql"
REMOTE_USER="u679500043"
REMOTE_HOST="147.93.60.77"
REMOTE_PORT="65002"
REMOTE_PATH="/home/u679500043/domains/odt.ohsofresh.top/public_html"

# 🔧 Dane bazy z produkcji
REMOTE_DB_NAME="u679500043_LOcJ5"
REMOTE_DB_USER="u679500043_VrUiA"
REMOTE_DB_PASS="GmWXh1uqyd"

# 🔧 Ścieżki lokalne
LOCAL_PROJECT_PATH="/Volumes/iDisc/Sites/odt/app/public"
LOCAL_DB_PATH="$LOCAL_PROJECT_PATH/wp-content/themes/odt/deploy/$DB_FILE"

# 🔧 LocalWP DB
LOCAL_DB_NAME="local"
LOCAL_DB_USER="root"
LOCAL_DB_PASS="root"
SOCKET_PATH="/Users/arek/Library/Application Support/Local/run/ETA4LNPht/mysql/mysqld.sock"
MYSQL="mysql"

# 1. Zdalny eksport przez mysqldump
echo "📤 Eksport bazy danych z serwera przez mysqldump..."
ssh -p $REMOTE_PORT $REMOTE_USER@$REMOTE_HOST "
  cd $REMOTE_PATH &&
  mysqldump -u $REMOTE_DB_USER -p$REMOTE_DB_PASS $REMOTE_DB_NAME > $DB_FILE
"

mkdir -p "$(dirname "$LOCAL_DB_PATH")"


# 2. Pobranie pliku na komputer lokalny
echo "📥 Pobieranie bazy na lokalny komputer..."
scp -P $REMOTE_PORT $REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/$DB_FILE "$LOCAL_DB_PATH"

# 3. Czyszczenie pliku z serwera
echo "🧹 Usuwanie dumpa z serwera..."
ssh -p $REMOTE_PORT $REMOTE_USER@$REMOTE_HOST "rm $REMOTE_PATH/$DB_FILE"

# 4. Podmiana adresów domeny
echo "🔁 Podmiana adresów domeny..."
sed -i '' "s|https://$REMOTE_URL|http://$LOCAL_URL|g" "$LOCAL_DB_PATH"
sed -i '' "s|http://$REMOTE_URL|http://$LOCAL_URL|g" "$LOCAL_DB_PATH"

# 4a. Poprawka kodowania dla starszego MySQL
echo "🔧 Poprawka kodowania dla lokalnej bazy danych..."
sed -i '' 's/utf8mb3_uca1400_ai_ci/utf8mb3_general_ci/g' "$LOCAL_DB_PATH"

# 5. Import do LocalWP
echo "📦 Importowanie bazy danych do LocalWP..."
"$MYSQL" \
  --user=$LOCAL_DB_USER \
  --password=$LOCAL_DB_PASS \
  --socket="$SOCKET_PATH" \
  "$LOCAL_DB_NAME" < "$LOCAL_DB_PATH"

# 6. Czyszczenie lokalnego dumpa
rm "$LOCAL_DB_PATH"
echo "✅ Baza danych zaimportowana do LocalWP!"
