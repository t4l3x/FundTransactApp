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
2. Install dependencies: `composer install`. or `docker compose -up -d`

### Development Environment

- This project uses Laravel Sail for development and Docker containers.
- Ensure you have Docker and Sail installed: [Laravel Sail Documentation](https://laravel.com/docs/8.x/sail).
1. Start the development environment with Sail: (sail up -d, ./vendor/bin/sail  
   up -d, docker compose up -d)
2. Run Migrations `sail artisan migrate`

### Usage
- Use the API endpoints to interact with the service.
- Import the provided Postman collection located at `tests/mintos.postman_collection.json`. This collection contains a set of API requests for testing the application.
- Send a POST request to the `/api/register `endpoint to register a new user. You will receive a response containing the user information. 
- After registering, obtain an access token. This token will be used as a bearer token for subsequent API requests.In Postman, set the access token as a bearer token in the "Authorization" section of your requests. This will authenticate your requests with the token.
### Testing

- Run tests with `php artisan test`.
