# research-coder-api

Backend supporting <a href="https://researchcoder.com">researchcoder.com</a>.
  
## Installation
The following tutorial is for local development only. Do not use it for a production deployment.

### Docker (the easy way)

You'll need:
+ <a href="https://git-scm.com/book/en/v2/Getting-Started-Installing-Git">git</a>
+ <a href="https://docs.docker.com/engine/installation/">docker</a>
+ <a href="https://docs.docker.com/compose/install/">docker-compose</a>

Clone the repo and enter the new directory
~~~
git clone https://github.com/uab-energetics/research-coder-api
cd research-coder-api
~~~

Start the development server, opting to run the database migrations. If you didn't add your user to the docker group, you may need to prefix with `sudo`
~~~
./docker-dev.sh --migrate
~~~


## Daily use

### Docker

Start up the docker containers with the following command, optionally passing in any arguments that `docker-compse up` accepts. Migrate the database with `--migrate`. The `--down` switch maps to `docker-compose down` and accepts its extra arguments. This will start a local server on `http://localhost:8080`
~~~
./docker-dev.sh
~~~
