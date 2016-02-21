# Tinify
Tinify is a simple PHP URL shortner web application

Tinify is already online at [tinify.co](https://tinify.co). You can check it out to see how it looks and works

Additionaly, Tinify is designed to work with an API, so different apps can use it. Currently the Android app is available on [Play Store](https://play.google.com/store/apps/details?id=co.tinify.app)

In order to install tinify on your own server you need to install a web server (tinify.co runs on nginx) and MySQL.
To install and run follow these simple steps

1. Download and unzip the files on the main web server directory.
2. Edit file `config.php` with the correct data
3. From the DATABASE directory import the data to MySQL. Start with `tinify.sql` and then import `ip2nation.sql`
4. The application should be ready to run, however to be able to redirect using `/abc` you need to configure rewrite on your web server. For nginx see Rewrite Rules below.
5. Make other changes if necessary.

#####Rewrite Rules
This is an example for nginx, if you are running Apache or something else please refer to the documentation 
```
map $uri $rewrite {
  /                                               0;
  "~*(\/.*\/)"                                    0;
  "~*(\.)"                                        0;
  default                                         1;
}
server{
  ...
  location / {
    try_files $uri $uri/ =404;
    if ($rewrite = 1){
      rewrite ^/(.*)$ /redirect.php?url_id=$1 last;
    }
  }
}
```
