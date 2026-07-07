# Ticketing System

A modern Ticketing System built with **Laravel 12**, **PHP 8.4**, **MariaDB**, and **Docker**.

The system is designed for internal support and vendor-client collaboration with a scalable architecture based on **Service Layer**, **Repository Pattern**, and **REST API**.

---

# Features

## Ticket Management

- Create Ticket
- Review Ticket
- Assignment
- Progress Tracking
- Resolve & Close
- Timeline

## Ticket Communication

- Markdown Comment
- Attachment Support
- Image Preview
- File Download

## Master Data

- Application
- Application Feature
- Ticket Category
- Ticket Priority
- Ticket Status
- Ticket Waiting For
- SLA Rule

## User Management

- User
- Role
- Permission

---

# Technology Stack

| Layer | Technology |
|--------|------------|
| Backend | Laravel 12 |
| Language | PHP 8.4 |
| Database | MariaDB |
| Storage | MinIO / S3 Compatible |
| Container | Docker |
| API | REST API |
| Authentication | Laravel Sanctum |
| Authorization | Spatie Permission |

---

# Architecture

```
Client (Vue)

        │

 REST API

        │

Controller

        │

Service

        │

Repository

        │

Model

        │

MariaDB
```

---

# Getting Started

## Clone Repository

```bash
git clone <repository-url>

cd ticketing-system
```

---

## Environment

```bash
cp .env.example .env
```

---

## Start Docker

```bash
docker compose up -d
```

---

## Install Dependency

```bash
composer install
```

---

## Generate Application Key

```bash
php artisan key:generate
```

---

## Run Migration

```bash
php artisan migrate --seed
```

---

## Storage Link

```bash
php artisan storage:link
```

---

## Start Development Server

```bash
php artisan serve
```

---

# Docker

Start

```bash
docker compose up -d
```

Stop

```bash
docker compose down
```

Rebuild

```bash
docker compose build --no-cache
```

---

# Project Structure

```
app/
bootstrap/
config/
database/
docker/
docs/
public/
resources/
routes/
storage/
tests/
```

---

# Documentation

Complete project documentation is available under the **docs/** directory.

```
docs/

├── 00-project/
├── 01-api/
├── 02-backend/
├── 03-frontend/
├── 04-database/
├── 05-deployment/
└── assets/
```

---

# API Documentation

```
docs/01-api/
```

- Common Convention
- Authentication
- Master Data
- Ticket
- Ticket Comment
- Attachment

---

# Development Workflow

```
Feature

↓

Development

↓

Pull Request

↓

Review

↓

Merge

↓

Deploy
```

---

# Coding Standard

- PSR-12
- Laravel Best Practice
- Repository Pattern
- Service Layer
- Form Request Validation
- API Resource
- Database Transaction
- RESTful API

---

# Testing

Run Feature Test

```bash
php artisan test
```

Run Specific Test

```bash
php artisan test --filter TicketTest
```

---

# License

Internal Project.