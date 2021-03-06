# Paylocity Coding Challenge

## Installation

**Note:** You will need to install [Docker Desktop](https://www.docker.com/products/docker-desktop) to start this application locally.

Download the repository:
```
git clone https://github.com/sammagee/pcty
```

Install Composer dependencies:
```
cd pcty && docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```

## Setup

Copy the environment file:
```
cd pcty && cp .env.example .env
```

Start the Docker image:
```
./vendor/bin/sail up
```

Compile the dependencies:
```
./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev
```

Run the seeder:
```
./vendor/bin/sail artisan seed {number of employees to seed} -U -F
```

Index the search database:
```
./vendor/bin/sail artisan scout:index employees
./vendor/bin/sail artisan scout:flush "App\Models\Employee"
./vendor/bin/sail artisan scout:import "App\Models\Employee"
```

At this point you should be able to open [`localhost`](http://localhost) in your browser to view the project!

## Usage

### View mail locally
With the default configuration, a [MailHog](https://github.com/mailhog/MailHog) server will be running in the background when you start the server with the instructions above. In order to view the web interface for this, you will need to go to [`localhost:8025`](http://localhost:8025/). Here, you will be able to access emails sent locally (i.e. email verification emails).

### Start/Stop the server
Start:
```
cd pcty && ./vendor/bin/sail up -d
```

Stop:
```
cd pcty && ./vendor/bin/sail down
```
