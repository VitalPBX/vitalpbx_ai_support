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
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_agent_ai_chatgpt/main/requirements.txt
</pre>

<pre>
  pip install -r requirements.txt
</pre>

## Install from script
Download the script
<pre>
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_agent_ai_chatgpt/main/vpbx-agent-ai.sh
</pre>

Give execution permissions
<pre>
  chmod +x vpbx-agent-ai.sh
</pre>

Run the script
<pre>
  ./vpbx-agent-ai.sh
</pre>

## Embedding Document
To transfer our documents to the ChromaDB database we must do the following::<br>
1.- Upload the document to the /var/lib/asterisk/agi-bin/docs folder with the information to use for the query with ChatGPT-Embedded<br>
2.- To transfer this document to a Vector database (ChromaDB), proceed to execute the following command.
<pre>
  cd /var/lib/asterisk/agi-bin/
  ./embedded-docs.py
</pre>


## Web Chat
It is also possible to ask questions to the document that we have uploaded through the web interface; for them we must follow the following procedure.

We are going to copy the chatserver.py file to the folder we want (It could be /var/lib/asterisk/agi-bin/).
<pre>
  cd /var/lib/asterisk/agi-bin/
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_agent_ai_chatgpt/main/chatbotserver.py
  chamod +x chatbotserver.py
</pre>

Edit the file you just downloaded (chatbotserver.py)
<pre>
  cd /var/lib/asterisk/agi-bin/
  nano chatbotserver.py
</pre>

Replace the following lines with your IP or Domain if you use SSL.<br>
If you use ssl with a valid domain remember to change ws to wss.
<pre>
start_server = websockets.serve(echo, 'Your_IP_or_Domain', 3002)
print("WebSocket server started on ws://Your_IP_or_Domain:3002")
</pre>

Now we will proceed to create the service
<pre>
  cd /etc/systemd/system/
  nano vpbxchatbot.service
</pre>

Copy and paste the following content
<pre>
[Unit]
Description=Agent AI
After=network.target

[Service]
ExecStart=/usr/bin/python3 /var/lib/asterisk/agi-bin/chatbotserver.py
Restart=always
User=root
Group=root
Environment=VariableDeEntorno=valor
WorkingDirectory=/var/lib/asterisk/agi-bin

[Install]
WantedBy=multi-user.target
</pre>

Enable and start the service
<pre>
systemctl enable vpbxchatbot
systemctl start vpbxchatbot
systemctl status vpbxchatbot
</pre>

Now we are going to download the chat.html file and copy it to the /usr/share/vitalpbx/www folder
<pre>
  cd /usr/share/vitalpbx/www
  mkdir chatbot
  cd chatbot
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_agent_ai_chatgpt/main/chatbot/index.html
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_agent_ai_chatgpt/main/chatbot/bootstrap.min.css
  wget https://raw.githubusercontent.com/VitalPBX/vitalpbx_agent_ai_chatgpt/main/chatbot/jquery.min.js
</pre>
For HTTPS: wss, for HTTP: ws. If you are not going to use SSL just enter the IP of your server, otherwise leave ${location.hostname}.<br>

Finally, edit the files vpbx-agent-ai-embedded.py and vpbx-agent-ai.py and uncomment everything related to sending messages via websocket.<br>

To see the chat in real time, run the url of your VitalPBX:<br>
For example:<br>
http://mypbxurl/chatbot<br>
or<br>
https://mypbxurl/chatbot
### Note
Remember to unblock port 3001, 3002 or the one you decided to use in the VitalPBx firewall as in any other firewall that VitalPBX has in front of you.<br>
To make sure everything is fine, we can run the following command.
<pre>
  netstat -tuln | grep 3001
</pre>
And it would have to return the following to us:
<pre>
tcp        0      0 192.168.57.50:3001       0.0.0.0:*               LISTEN     
tcp        0      0 127.0.1.1:3001           0.0.0.0:*               LISTEN  
</pre>
Don 192.168.57.50 is our public or private IP.
