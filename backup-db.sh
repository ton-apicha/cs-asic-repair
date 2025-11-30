#!/bin/bash

#=============================================================================
# Database Backup Script
# 
# Creates a backup of the MySQL database
#=============================================================================

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Load environment variables
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
fi

# Create backup directory if not exists
BACKUP_DIR="backups"
mkdir -p $BACKUP_DIR

# Generate filename with timestamp
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="$BACKUP_DIR/asic_repair_backup_$TIMESTAMP.sql"

echo -e "${YELLOW}📦 Creating database backup...${NC}"

# Create backup
docker-compose exec -T db mysqldump \
    -u ${DB_USERNAME:-asic_user} \
    -p${DB_PASSWORD} \
    ${DB_DATABASE:-asic_repair_db} \
    > $BACKUP_FILE

if [ $? -eq 0 ]; then
    # Compress backup
    gzip $BACKUP_FILE
    BACKUP_FILE="${BACKUP_FILE}.gz"
    
    # Get file size
    SIZE=$(du -h $BACKUP_FILE | cut -f1)
    
    echo -e "${GREEN}✅ Backup created successfully!${NC}"
    echo -e "   File: ${GREEN}$BACKUP_FILE${NC}"
    echo -e "   Size: ${GREEN}$SIZE${NC}"
    
    # Keep only last 7 backups
    echo -e "${YELLOW}🧹 Cleaning old backups (keeping last 7)...${NC}"
    ls -t $BACKUP_DIR/*.sql.gz | tail -n +8 | xargs -r rm
    
    echo -e "${GREEN}✅ Done!${NC}"
else
    echo -e "${RED}❌ Backup failed!${NC}"
    exit 1
fi

