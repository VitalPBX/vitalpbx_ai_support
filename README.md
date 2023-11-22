# VitalPBX - AI Support with ChatGPT

## Necessary Resources
OpenAI Account (https://platform.openai.com/apps).

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

## Install Database
<pre>
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_agent_ai_chatgpt/main/vpbx_agentai.sql
  mysql -u root < vpbx_agentai.sqlackup.sql
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
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_agent_ai_chatgpt/main/install.sh
</pre>

Give execution permissions
<pre>
  chmod +x install.sh
</pre>

Run the script
<pre>
  ./install.sh
</pre>

## Embedding Document
To transfer our documents to the ChromaDB database we must do the following:<br>
1.- Upload the document to the /usr/share/vpbx_ai_support/docs folder with the information to use for the query with ChatGPT-Embedded<br>
2.- To transfer this document to a Vector database (ChromaDB), proceed to execute the following command.
<pre>
  cd /usr/share/vpbx_ai_support/
  ./embedded-docs.py
</pre>

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
Don 192.168.57.50 is our public or private IP.
