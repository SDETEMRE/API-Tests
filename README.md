# Log Analytics API Documentation

This API provides functionalities to analyze log streams, including searching logs and providing aggregated counts of matches, as well as truncating the service logs table.

## Table of Contents

- [Introduction](#introduction)
- [Getting Started](#getting-started)
- [Usage](#usage)
- [API Description](#api-description)
- [API Endpoints](#api-endpoints)
- [Contact](#contact)

## Introduction

The Log Analytics API is designed to facilitate log stream analysis by providing endpoints to search logs and retrieve aggregated counts of matches. Additionally, it offers a functionality to truncate the service logs table.

## Key Features

- **Hexagonal Architecture:** The Log Analytics API follows the principles of hexagonal architecture, ensuring separation of concerns and a clear boundary between the application core and external systems.
- **Agnostic / Reusable Code:** The codebase is designed to be agnostic and reusable across different environments, promoting portability and flexibility.
- **Clean Software Development Principles:** The codebase adheres to the SOLID principles, DRY, KISS
- **Unit, Integration, Behat Tests:** The Log Analytics API includes unit tests, integration tests, and Behat tests, covering functionality at different levels and ensuring reliability and correctness.
- **TDD (Test-Driven Development) and BDD (Behavior-Driven Development) Approach:** Tests are written before the implementation code, following the TDD approach, and focus on behavior and user stories, following the BDD approach.

## Getting Started

To get started with using the Log Analytics API, follow these steps:

1. Clone the repository to your local machine.
2. Ensure you have Docker installed.
3. Run the `make setup` command to set up the Docker environment.
4. Use the provided OpenAPI specification (`openapi.yaml`) to interact with the API.

## Usage

Below are some common tasks and commands to interact with the API:

- **Start Docker environment:** `make start`
- **Stop Docker environment:** `make stop`
- **Execute migrations:** `make database-migrate`
- **Run tests:** `make test`
- **Run tests:** `make test-behat`
- **Run analysis:** `make analysis`

### Importing Logs

To start importing logs to the database, use the following command:

```bash
make import-logs
```

## API Description

This API conforms to the OpenAPI 3.0.0 specification. It is RESTful and provides endpoints for searching logs (`/count`) and truncating the service logs table (`/truncate`).

## API Endpoints
You can access the OpenAPI documentation through this url: http://127.0.0.1:8090/openapi
### /count

- **Description:** Searches logs and provides an aggregated count of matches.
- **Method:** GET
- **Parameters:**
    - `serviceNames`: (optional) An array of service names to filter logs.
    - `startDate`: (optional) Start date to filter logs (format: dateTime).
    - `endDate`: (optional) End date to filter logs (format: dateTime).
    - `statusCode`: (optional) Filter on request status code.
- **Responses:**
    - `200`: Successful response with a count of matching results.
    - `400`: Bad input parameter.

### /truncate

- **Description:** Truncates the service logs table.
- **Method:** DELETE
- **Responses:**
    - `204`: Truncate was successful.
    - `400`: Bad input parameter.


## Contact

For any inquiries or feedback, please contact:

- **Name:** Sina Fetrat
- **Email:** sinafetrat@gmail.com
