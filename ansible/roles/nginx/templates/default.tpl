server {
    listen  80;

    root {{ doc_root }}/web;
    index index.html index.php;

    server_name {{ servername }};

    #site root is redirected to the app boot script
    location = / {
        try_files @site @site;
    }

    #all other locations try other files first and go to our front controller if none of them exists
    location / {
        try_files $uri $uri/ @site;
    }

    #return 404 for all php files as we do have a front controller
    location ~ \.php$ {
        return 404;
    }
    
    location @site {
        fastcgi_pass   unix:/var/run/php-fpm/www.sock;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME $document_root/index.php;
        #uncomment when running via https
        #fastcgi_param HTTPS on;
    }
}


