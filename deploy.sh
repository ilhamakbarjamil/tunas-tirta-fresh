#!/bin/bash
# =============================================================================
# TUNAS TIRTA FRESH - PRODUCTION DEPLOYMENT SCRIPT
# =============================================================================
# Jalankan script ini untuk setup production environment
# Usage: bash deploy.sh
# =============================================================================

set -e  # Exit jika ada error

echo "🚀 Starting Tunas Tirta Fresh Deployment..."
echo ""

# ============= COLOR OUTPUT =============
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# ============= STEP 1: Check Prerequisites =============
echo -e "${YELLOW}Step 1: Checking prerequisites...${NC}"

if ! command -v php &> /dev/null; then
    echo -e "${RED}ERROR: PHP not found!${NC}"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    echo -e "${RED}ERROR: Composer not found!${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Prerequisites OK${NC}"
echo ""

# ============= STEP 2: Check .env =============
echo -e "${YELLOW}Step 2: Checking .env file...${NC}"

if [ ! -f ".env" ]; then
    echo -e "${RED}ERROR: .env file not found!${NC}"
    echo "Please copy .env.production to .env and update credentials"
    exit 1
fi

# Check if APP_ENV is set to production
if ! grep -q "APP_ENV=production" .env; then
    echo -e "${YELLOW}⚠️  WARNING: APP_ENV is not set to production${NC}"
    read -p "Continue anyway? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

echo -e "${GREEN}✅ .env file exists${NC}"
echo ""

# ============= STEP 3: Generate APP_KEY =============
echo -e "${YELLOW}Step 3: Generating APP_KEY...${NC}"

if grep -q "^APP_KEY=$" .env; then
    echo "Generating new APP_KEY..."
    php artisan key:generate
    echo -e "${GREEN}✅ APP_KEY generated${NC}"
else
    echo -e "${GREEN}✅ APP_KEY already set${NC}"
fi
echo ""

# ============= STEP 4: Install Composer Dependencies =============
echo -e "${YELLOW}Step 4: Installing PHP dependencies...${NC}"

composer install --optimize-autoloader --no-dev
echo -e "${GREEN}✅ Composer dependencies installed${NC}"
echo ""

# ============= STEP 5: Install NPM Dependencies =============
echo -e "${YELLOW}Step 5: Installing Node dependencies...${NC}"

if command -v npm &> /dev/null; then
    npm install
    npm run build
    echo -e "${GREEN}✅ NPM dependencies installed and built${NC}"
else
    echo -e "${YELLOW}⚠️  NPM not found, skipping...${NC}"
fi
echo ""

# ============= STEP 6: Database Migrations =============
echo -e "${YELLOW}Step 6: Running database migrations...${NC}"

php artisan migrate --force
echo -e "${GREEN}✅ Database migrations completed${NC}"
echo ""

# ============= STEP 7: Clear Cache =============
echo -e "${YELLOW}Step 7: Clearing and caching configuration...${NC}"

php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo -e "${GREEN}✅ Cache cleared and optimized${NC}"
echo ""

# ============= STEP 8: Set File Permissions =============
echo -e "${YELLOW}Step 8: Setting file permissions...${NC}"

chmod -R 775 storage bootstrap/cache || echo -e "${YELLOW}⚠️  Could not set storage permissions (may need sudo)${NC}"
chmod -R 755 public/ || echo -e "${YELLOW}⚠️  Could not set public permissions (may need sudo)${NC}"

echo -e "${GREEN}✅ File permissions set${NC}"
echo ""

# ============= FINAL CHECKS =============
echo -e "${YELLOW}Final Verification:${NC}"
echo ""

# Check environment
ENV_CHECK=$(php artisan tinker --execute="echo config('app.env');" 2>/dev/null || echo "unknown")
echo "Environment: $ENV_CHECK"

# Check debug mode
DEBUG_CHECK=$(php artisan tinker --execute="echo config('app.debug') ? 'ON' : 'OFF';" 2>/dev/null || echo "unknown")
echo "Debug Mode: $DEBUG_CHECK"

echo ""
echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
echo ""
echo -e "${YELLOW}NEXT STEPS:${NC}"
echo "1. ✅ Verify all .env variables are correct"
echo "2. ✅ Test the application: php artisan serve"
echo "3. ✅ Configure SSL in cPanel"
echo "4. ✅ Setup webhooks in payment gateways"
echo "5. ✅ Upload to production"
echo ""
echo "📖 See DEPLOYMENT_GUIDE.md for detailed instructions"
echo ""
