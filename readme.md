## Hops application feature list

Virtual laboratory for **A Hybrid Computational Framework for Hop Variety Similarity Assessment Based on Sensory and Biochemical Profiles** paper.

### Introduction

This document describes a web-based application designed to support a computational framework for comparing hop varieties, as developed in the research work **A Hybrid Computational Framework for Hop Variety Similarity Assessment Based on Sensory and Biochemical Profiles**. The core of the system is a Python-based similarity engine capable of processing textual and JSON inputs according to configurable comparison rules.

The application serves two primary purposes. First, it provides an experimental playground for researchers to explore parameterization, weighting schemes, and similarity logic during model development and validation. Second, it delivers a production-ready environment for end users requiring consistent and reproducible hop variety comparisons.

The web interface will be implemented primarily using the Laravel framework and will act as an integration layer between users and the Python computational engine. This document outlines the scope and objectives of the application and serves as a foundation for the detailed functional and technical specifications presented in subsequent sections.

### Features

#### User Management System

The application includes a user management system with authentication and authorization. Users can register via a public registration form or be created and managed by an administrator through the admin panel. Authentication is required to access advanced functionality such as running comparisons and using research tools.

#### Hop Variety Browser

A publicly accessible module allowing users to browse hop variety data collected from the Hopsteiner source. The browser provides read-only access to biochemical and descriptive information for all supported hop varieties.

#### Comparison Engine Interface

The comparison module is publicly visible but executable only by authenticated users. Users can submit queries either in natural language or via an advanced form that generates a structured JSON request, as defined by the OpenAPI specification of the comparison engine. Query would take form as below:

```python
query = {
  "target": {
    "present": List()<HopName()>,
    "absent": List()<HopName()>,
  },
  "aroma": {
    "present": List()<AromaEnumerable()>,
    "absent": List()<AromaEnumerablee()>,
  },
  "description": {
    "present": List()<String>,
    "absent": List()<String>,
  },
  "ingredients": {
    "alphas": RangeOrNumberOrNull(),
    "betas": RangeOrNumberOrNull(),
    "cohumulones": RangeOrNumberOrNull(),
    "polyphenols": RangeOrNumberOrNull(),
    "xanthohumol": RangeOrNumberOrNull(),
    "oils": RangeOrNumberOrNull(),
    "farnesenes": RangeOrNumberOrNull(),
    "linalool": RangeOrNumberOrNull()
  },
  "feeling": {
    "bitterness": StringEnum()<low, medium, high>,
    "aromaticity": StringEnum()<low, medium, high>,
  }
}
```

The engine returns a ranked list of hop varieties sorted by similarity score relative to the query. In addition to similarity scores, the response includes metadata describing which computational modules were used in the evaluation. An explainability component presents the rationale behind each score to improve transparency and interpretability.

All submitted queries and their corresponding results are stored in the user’s history for later review and comparison.

#### Laboratory Module

The Laboratory module is accessible only to authenticated users with the team member flag enabled. In addition to standard query submission, this module allows users to adjust weighting parameters for individual comparison modules and biochemical attributes.

The module supports the creation of “agendas,” defined as persistent experimental sessions that group a single query with a sequence of results generated under different parameter configurations. This enables systematic exploration, tuning, and comparison of model behavior over time.

#### Administration Panel

The administration panel provides full CRUD functionality for managing users and hop varieties. Administrators have access to all submitted queries and their results. The module is designed for future extension with analytical features, including query usage statistics and system-level monitoring.

### Technology Stack and Integration

The web application is implemented using the Laravel framework and deployed in a Dockerized environment. SQLite may be used as the initial persistence layer. Frontend should be implemented using a lightweight mechanism; Blade templates with Alpine.js should be considered. Given the potentially high volume of concurrent comparison requests, a background job and queue mechanism should be considered.

Behavior-driven tests are implemented using Behat. The comparison engine is developed in Python and exposed via a well-defined interface, requiring a reliable inter-process or service-based communication mechanism for submitting queries and retrieving results.

---

## Run the project

Make sure you have **Docker** and **Docker Compose** installed.

### 1. Initialize the environment file

```bash
cp .env.example .env
```

### 2. Start the project

If you have [Taskfile](https://taskfile.dev/) installed, run:

```bash
task init
```

Otherwise, run the commands manually:

```bash
docker compose up --build -d
docker compose exec app composer install
docker compose exec app npm install
docker compose exec app php artisan migrate
```

To start development server, run:

```bash
task vite
```

or
```bash
docker compose exec app npm run dev
```

### 3. Access the application



The application should now be available at:

```text
https://hops-web.blumilk.local.env/
```

---

## Stop the project
To stop the containers, run:
```bash
docker compose down
```
