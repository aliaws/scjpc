#!/bin/bash

# Define the name of the zip file
ZIP_NAME="scjpc.zip"
rm -rf ZIP_NAME

# Find and zip all files and folders except .git, .idea, and *.zip
zip -r "$ZIP_NAME" . -x "*.git*" "*.idea*" "*.zip" "generate.sh" "package.json" "composer.lock" "composer.json" "webpack.config.js" "node_modules/*"
# Output message

mv $ZIP_NAME ~/Downloads/
echo "Created $ZIP_NAME successfully!"