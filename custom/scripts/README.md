# Custom Scripts Directory

This directory contains utility scripts for SuiteCRM development and testing.

## Available Scripts

### generate_dummy_transactions.php

Generates dummy real estate transactions for testing the Kanban pipeline view.

**Usage:**

1. **Via Command Line (Recommended):**
   ```bash
   # Generate 20 transactions (default)
   php custom/scripts/generate_dummy_transactions.php
   
   # Generate specific number of transactions
   php custom/scripts/generate_dummy_transactions.php 50
   ```

2. **Via Web Browser:**
   ```
   http://localhost:8080/custom/scripts/generate_dummy_transactions.php?count=30
   ```

3. **Via Docker:**
   ```bash
   docker-compose exec web php custom/scripts/generate_dummy_transactions.php 25
   ```

**What it creates:**
- Random buyer/seller accounts
- Real estate transactions with realistic data
- Properties at various sales stages
- Commission calculations
- Appropriate close dates based on stage

**After running:**
- View transactions list: `/index.php?module=Opportunities&action=index`
- View pipeline (Kanban): `/index.php?module=Opportunities&action=kanban`

## Adding New Scripts

Place any new utility scripts in this directory and update this README with usage instructions.