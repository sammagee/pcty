# Paylocity Coding Challenge

## Installation

**Note:** You will need to install [Docker Desktop](https://www.docker.com/products/docker-desktop) to start this application locally.

Download the repository:
```
git clone https://github.com/sammagee/pcty
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
sail npm install && sail npm run dev
```

Run the seeder:
```
sail artisan seed {number of employees to seed} -U -F
```

Index the search database:
```
sail artisan scout:index employees
sail artisan scout:flush "App\Models\Employee"
sail artisan scout:import "App\Models\Employee"
```

At this point you should be able to open [`localhost`](http://localhost) in your browser to view the project!
