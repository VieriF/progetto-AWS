<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BENVENUTO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        h2 {
            color: #444;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }
        p {
            font-size: 1rem;
            line-height: 1.6;
        }
        .code-box {
            background-color: #eee;
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            overflow-x: auto;
        }
        pre {
            margin: 0;
        }
    </style>
</head>
<body>
    <h1>BENVENUTO</h1>
    <p>Per realizzare questo compito ho dovuto inanzitutto installare docker e docker-compose insieme a apache e mysql</p>

    <h2>Installare docker-compose</h2>
    <p>Ho installato docker-compose con i seguenti comandi:</p>
    <div class="code-box">
        <pre>
$ sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
$ sudo chmod +x /usr/local/bin/docker-compose
        </pre>
    </div>
    <p>Verifica poi l'installazione:</p>
    <div class="code-box">
        <pre>
$ docker-compose --version
        </pre>
    </div>
    <p>Per poi creare la cartella del progetto:</p>
    <div class="code-box">
        <pre>
$ mkdir ~/progetto-aws
        </pre>
    </div>

    <h2>Aggiungi Nginx e SSL</h2>
    <p>Nginx è un server web open-source noto per le sue prestazioni elevate e la gestione efficiente delle connessioni mentre SSL (Secure Sockets Layer) è un protocollo di crittografia che assicura una comunicazione sicura su Internet, garantendo che i dati trasmessi tra il client e il server siano crittografati e protetti da intercettazioni.</p>
    <p>Inizia creando il file docker-compose.yml:</p>
    <div class="code-box">
        <pre>
$ sudo nano docker-compose.yml
        </pre>
    </div>
    <p>Poi bisogna copiare questo codice nel file appena creato e salvare:</p>
    <div class="code-box">
        <pre>
version: "3.9"
services:
  nginx:
    image: nginx:latest
    container_name: nginx-container
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./ssl:/etc/nginx/certs
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
      - ./php_code/:/var/www/html/
        </pre>
    </div>
    <p>Crea una cartella per il certificato SSL:</p>
    <div class="code-box">
        <pre>
$ mkdir ~/ssl
        </pre>
    </div>
    <p>Poi genera il certificato SSL e inserire le informazioni corrette richieste (personalmente ho utilizzato le informazioni trovate sul file mandato su classroom dal prof. Andronaco).</p>
    <p>E connettiti al container di Nginx:</p>
    <div class="code-box">
        <pre>
$ sudo docker run -d --name proxyapp --network docker-project_default -p 443:443 -e DOMAIN=*.compute-1.amazonaws.com -e TARGET_PORT=80 -e TARGET_HOST=docker-project-nginx-1 -e SSL_PORT=443 -v ~/ssl:/etc/nginx/certs --restart unless-stopped fsouza/docker-ssl-proxy
        </pre>
    </div>

    <h2>Configura PHP</h2>
    <p>Crea una cartella per PHP:</p>
    <div class="code-box">
        <pre>
$ mkdir ~/progetto-aws/php
        </pre>
    </div>
    <p>Clona la repository di GitHub:</p>
    <div class="code-box">
        <pre>
$ git clone https://github.com/VieriF/progetto-AWS
        </pre>
    </div>
    <p>Ora crea un file chiamato Dockerfile:</p>
    <div class="code-box">
        <pre>
$ sudo nano Dockerfile
        </pre>
    </div>
    <p>Dentro al file metti il codice qui sotto:</p>
    <div class="code-box">
        <pre>
FROM php:7.0-fpm
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli
        </pre>
    </div>
    <p>Ora crea una cartella per Nginx:</p>
    <div class="code-box">
        <pre>
$ mkdir ~/progetto-aws/nginx
        </pre>
    </div>
    <p>Crea un file di configurazione per Nginx:</p>
    <div class="code-box">
        <pre>
$ sudo nano ~/progetto-aws/nginx/default.conf
        </pre>
    </div>
    <p>E aggiungi questo codice dentro al file Nginx default.conf:</p>
    <div class="code-box">
        <pre>
server {  
  listen 80 default_server;  
  root /var/www/html;  
  index index.html index.php;  
  charset utf-8;  

  location / {  
    try_files $uri $uri/ /index.php?$query_string;  
  }  

  location = /favicon.ico { access_log off; log_not_found off; }  
  location = /robots.txt { access_log off; log_not_found off; }  

  access_log off;  
  error_log /var/log/nginx/error.log error;  

  sendfile off;  
  client_max_body_size 100m;  

  location ~ \.php$ {  
    fastcgi_split_path_info ^(.+\.php)(/.+)$;  
    fastcgi_pass php:9000;  
    fastcgi_index index.php;  
    include fastcgi_params;
    fastcgi_read_timeout 300;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;  
    fastcgi_intercept_errors off;  
    fastcgi_buffer_size 16k;  
    fastcgi_buffers 4 16k;  
  }  

  location ~ /\.ht {  
    deny all;  
  }  
}
        </pre>
    </div>
    <p>Dopo aver salvato i cambiamenti al file, aggiungi le seguenti linee di codice al file Dockerfile:</p>
    <div class="code-box">
        <pre>
FROM nginx
COPY ./default.conf /etc/nginx/conf.d/default.conf
        </pre>
    </div>
    <p>Aggiorna docker-compose.yml con le seguenti linee di codice:</p>
    <div class="code-box">
        <pre>
version: "3.9"
services:
  nginx:
    build: ./nginx/
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./php_code/:/var/www/html/

  php:
    build: ./php_code/
    expose:
      - "9000"
    volumes:
      - ./php_code/:/var/www/html/
        </pre>
    </div>
    <p>Ora fai partire il container:</p>
    <div class="code-box">
        <pre>
$ sudo docker-compose up -d
        </pre>
    </div>
    <p>Controlla se nei container compare il container appena creato:</p>

    <h2>Configura MariaDB</h2>
    <p>Aggiorna il file docker-compose.yml:</p>
    <div class="code-box">
        <pre>
version: "3.9"
services:
  nginx:
    build: ./nginx/
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./php_code/:/var/www/html/

  php:
    build: ./php_code/
    expose:
      - "9000"
    volumes:
      - ./php_code/:/var/www/html/

  db:    
    image: mariadb  
    volumes: 
      - mysql-data:/var/lib/mysql
    environment:  
      MYSQL_ROOT_PASSWORD: mariadb
      MYSQL_DATABASE: AWS

volumes:
  mysql-data:
        </pre>
    </div>
    <p>Ora fai partire il container:</p>
    <div class="code-box">
        <pre>
$ sudo docker-compose up -d
        </pre>
    </div>
    <p>Crea la CLI dentro a MariaDB:</p>
    <div class="code-box">
        <pre>
$ sudo docker exec -it docker-project-db-1 /bin/sh
        </pre>
    </div>
    <p>E accedi a MariaDB:</p>
    <div class="code-box">
        <pre>
$ mariadb -u root -pmariadb
        </pre>
    </div>
    <p>Crea uno user per il db:</p>
    <div class="code-box">
        <pre>
CREATE USER 'vieri'@'%' IDENTIFIED BY "avieri";
        </pre>
    </div>
    <p>Dagli tutti i permessi:</p>
    <div class="code-box">
        <pre>
GRANT ALL PRIVILEGES ON *.* TO 'vieri'@'%';
        </pre>
    </div>
    <p>E ricarica tutti i privilegi:</p>
    <div class="code-box">
        <pre>
FLUSH PRIVILEGES;
        </pre>
    </div>
    <p>Ora crea un nuovo database:</p>
    <div class="code-box">
        <pre>
CREATE DATABASE site;
        </pre>
    </div>
    <p>Usa il sito del database:</p>
    <div class="code-box">
        <pre>
USE site;
        </pre>
    </div>
    <p>Ora utilizza SQL per creare la tabella users:</p>
    <div class="code-box">
        <pre>
CREATE TABLE users (username VARCHAR(30) NOT NULL, password VARCHAR(32) NOT NULL);
        </pre>
    </div>
    <p>Ora il sito dovrebbe funzionare.</p>
</body>
</html>
