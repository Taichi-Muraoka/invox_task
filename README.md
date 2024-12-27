# Docker-Laravel-mypage

## git clone
プロジェクト作成したいフォルダで
```
git clone git@github.com:Taichi-Muraoka/docker-laravel-mypage.git
```

## docker関連ファイル作成
以下のように`docker`フォルダを作成し、その配下に`mysql`,`nginx`,`php`フォルダを作成する。
![image](https://github.com/user-attachments/assets/d902f3c3-5b71-4657-b5f5-46b7d9124eae)

#### nginxフォルダ
以下のように`logs`フォルダ作成と`Dockerfile`,`default.conf`ファイルを作成する。
![image](https://github.com/user-attachments/assets/0ee9c509-7e74-44e7-bc54-370b6847f7ff)

`logs`の中身は空

- Dockerfile
```
FROM nginx:1.27
COPY ./default.conf /etc/nginx/conf.d/default.conf
```

- default.conf
```
server {
    
    listen 80;
    server_name _;

    client_max_body_size 1G;

    root /src/public;
    index index.php;

    access_log /src/docker/nginx/logs/access.log;
    error_log  /src/docker/nginx/logs/error.log;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;    
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
        
}
```

#### phpフォルダ
以下のように`Dockerfile`,`php.ini`ファイルを作成する。  
![image](https://github.com/user-attachments/assets/31db2d52-395f-48dc-83cc-0088cc6d8bce)

- Dockerfile
```
FROM php:8.3-fpm
EXPOSE 5173
RUN apt-get update \
    && apt-get install -y \
    git \
    zip \
    unzip \
    vim \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libfontconfig1 \
    libxrender1

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install pdo_mysql mysqli exif
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

ENV NODE_VERSION=20.15.0
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"
RUN node --version
RUN npm --version

WORKDIR /src
ADD . /src/storage
RUN chown -R www-data:www-data /src/storage

ENTRYPOINT [ "bash", "-c", "composer install; npm install; exec php-fpm" ]
```

- php.ini
```
upload_max_filesize=256M
post_max_size=256M
```

## dockerコンテナビルド
```
cd docker-laravel-mypage
```

```
docker compose up -d --build 
```

`docker ps -a`コマンドなどでコンテナが4つ立ち上がっていることを確認

自分はvscodeの拡張機能で確認

![image](https://github.com/user-attachments/assets/4a59167d-1a97-4743-b4ef-52c9ef6299e7)

## .env設定
プロジェクトディレクトリ直下で実行
```
cp .env.example .env
```

コピーしてできた`.env`を修正  
元々あったDB設定をコメントアウトして今回のものに合わせる
```
# DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_docker01
DB_USERNAME=root
DB_PASSWORD=root
```

## 必要なコマンド実施
appサーバーのコンソールに入る
```
docker compose exec app bash
```
![image](https://github.com/user-attachments/assets/b505486a-e1fb-4181-be0f-662bd3fc3540)

appサーバーのコンソール内で以下実施
```
php artisan key:generate
```

```
php artisan migrate
```

## アクセス
http://localhost:80
