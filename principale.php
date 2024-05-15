<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BENVENUTO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
        }
        p {
            color: #555;
        }
        pre {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>BENVENUTO</h1>

        <h2>Installare Docker e Docker-Compose</h2>
        <p>Per realizzare questo compito ho dovuto innanzitutto installare Docker e Docker-Compose insieme ad Apache e MySQL.</p>
        <p>Ho installato Docker-Compose eseguendo i seguenti passaggi:</p>
        <pre><code>$ sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose</code></pre>
        <pre><code>$ sudo chmod +x /usr/local/bin/docker-compose</code></pre>
        <p>Verifica dell'installazione:</p>
        <pre><code>$ docker-compose --version</code></pre>

        <h2>Aggiungi Nginx e SSL</h2>
        <p>Nginx è un server web open-source noto per le sue prestazioni elevate e la gestione efficiente delle connessioni, mentre SSL (Secure Sockets Layer) è un protocollo di crittografia che assicura una comunicazione sicura su Internet.</p>
        <p>Per aggiungere Nginx e SSL ho eseguito i seguenti passaggi:</p>
        <pre><code>$ mkdir ~/ssl</code></pre>
        <p>Poi ho generato il certificato SSL e inserito le informazioni richieste.</p>
        <pre><code>$ sudo docker run -d --name proxyapp --network docker-project_default -p 443:443 -e DOMAIN=*.compute-1.amazonaws.com -e TARGET_PORT=80 -e TARGET_HOST=docker-project-nginx-1 -e SSL_PORT=443 -v ~/ssl:/etc/nginx/certs --restart unless-stopped fsouza/docker-ssl-proxy</code></pre>

        <h2>Configura PHP</h2>
        <p>Ho configurato PHP seguendo questi passaggi:</p>
        <pre><code>$ mkdir ~/progetto-aws/php</code></pre>
        <p>Clona la repository di GitHub:</p>
        <pre><code>$ git clone https://github.com/VieriF/progetto-AWS</code></pre>
        <p>Crea un file chiamato Dockerfile:</p>
        <pre><code>$ sudo nano Dockerfile</code></pre>
        <p>Dentro al file metti il seguente codice:</p>
        <pre><code>FROM php:7.0-fpm
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli</code></pre>

        <h2>Configura Nginx</h2>
        <p>Crea una cartella per Nginx:</p>
        <pre><code>$ mkdir ~/progetto-aws/nginx</code></pre>
        <p>Crea un file di configurazione per Nginx:</p>
        <pre><code>$ sudo nano ~/progetto-aws/nginx/default.conf</code></pre>
        <p>E aggiungi il seguente codice:</p>
        <pre><code>server {  
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

    location ~ .php$ {  
        fastcgi_split_path_info ^(.+.php)(/.+)$;  
        fastcgi_pass php:9000;  
        fastcgi_index index.php;  
        include fastcgi_params;
        fastcgi_read_timeout 300;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;  
        fastcgi_intercept_errors off;  
        fastcgi_buffer_size 16k;  
        fastcgi_buffers 4 16k;  
    }  

    location ~ /.ht {  
        deny all;  
    }  
}</code></pre>
        <p>Dopo aver salvato i cambiamenti, aggiungi le seguenti linee di codice al file Dockerfile:</p>
        <pre><code>COPY ./default.conf /etc/nginx/conf.d/default.conf</code></pre>

        <h2>Avvia i container</h2>
        <p>Aggiorna il file `docker-compose.yml` con le seguenti linee di codice:</p>
        <pre><code>version: "3.9"
services:
    nginx:
        build: ./nginx/
        ports:
            - 80:80
        volumes:
            - ./php_code/:/var/www/html/

    php:
        build: ./php_code/
        expose:
            - 9000
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
    mysql-data:</code></pre>
        <p>Poi avvia i container con il comando:</p>
        <pre><code>$ sudo docker-compose up -d</code></pre>

        <h2>Configura MariaDB</h2>
        <p>Aggiorna il file di `docker-compose.yml`:</p>
        <pre><code>version: "3.9"
services:
    nginx:
        build: ./nginx/
        ports:
            - 80:80
        volumes:
            - ./php_code/:/var/www/html/

    php:
        build: ./php_code/
        expose:
            - 9000
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
    mysql-data:</code></pre>
        <p>Poi avvia il container:</p>
        <pre><code>$ sudo docker-compose up -d</code></pre>

        <p>Creazione della CLI dentro a MariaDB:</p>
        <pre><code>$ sudo docker exec -it docker-project-db-1 /bin/sh</code></pre>
        <p>Accedi a MariaDB:</p>
        <pre><code>$ mariadb -u root -pmariadb</code></pre>
        <p>Crea un nuovo utente per il database:</p>
        <pre><code>CREATE USER 'vieri'@'%' IDENTIFIED BY "avieri";</code></pre>
        <p>Dagli tutti i permessi:</p>
        <pre><code>GRANT ALL PRIVILEGES ON *.* TO 'vieri'@'%';</code></pre>
        <p>Ricarica tutti i privilegi:</p>
        <pre><code>FLUSH PRIVILEGES;</code></pre>
        <p>Crea un nuovo database:</p>
        <pre><code>CREATE DATABASE site;</code></pre>
        <p>Utilizza il database appena creato:</p>
        <pre><code>USE site;</code></pre>
        <p>Crea una tabella:</p>
        <pre><code>CREATE TABLE users (username VARCHAR(30) NOT NULL, password VARCHAR(32) NOT NULL);</code></pre>

        <p>Ora il sito dovrebbe funzionare.</p>
    </div>
</body>
</html>
