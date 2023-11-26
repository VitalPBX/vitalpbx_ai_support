# VitalPBX - AI Support with ChatGPT

## Necessary Resources
OpenAI Account (https://platform.openai.com/apps).<br>
Postfix for sending emails<br>
Python, for ChatGPT query service<br>
PHP 8<br>
Chroma database<br>
MariaDB 10<br>
Apache<br>

## Installing dependencies
<pre>
  apt update
  apt install python3 python3-pip
  pip install websocket-client
  pip install asyncio
</pre>

<pre>
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/requirements.txt
</pre>

<pre>
  pip install -r requirements.txt
</pre>

## Email configuration
This example is made to work with Postfix, so we recommend you configure it correctly before proceeding.

Install Postfix
<pre>
  sudo apt install postfix
</pre>

## Install Database
<pre>
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/vpbx_agentai.sql
  mysql -u root -e  "CREATE DATABASE vpbx_agentai;"
  mysql -u root vpbx_agentai < vpbx_agentai.sql
</pre>

Create User
<pre>
  mysql -u root
  GRANT ALL PRIVILEGES ON vpbx_agentai.* TO myuser@'localhost' IDENTIFIED BY 'mypassword';
  FLUSH PRIVILEGES;
  exit;
</pre>

## Install from script
Download the script
<pre>
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_ai_support/main/install.sh
</pre>

Give execution permissions
<pre>
  chmod +x install.sh
</pre>

Run the script
<pre>
  ./install.sh
</pre>

## Edit .env, database.php,vpbxaisupport.py and embededded-docs.py
Now we are going to edit the .env file to add the Openai API Key and the paths to the documents and the ChromaDB database.
<pre>
  cd /usr/share/vpbx_ai_support/
  nano .env
</pre>

Later we are going to edit the database.php file to configure the access credentials to the amriadb database
<pre>
  cd /var/www/vpbx_ai_support/html/
  nano dtabase.php
</pre>

In vpbxaisupport.py change the route of valid certificate
<pre>
    ssl_cert = "/usr/share/PathToCertificate/bundle.pem"
    ssl_key = "/usr/share/PathToCertificate/private.pem"
</pre>

## Embedding Document
To transfer our documents to the ChromaDB database we must do the following:<br>
1.- Upload the document to the /usr/share/vpbx_ai_support/docs folder with the information to use for the query with ChatGPT-Embedded<br>
2.- To transfer this document to a Vector database (ChromaDB), proceed to execute the following command.
<pre>
  cd /usr/share/vpbx_ai_support/
  ./embedded-docs.py
</pre>

## Create apache configuration file
Remember to create the Apache configuration file to access the web site

### Note
Remember to unblock port 3002 or the one you decided to use in the VitalPBx firewall as in any other firewall that VitalPBX has in front of you.<br>
To make sure everything is fine, we can run the following command.
<pre>
  netstat -tuln | grep 3002
</pre>
And it would have to return the following to us:
<pre>
tcp        0      0 192.168.57.50:3002       0.0.0.0:*               LISTEN     
tcp        0      0 127.0.1.1:3002           0.0.0.0:*               LISTEN  
</pre>
192.168.57.50 is our public or private IP.
