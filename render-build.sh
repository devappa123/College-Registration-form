#!/bin/bash
# Render build script

echo "Setting up production environment..."

# Copy production files
cp config_production.php config.php
cp submit_production.php submit.php

echo "Build complete!"
