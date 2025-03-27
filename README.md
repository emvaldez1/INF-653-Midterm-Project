# Quotes API

## Project Overview

Quotes API is a RESTful web service designed to manage and deliver quotes along with their respective authors and categories. It supports comprehensive CRUD operations for quotes, authors, and categories, facilitating integration with front-end applications or serving as a backend for mobile applications.

## Features

- **CRUD Operations**: Full support for creating, reading, updating, and deleting quotes, authors, and categories.
- **RESTful Interface**: Adheres to REST principles with clear HTTP method mapping.
- **JSON Responses**: Uniform JSON responses for easy consumption by clients.
- **Search Functionality**: Advanced querying capabilities to search quotes and authors.

## Technology Stack

- **PHP**: Utilized for server-side logic.
- **PostgreSQL**: Chosen for its robustness and support for complex queries.
- **Apache**: For hosting the PHP application.
- **Docker**: Used for creating reproducible environments for development and production.

## Installation

### Prerequisites

- PHP 7.4+
- Apache server with `mod_rewrite` enabled
- PostgreSQL 10+
- Composer (optional for managing PHP dependencies)
- Docker (optional for containerization)

### Local Setup

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/yourusername/quotes-api.git
   cd quotes-api
