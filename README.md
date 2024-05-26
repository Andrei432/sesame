# Sesame Project

The following installation process is tested under Ubuntu 24.04. Ensure you have Docker and Symfony installed. 

## Prerequisites

- Docker
- Docker Compose
- Symfony CLI
- Composer

## Installation

Follow these steps to set up and run the project:

1. Clone the Repository

Download the Git repository.

git clone http://... sesame

2. Install Composer Dependencies

Navigate to the `app` directory and install the Composer dependencies.

cd sesame/app
composer install

3. Start Docker Services

Open a new terminal and run Docker Compose to start the necessary services.

sudo docker compose up

4. Run Database Migrations

In the `app` directory, run the Doctrine migrations to set up the database schema.

php bin/console doctrine:migrations:migrate

5. Set sqlite database write permissions:

chmod 666 var/data.db

6. Run Tests

Run the PHPUnit tests to ensure everything is set up correctly.

php bin/phpunit

## Additional Information

- Ensure Docker is running before starting the Docker Compose services.
- If you encounter any issues with permissions, you might need to adjust the ownership or permissions of certain directories.
- You can stop the Docker services by pressing `Ctrl+C` in the terminal where `docker compose up` is running or by running `sudo docker compose down` in a new terminal.

## Troubleshooting

- **Composer Install Errors**: Ensure Composer is installed and the internet connection is stable.
- **Docker Issues**: Verify Docker is installed correctly and the Docker daemon is running.
- **Migration Issues**: Check the database configuration in the `.env` file and ensure the database service is running.

## Useful Commands

- **Start Docker Services**: `sudo docker compose up`
- **Stop Docker Services**: `sudo docker compose down`
- **Run Database Migrations**: `php bin/console doctrine:migrations:migrate`
- **Run Tests**: `php bin/phpunit`
- **Clear Symfony Cache**: `php bin/console cache:clear`

## Support

If you encounter any issues or have any questions, feel free to open an issue on the GitHub repository or contact the maintainers.

---

Happy coding!