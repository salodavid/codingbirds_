#!/bin/bash
# Deploy pos-project to local web root, preserving config.php
DEST="/Library/WebServer/Documents/pos.urafiki.co.mz"
rsync -av --exclude='config/config.php' "$(dirname "$0")/" "$DEST/"
echo "Deploy complete. config.php was preserved."
