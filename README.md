# Funds Transfer RESTful Service

## Project Structure

- [Functional Requirements](#functional-requirements)
- [Non-functional Requirements](#non-functional-requirements)
- [Getting Started](#getting-started)
- [Development Environment](#development-environment)
- [Usage](#usage)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

### Functional Requirements

- Given a client id, return a list of accounts.
- Given an account id, return transaction history with paging.
- Transfer funds between accounts with currency conversion.

### Non-functional Requirements

1. Test coverage: >= 80%.
2. Resilient to 3rd party service unavailability.
3. Database schema versioning.

### Getting Started

1. Clone this repository.
2. Install dependencies: `composer install`.
3. Set up environment variables in `.env`.
4. Generate an application key: `php artisan key:generate`.
5. Migrate the database: `php artisan migrate`.
6. Start the server: `php artisan serve`.

### Development Environment

- This project uses Laravel Sail for development and Docker containers.
- Ensure you have Docker and Sail installed: [Laravel Sail Documentation](https://laravel.com/docs/8.x/sail).
1. Start the development environment with Sail: (sail up -d, ./vendor/bin/sail  
   up -d, docker compose up -d)


### Usage
- Use the API endpoints to interact with the service.

### Testing

- Run tests with `php artisan test`.
