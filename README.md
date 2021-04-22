# Paylocity Coding Challenge

## Installation

**Note:** You will need to install [Docker Desktop](https://www.docker.com/products/docker-desktop) to start this application locally.

1. Download the repository:
    ```
    git clone https://github.com/sammagee/pcty
    ```

2. Start the Docker image:
    ```
    cd pcty && ./vendor/bin/sail up
    ```

3. Compile the dependencies:
    ```
    sail npm install && sail npm run dev
    ```

4. Run the seeder:
    ```
    sail artisan seed {number of employees to seed} -U -F
    ```

5. Index the search database:
    ```
    sail artisan scout:index employees
    sail artisan scout:flush "App\Models\Employee"
    sail artisan scout:import "App\Models\Employee"
    ```

6. At this point you should be able to open `localhost` in your browser to view the project!
