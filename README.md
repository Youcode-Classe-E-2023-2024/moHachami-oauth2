# README - Laravel API with OAuth2, Scopes, PHPUnit, and Swagger Documentation.

This README provides an overview of a Laravel API project that utilizes OAuth2 for authentication with scopes to handle roles and permissions. It also includes PHPUnit for testing and Swagger for API documentation.

## Project Overview

This Laravel API project is designed to provide secure authentication and authorization mechanisms using OAuth2 with scopes. It allows for role-based access control (RBAC) by assigning different scopes to users based on their roles, enabling granular control over API access.

## Features

1. **OAuth2 Authentication**: Implements OAuth2 for secure authentication using access tokens.
2. **Scopes for Role-based Access Control**: Utilizes scopes to assign specific permissions to users based on their roles.
3. **Role Management**: Provides endpoints to manage roles and assign permissions.
4. **PHPUnit Testing**: Includes PHPUnit tests to ensure the correctness of API endpoints and functionality.
5. **Swagger Documentation**: Utilizes Swagger for API documentation, making it easy for developers to understand and interact with the API.

## Installation

1. Clone the repository: `git clone <repository-url>`
2. Install dependencies: `composer install`
3. Set up environment variables: Configure `.env` file with database and other environment-specific settings.
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate` (Ensure database connection is configured)
6. Generate Passport keys: `php artisan passport:install`
7. Serve the application: `php artisan serve`

## Usage

1. Register a new user: Send a POST request to `/auth/register` with name, email, and password.
2. Login: Send a POST request to `/auth/login` with email and password to obtain an access token.
3. Access protected routes: Include the access token in the Authorization header for requests to protected routes.
4. Manage roles and permissions: Use provided endpoints (`/AddRole`, `/AddPermission`, `/AssignRoleToPermission`) to manage roles and permissions.
5. Access API documentation: Visit `/api-docs` to access Swagger documentation for the API endpoints.


## Testing

1. Run PHPUnit tests: Execute `php artisan test` to run the PHPUnit tests and ensure the API endpoints function as expected.
2. Write additional tests: Expand the test suite by adding more PHPUnit tests to cover additional functionalities and edge cases.

![Swagger-Docs](https://github.com/Youcode-Classe-E-2023-2024/moHachami-oauth2/blob/main/docs/screen.png)

