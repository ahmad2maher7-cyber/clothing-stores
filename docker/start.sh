#!/bin/sh
set -e

echo "═══════════════════════════════════════════════════"
echo "  🚀 Starting Laravel Application on Render"
echo "═══════════════════════════════════════════════════"

# Set default port if not provided
export PORT=${PORT:-8080}

echo ""
echo "🌐 Port: $PORT"
echo "🌍 Environment: $APP_ENV"
echo ""

# ─────────────────────────────────────────────
# 1. Wait for Database to be Ready
# ─────────────────────────────────────────────
echo "⏳ [1/6] Waiting for database connection..."

for i in $(seq 1 30); do
    if php -r "try { new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); echo 'OK'; } catch (Exception \$e) { exit(1); }" 2>/dev/null; then
        echo "✅ Database is ready!"
        break
    fi
    echo "   Attempt $i/30... waiting 2s"
    sleep 2
done

# ─────────────────────────────────────────────
# 2. Clear Old Cache
# ─────────────────────────────────────────────
echo ""
echo "🧹 [2/6] Clearing old cache..."
php artisan optimize:clear || true

# ─────────────────────────────────────────────
# 3. Run Migrations
# ─────────────────────────────────────────────
echo ""
echo "🗄️  [3/6] Running migrations..."
php artisan migrate --force || echo "⚠️ Migration failed (continuing...)"

# ─────────────────────────────────────────────
# 4. Seed Database (only if empty)
# ─────────────────────────────────────────────
echo ""
echo "🌱 [4/6] Checking if seeding is needed..."

USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1 | tr -d '[:space:]')

if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "📦 Database is empty. Seeding..."
    php artisan db:seed --force || echo "⚠️ Seeding failed (continuing...)"
else
    echo "✅ Database already has $USER_COUNT users. Skipping seed."
fi

# ─────────────────────────────────────────────
# 5. Create Storage Link
# ─────────────────────────────────────────────
echo ""
echo "🔗 [5/6] Creating storage symlink..."
php artisan storage:link || true

# ─────────────────────────────────────────────
# 6. Cache for Production
# ─────────────────────────────────────────────
echo ""
echo "⚡ [6/6] Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "═══════════════════════════════════════════════════"
echo "  ✅ Setup Complete! Starting services..."
echo "═══════════════════════════════════════════════════"
echo ""

# Update Nginx to use the correct port
sed -i "s/listen 8080;/listen $PORT;/g" /etc/nginx/nginx.conf

# Start supervisord (runs PHP-FPM + Nginx)
exec /usr/bin/supervisord -c /etc/supervisord.conf