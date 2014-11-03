server {
    listen  80;

    root {{ doc_root }}/web;

    server_name {{ servername }};

    location = / {
        try_files @site @site;
    }

    location / {
        try_files $uri $uri/ @site;
    }

    location ~ \.php$ {
        return 404;
    }

    location @site {
        fastcgi_pass   unix:/var/run/php5-fpm.sock;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME $document_root/index_dev.php;
    }
}


