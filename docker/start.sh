#!/bin/sh
set -e

echo "═══════════════════════════════════════════════════"
echo "  🚀 Starting Laravel Application on Render"
echo "═══════════════════════════════════════════════════"

export PORT=${PORT:-8080}

echo "🌐 Port: $PORT"
echo "🌍 Env: $APP_ENV"
echo ""

# 1. Wait for DB
echo "⏳ [1/6] Waiting for database..."
for i in $(seq 1 30); do
    if php -r "
        try {
            new PDO(
                'pgsql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD')
            );
            echo 'OK';
        } catch (Exception \$e) { exit(1); }
    " 2>/dev/null | grep -q OK; then
        echo "✅ Database ready!"
        break
    fi
    echo "   Attempt $i/30"
    sleep 2
done

# 2. Clear cache (قوي - يحذف كل شيء قديم)
echo ""
echo "🧹 [2/6] Clearing cache..."
php artisan optimize:clear || true
php artisan view:clear || true
rm -rf storage/framework/views/* || true
rm -rf storage/framework/cache/data/* || true
rm -f bootstrap/cache/*.php || true

# 3. Migrations
echo ""
echo "🗄️  [3/6] Migrations..."
php artisan migrate --force || echo "⚠️ Migration failed"

# 4. Seed if empty
echo ""
echo "🌱 [4/6] Checking seeders..."
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1 | tr -d '[:space:]')
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "📦 Empty DB. Seeding..."
    php artisan db:seed --force || echo "⚠️ Seeding failed"
else
    echo "✅ DB has $USER_COUNT users. Skipping."
fi

# 5. Storage link
echo ""
echo "🔗 [5/6] Storage link..."
php artisan storage:link || true

# 6. Cache
echo ""
echo "⚡ [6/6] Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "═══════════════════════════════════════════════════"
echo "  ✅ Ready! Starting services..."
echo "═══════════════════════════════════════════════════"

# Update Nginx port
sed -i "s/listen 8080;/listen $PORT;/g" /etc/nginx/nginx.conf

exec /usr/bin/supervisord -c /etc/supervisord.conf