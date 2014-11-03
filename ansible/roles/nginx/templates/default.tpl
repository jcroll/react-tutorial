server {
    listen  80;

    root {{ doc_root }}/web;

    server_name {{ servername }};

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/(index|index_dev)\.php(/|$) {
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS off;
    }

    error_log /var/log/nginx/project_error.log;
    access_log /var/log/nginx/project_access.log;
}


