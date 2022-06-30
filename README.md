<<<<<<< HEAD
# Agms_GateEropa
=======
<p align="center"><img src="http://advision.alfabeta.co.id/assets/images/logo-advision-white.png" width="400"></p>

# Automated Visitor Management System by Alfabeta
# (prev. advision) 

Advision is a web application with AI and Computer Vision

## Dependencies
* Docker > 19.03
~~~
$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
$ sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
$ apt-cache policy docker-ce
$ sudo apt-get install -y docker-ce
~~~
* Docker Compose 1.24.1
~~~
$ sudo curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
$ sudo chmod +x /usr/local/bin/docker-compose
~~~

## Prepare .env
* Create .env file
~~~
$ cp .env.example .env
~~~
* Edit your desirable .env variables (Probably use 172.17.0.1 as MySQL Host)

## Build and Run -- Happy Hacking !
~~~
$ sudo docker-compose up --build -d
$ sudo docker-compose exec app composer install
$ sudo docker-compose exec app php artisan key:generate
$ sudo docker-compose exec app php artisan migrate --seed
~~~

NOTE: please wait until composer install & migration command done, you can check it with command: `docker logs -f advision`


### CI/CD with Jenkins
```
$ git tag <version>
$ git push origin <version>
```

example
```
$ git tag v1.0.0
$ git push origin v1.0.0
```
so jenkins will build the docker image with tag docker.alfabeta.co.id/advision-data-collector:v1.0.0 and the latest


## Contributing

- [Yusuf Faisal](https://www.linkedin.com/in/yusuf-faisal-agus-saputro/).

## License

The Advision is software licensed under the [Advision license](https://alfabeta.co.id/).
>>>>>>> ac61bb3 (Agms_GateEropa)
