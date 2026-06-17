# Deployment - cPanel FTP

**Host:** cpanel.arifhasan.me  
**Username:** sr@arifhasan.me  
**Password:** nfzp2kFtB@ftB-ak  
**Protocol:** FTP (TLS, skip certificate verification)

## Method: Zip Upload + Extract (Recommended)

### Step 1: Create deployment zip (local)
```bash
cd /var/www/vaft-cpanel
sudo zip -r /tmp/vaft-deploy.zip app/ resources/ routes/ database/ config/ lang/ bootstrap/ artisan composer.json composer.lock -x "storage/logs/*" "storage/framework/sessions/*" "storage/framework/cache/*"
```

### Step 2: Upload zip via FTP
```bash
lftp -u "sr@arifhasan.me,nfzp2kFtB@ftB-ak" cpanel.arifhasan.me -e "
set ssl:verify-certificate no
put /tmp/vaft-deploy.zip
quit
"
```

### Step 3: Extract on cPanel
Use cPanel File Manager → select `vaft-deploy.zip` → Extract  
Or via cPanel Terminal:
```bash
cd ~/public_html  # or wherever the app root is
unzip -o vaft-deploy.zip
rm vaft-deploy.zip
```

## Method: Direct Mirror (Slow, for small changes)

```bash
cd /var/www/vaft-cpanel && lftp -u "sr@arifhasan.me,nfzp2kFtB@ftB-ak" cpanel.arifhasan.me -e "
set ssl:verify-certificate no
set net:timeout 30
mirror -R --only-newer --exclude .git/ --exclude storage/logs/ --exclude storage/framework/sessions/ app/ app/
mirror -R --only-newer resources/ resources/
mirror -R --only-newer routes/ routes/
mirror -R --only-newer database/ database/
mirror -R --only-newer config/ config/
mirror -R --only-newer lang/ lang/
quit
"
```

## Important Notes
- Do NOT overwrite `.env` on remote unless DB config changes
- Remote DB: `arifhasan_vaft` on localhost:3306 (user: vaft_user)
- After new migrations: extract zip, then run via cPanel Terminal or a migration route
- Vendor folder is already on remote — don't include unless composer.json changes
- If composer.json changed: upload, then run `composer install --no-dev` via cPanel Terminal
