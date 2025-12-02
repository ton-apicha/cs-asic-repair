# Deployment Guide

## Production Server Information
- **Server IP**: 152.42.201.200
- **Server User**: root
- **Project Path**: /var/www/cs-asic-repair
- **Container Name**: asic-repair-app

## Deployment Process

### 1. Local Development
```bash
# Make your changes locally
cd d:\VSCode\cs-asic-repair\cs-asic-repair

# Test locally if needed
# (Docker commands for local testing)
```

### 2. Commit and Push to GitHub
```bash
# Add changed files
git add .

# Commit with descriptive message
git commit -m "Description of changes"

# Push to GitHub
git push origin main
```

### 3. Deploy to Production Server
```bash
# SSH into server, pull latest code, and restart container
ssh root@152.42.201.200 "cd /var/www/cs-asic-repair && git pull origin main && docker compose restart app"
```

**Note**: This single command will:
1. Connect to production server via SSH
2. Navigate to project directory
3. Pull latest code from GitHub
4. Restart the Docker container to apply changes

### 4. Verify Deployment
- Visit http://152.42.201.200 to verify changes
- Test on both desktop and mobile devices
- Check browser console for any errors

## Common Issues

### SSH Connection Issues
If you get "Permission denied (publickey)":
1. Generate SSH key on your machine: `ssh-keygen -t rsa -b 4096`
2. Copy public key: `cat ~/.ssh/id_rsa.pub`
3. Add to server via Digital Ocean Console
4. Paste into `/root/.ssh/authorized_keys` on server

### Git Pull Fails
If git pull shows conflicts:
```bash
# SSH into server
ssh root@152.42.201.200

# Navigate to project
cd /var/www/cs-asic-repair

# Reset to latest GitHub version (WARNING: loses local changes)
git fetch origin
git reset --hard origin/main

# Restart container
docker compose restart app
```

### Container Won't Start
```bash
# SSH into server
ssh root@152.42.201.200

# Check container logs
docker compose logs app

# Rebuild if needed
docker compose down
docker compose up -d --build
```

## Rollback Procedure
If deployment causes issues:
```bash
# SSH into server
ssh root@152.42.201.200
cd /var/www/cs-asic-repair

# View git history
git log --oneline -10

# Rollback to previous commit (replace COMMIT_HASH)
git reset --hard COMMIT_HASH

# Restart container
docker compose restart app
```

## Environment Variables
Check `.env` file on server for:
- Database credentials
- App environment (production/development)
- Debug settings

## Database Migrations
If database changes are needed:
```bash
ssh root@152.42.201.200
cd /var/www/cs-asic-repair
docker compose exec app php spark migrate
```
