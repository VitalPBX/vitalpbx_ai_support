#!/bin/bash
# This code is the property of VitalPBX LLC Company
# License: Proprietary
# Date: 9-Oct-2023
# VitalPBX Agent AI with ChatGPT(Embedded), Whisper, and Azure Speech(TTS)

# Exit on any error
set -e

# Display a welcome message
echo -e "************************************************************"
echo -e "*          Welcome to the AI Agent installation            *"
echo -e "************************************************************"

# Create necessary directories
mkdir /usr/share/vpbx_ai_support/
mkdir /usr/share/vpbx_ai_support/docs
mkdir /usr/share/vpbx_ai_support/data
mkdir /var/www/vpbx_ai_support/html
mkdir /var/www/vpbx_ai_support/html/css
mkdir /var/www/vpbx_ai_support/html/js


# Download required files from GitHub
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/agent-ai-create-py -P /usr/share/vpbx_ai_support/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/agent-ai-search.py -P /usr/share/vpbx_ai_support/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/embedded-docs.py -P /usr/share/vpbx_ai_support/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/vpbxaisupport.py -P /usr/share/vpbx_ai_support/

wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/.env  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/agentai.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/database.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/forgot-password.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/index.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/login.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/logout.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/mailer.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/process-reset-password.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/process-signup.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/reset-password.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/send-password-reset.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/signup-success.html  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/signup.html  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/validate-email.php  -P /var/www/vpbx_ai_support/html/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/css/bootstrap.min.css -P /var/www/vpbx_ai_support/html/css/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/css/style.css -P /var/www/vpbx_ai_support/html/css/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/js/jquery.min.js  -P /var/www/vpbx_ai_support/html/js/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/js/just-validate.production.min.js  -P /var/www/vpbx_ai_support/html/js/
wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/js/validation.js  -P /var/www/vpbx_ai_support/html/

wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/vpbxaisupport.service  -P /etc/systemd/system/
chmod 777 /etc/systemd/system/vpbxaisupport.service
systemctl enable vpbxaisupport.service
systemctl start vpbxaisupport.service
systemctl status vpbxaisupport.service

# Set execute permissions for the downloaded scripts
chmod +x /usr/share/vpbx_ai_support/agent-ai-create-py
chmod +x /usr/share/vpbx_ai_support/agent-ai-search.py
chmod +x /usr/share/vpbx_ai_support/embedded-docs.py
chmod +x /usr/share/vpbx_ai_support/vpbxaisupport.py

# Display installation instructions
echo -e "\n"
echo -e "************************************************************"
echo -e "*          Remember to configure the .env file             *"
echo -e "************************************************************"
echo -e "\n"
echo -e "************************************************************"
echo -e "*       All components have been installed correctly       *"
echo -e "*                To ask VitalPBX AI Suppoert               *"
echo -e "*   Enter the website, register as a user and that's it.   *"
echo -e "************************************************************"
