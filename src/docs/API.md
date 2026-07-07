# Common Convention

---

# 1. Base URL

Development

```
http://localhost/api
```

Staging

```
https://staging.example.com/api
```

Production

```
https://example.com/api
```

---

# 2. Content Type

Semua request dan response menggunakan JSON.

Request Header

```http
Content-Type: application/json
Accept: application/json
```

Kecuali endpoint upload attachment menggunakan

```http
multipart/form-data
```

---

# 3. Authentication

Authentication menggunakan Laravel Sanctum.

Semua endpoint (kecuali Login) membutuhkan Bearer Token.

Header

```http
Authorization: Bearer {access_token}
```

Contoh

```http
Authorization: Bearer 1|eyJ0eXAiOiJKV1QiLCJh...
```

---

# 4. Standard Response

## Success Response

```json
{
    "data": {}
}
```

---

## Collection Response

```json
{
    "data": []
}
```

---

## Pagination Response

```json
{
    "data": [
        ...
    ],

    "links": {

        "first": "...",

        "last": "...",

        "prev": null,

        "next": "..."

    },

    "meta": {

        "current_page": 1,

        "from": 1,

        "last_page": 5,

        "path": "...",

        "per_page": 10,

        "to": 10,

        "total": 42

    }
}
```

---

# 5. HTTP Status Code

| Code | Description |
|-------|-------------|
| 200 | Success |
| 201 | Created |
| 204 | No Content |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 409 | Conflict |
| 422 | Validation Error |
| 500 | Internal Server Error |

---

# 6. Validation Error Response

Semua validation menggunakan Laravel FormRequest.

Response

```json
{
    "message": "The given data was invalid.",

    "errors": {

        "subject": [

            "The subject field is required."

        ],

        "ticket_priority_id": [

            "The selected ticket priority is invalid."

        ]

    }
}
```

Status

```
422 Unprocessable Entity
```

---

# 7. Business Rule Error

Business Rule menggunakan

```php
abort(422, '...');
```

Response

```json
{
    "message": "Only draft ticket can be submitted."
}
```

Status

```
422 Unprocessable Entity
```

Contoh

- Ticket sudah Submitted
- Ticket sudah Assigned
- Attachment expired
- User tidak dapat menerima assignment

---

# 8. Unauthorized

Belum login.

```json
{
    "message": "Unauthenticated."
}
```

Status

```
401 Unauthorized
```

---

# 9. Forbidden

Tidak memiliki permission.

```json
{
    "message": "This action is unauthorized."
}
```

Status

```
403 Forbidden
```

---

# 10. Not Found

```json
{
    "message": "No query results for model ..."
}
```

Status

```
404 Not Found
```

---

# 11. Pagination

Seluruh endpoint list menggunakan pagination.

Default

```
per_page = 10
```

Parameter

| Parameter | Type | Required | Default |
|------------|------|----------|----------|
| page | integer | No | 1 |
| per_page | integer | No | 10 |

Contoh

```
GET /api/tickets?page=2&per_page=20
```

---

# 12. Searching

Keyword search menggunakan parameter

```
search
```

Contoh

```
GET /api/tickets?search=login
```

Pencarian dilakukan sesuai implementasi masing-masing module.

Contoh Ticket

- ticket_number
- subject
- description
- contact_name
- contact_phone

---

# 13. Filtering

Semua filter menggunakan Query String.

Contoh

```
GET /api/tickets?ticket_status_id=2
```

Multiple Filter

```
GET /api/tickets?ticket_status_id=2&ticket_priority_id=1
```

Date Range

```
GET /api/tickets?submitted_from=2026-07-01&submitted_to=2026-07-31
```

---

# 14. Sorting

Parameter

```
sort
direction
```

Contoh

```
GET /api/tickets?sort=created_at&direction=desc
```

Default

```
created_at desc
```

---

# 15. Boolean Parameter

Boolean dikirim sebagai

```
true
false
```

atau

```
1
0
```

Sesuai validasi endpoint.

Contoh

```
GET /api/users?is_active=true
```

---

# 16. Date Format

Semua tanggal menggunakan ISO-8601.

Contoh

```
2026-07-07T14:35:10Z
```

Filter Date

```
YYYY-MM-DD
```

Contoh

```
submitted_from=2026-07-01
```

---

# 17. File Upload

Attachment menggunakan

```
multipart/form-data
```

Field

```
file
```

Response

```json
{
    "data": {

        "id": 1,

        "ulid": "...",

        "markdown": "![image](attachment://01J...)"

    }
}
```

---

# 18. Markdown Attachment

Semua attachment yang disisipkan ke Markdown menggunakan format

```
attachment://{ULID}
```

Contoh

```
![Screenshot](attachment://01JZP8X3D6VZJ8YB8M5J9P2G1T)
```

atau

```
[Log Error](attachment://01JZP8X3D6VZJ8YB8M5J9P2G1T)
```

---

# 19. Standard Query Parameter

| Parameter | Description |
|-----------|-------------|
| page | Pagination |
| per_page | Pagination |
| search | Keyword Search |
| sort | Sort Column |
| direction | asc / desc |

Module dapat menambahkan parameter filter sesuai kebutuhan.

---

# 20. Naming Convention

REST Endpoint menggunakan kebab-case.

Contoh

```
ticket-categories
application-features
ticket-priorities
ticket-statuses
ticket-waiting-fors
```

Primary Key

```
{id}
```

Nested Resource

```
tickets/{ticket}/comments
```

Action Endpoint

```
tickets/{ticket}/submit
tickets/{ticket}/review
tickets/{ticket}/resolve
```

---

# 21. Common Response Flow

```
Client

↓

Request

↓

Middleware

↓

Authentication

↓

Permission

↓

FormRequest Validation

↓

Controller

↓

Service

↓

Resource

↓

JSON Response
```
# Authentication

Authentication menggunakan **Laravel Sanctum Token Authentication**.

Seluruh endpoint API **wajib menggunakan Bearer Token**, kecuali endpoint Login.

---

# Authentication Flow

```text
+--------+
| Login  |
+--------+
     │
     ▼
POST /api/login
     │
     ▼
Generate Personal Access Token
     │
     ▼
Client Menyimpan Token
     │
     ▼
Authorization: Bearer {token}
     │
     ▼
Semua Request API
```

---

# Authentication Header

Seluruh request setelah login wajib mengirimkan header berikut.

| Header | Value |
|----------|--------|
| Accept | application/json |
| Authorization | Bearer {access_token} |

Contoh

```http
GET /api/me HTTP/1.1
Host: localhost

Accept: application/json

Authorization: Bearer 1|eyJ0eXAiOiJKV1QiLCJh...
```

---

# Login

## Endpoint

```http
POST /api/login
```

Authentication:

```
No Authentication Required
```

Permission:

```
Public
```

---

## Request Body

| Field | Type | Required | Description |
|---------|------|----------|-------------|
| email | string | Yes | User Email |
| password | string | Yes | User Password |

---

### Example Request

```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

---

## Success Response

HTTP Status

```
200 OK
```

Example

```json
{
    "token": "1|xxxxxxxxxxxxxxxx",

    "user": {

        "id": 1,

        "name": "Administrator",

        "email": "admin@example.com"

    }
}
```

---

## Validation Error

HTTP Status

```
422 Unprocessable Entity
```

Example

```json
{
    "message": "The given data was invalid.",

    "errors": {

        "email": [

            "The email field is required."

        ]

    }
}
```

---

## Invalid Credential

HTTP Status

```
401 Unauthorized
```

Example

```json
{
    "message": "Invalid credentials."
}
```

---

# Logout

## Endpoint

```http
POST /api/logout
```

Authentication

```
Bearer Token
```

Permission

```
Authenticated User
```

---

## Request Body

None

---

### Example Request

```http
POST /api/logout

Authorization: Bearer {token}
```

---

## Success Response

HTTP Status

```
200 OK
```

Example

```json
{
    "message": "Logout successful."
}
```

---

# Get Current User

## Endpoint

```http
GET /api/me
```

Authentication

```
Bearer Token
```

Permission

```
Authenticated User
```

---

## Request Body

None

---

### Example Request

```http
GET /api/me

Authorization: Bearer {token}
```

---

## Success Response

HTTP Status

```
200 OK
```

Example

```json
{
    "data": {

        "id": 1,

        "name": "Administrator",

        "email": "admin@example.com",

        "roles": [

            {
                "id": 1,
                "name": "Super Admin"
            }

        ],

        "permissions": [

            "ticket.view",

            "ticket.create",

            "ticket.update"

        ]

    }
}
```

---

# Authentication Error

## Missing Token

HTTP Status

```
401 Unauthorized
```

Example

```json
{
    "message": "Unauthenticated."
}
```

---

## Invalid Token

HTTP Status

```
401 Unauthorized
```

Example

```json
{
    "message": "Unauthenticated."
}
```

---

## Expired / Revoked Token

HTTP Status

```
401 Unauthorized
```

Example

```json
{
    "message": "Unauthenticated."
}
```

---

# Authentication Summary

| Endpoint | Method | Authentication |
|------------|--------|----------------|
| /api/login | POST | Public |
| /api/logout | POST | Bearer Token |
| /api/me | GET | Bearer Token |

---

# Frontend Authentication Flow

```text
Login Page

        │

        ▼

POST /api/login

        │

        ▼

Receive Token

        │

        ▼

Save Token (Local Storage / Cookie)

        │

        ▼

Set Authorization Header

        │

        ▼

Call GET /api/me

        │

        ▼

Store User Profile

        │

        ▼

Access Application
```

---

# Frontend Logout Flow

```text
Click Logout

      │

      ▼

POST /api/logout

      │

      ▼

Delete Token

      │

      ▼

Redirect Login Page
```

# User

Module User digunakan untuk mengelola data pengguna sistem.

---

# Endpoint Summary

| Method | Endpoint | Description |
|---------|----------|-------------|
| GET | /api/users | Get User List |
| POST | /api/users | Create User |
| GET | /api/users/{user} | Get User Detail |
| PUT | /api/users/{user} | Update User |

---

# User Model

| Field | Type |
|---------|------|
| id | integer |
| name | string |
| email | string |
| is_active | boolean |
| roles | array |
| created_at | datetime |
| updated_at | datetime |

---

# GET /api/users

Get user list.

## Endpoint

```http
GET /api/users
```

## Authentication

Bearer Token

## Permission

```
user.view
```

---

## Query Parameters

### Pagination

| Parameter | Type | Default |
|------------|------|----------|
| page | integer | 1 |
| per_page | integer | 10 |

---

### Search

| Parameter | Type | Description |
|------------|------|-------------|
| search | string | Search by name or email |

Example

```
GET /api/users?search=admin
```

---

### Filter

| Parameter | Type | Description |
|------------|------|-------------|
| role_id | integer | Filter by role |
| is_active | boolean | Active / Inactive User |

Example

```
GET /api/users?role_id=2
```

```
GET /api/users?is_active=true
```

---

### Sorting

| Parameter | Example |
|------------|----------|
| sort | name |
| direction | asc |

Example

```
GET /api/users?sort=name&direction=asc
```

---

## Example Request

```
GET /api/users?page=1&per_page=10&search=admin
```

---

## Success Response

```json
{
    "data": [

        {

            "id": 1,

            "name": "Administrator",

            "email": "admin@example.com",

            "is_active": true,

            "roles": [

                {

                    "id": 1,

                    "name": "Super Admin"

                }

            ]

        }

    ],

    "links": {},

    "meta": {}

}
```

---

# POST /api/users

Create User.

## Endpoint

```http
POST /api/users
```

## Authentication

Bearer Token

## Permission

```
user.create
```

---

## Request Body

| Field | Type | Required | Description |
|---------|------|----------|-------------|
| name | string | Yes | User Name |
| email | string | Yes | Email |
| password | string | Yes | Password |
| password_confirmation | string | Yes | Password Confirmation |
| role_ids | array | Yes | User Roles |
| is_active | boolean | Yes | Active Status |

---

### Example Request

```json
{
    "name": "John Doe",

    "email": "john@example.com",

    "password": "password",

    "password_confirmation": "password",

    "role_ids": [
        2
    ],

    "is_active": true
}
```

---

## Success Response

HTTP Status

```
201 Created
```

```json
{
    "data": {

        "id": 5,

        "name": "John Doe",

        "email": "john@example.com"

    }
}
```

---

## Validation

- Email must be unique.
- Password confirmation must match.
- Role must exist.
- Name is required.

---

# GET /api/users/{user}

Get User Detail.

## Endpoint

```http
GET /api/users/{user}
```

## Authentication

Bearer Token

## Permission

```
user.view
```

---

## Path Parameter

| Parameter | Description |
|------------|-------------|
| user | User ID |

---

## Success Response

```json
{
    "data": {

        "id": 5,

        "name": "John Doe",

        "email": "john@example.com",

        "is_active": true,

        "roles": [

            {

                "id": 2,

                "name": "Developer"

            }

        ]

    }
}
```

---

# PUT /api/users/{user}

Update User.

## Endpoint

```http
PUT /api/users/{user}
```

## Authentication

Bearer Token

## Permission

```
user.update
```

---

## Path Parameter

| Parameter | Description |
|------------|-------------|
| user | User ID |

---

## Request Body

| Field | Type | Required |
|---------|------|----------|
| name | string | Yes |
| email | string | Yes |
| password | string | No |
| password_confirmation | string | Required if password filled |
| role_ids | array | Yes |
| is_active | boolean | Yes |

---

### Example Request

```json
{
    "name": "John Doe",

    "email": "john@example.com",

    "password": "",

    "password_confirmation": "",

    "role_ids": [
        2,
        3
    ],

    "is_active": true
}
```

---

## Success Response

HTTP Status

```
200 OK
```

```json
{
    "data": {

        "id": 5,

        "name": "John Doe",

        "email": "john@example.com"

    }
}
```

---

# Business Rules

## Create

- Email harus unik.
- Minimal memiliki satu Role.
- Password wajib diisi.
- Password akan di-hash sebelum disimpan.

---

## Update

- Password bersifat opsional.
- Jika password kosong, password lama tetap digunakan.
- Email harus tetap unik.
- Role akan disinkronkan menggunakan syncRoles().
- User yang sedang login tidak boleh menonaktifkan akun sendiri (jika business rule diterapkan).

---

# Error Response

| HTTP Code | Description |
|------------|-------------|
| 401 | Unauthenticated |
| 403 | Forbidden |
| 404 | User Not Found |
| 422 | Validation Error |

---

# Frontend Notes

## User List

Gunakan endpoint

```
GET /api/users
```

untuk:

- User Dropdown
- Assignment User
- User Management

---

## User Detail

Gunakan

```
GET /api/users/{user}
```

untuk halaman Edit User.

---

## Create / Update

Frontend cukup mengirimkan `role_ids` sebagai array.

Backend akan melakukan sinkronisasi Role secara otomatis.

# 3. Master Data

Master Data merupakan kumpulan data referensi yang digunakan oleh seluruh proses bisnis Ticketing System.

Seluruh Master Data memiliki karakteristik yang sama:

- RESTful API
- CRUD
- Pagination
- Search
- Sorting
- Filtering
- Form Request Validation
- API Resource
- Service Layer
- Permission Based Access

---

# 3.1 Supported Modules

| Module | Endpoint | Digunakan Oleh |
|---------|----------|----------------|
| Application | `/api/applications` | Ticket |
| Application Feature | `/api/application-features` | Ticket |
| Ticket Category | `/api/ticket-categories` | Ticket |
| Ticket Priority | `/api/ticket-priorities` | Ticket, SLA |
| Ticket Status | `/api/ticket-statuses` | Ticket Workflow |
| Ticket Waiting For | `/api/ticket-waiting-fors` | Ticket Progress |
| SLA Rule | `/api/sla-rules` | Ticket |

---

# 3.2 Common CRUD Convention

Seluruh Master Data menggunakan endpoint REST yang sama.

| Method | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/{resource}` | Get List |
| POST | `/api/{resource}` | Create |
| GET | `/api/{resource}/{id}` | Detail |
| PUT | `/api/{resource}/{id}` | Update |
| DELETE | `/api/{resource}/{id}` | Delete |

---

# 3.3 Common Query Parameters

## Pagination

| Parameter | Type | Default |
|-----------|------|---------|
| page | integer | 1 |
| per_page | integer | 10 |

Example

```http
GET /api/applications?page=1&per_page=20
```

---

## Search

```http
GET /api/applications?search=erp
```

---

## Sorting

```http
GET /api/applications?sort=name&direction=asc
```

---

## Filter

```http
GET /api/applications?is_active=true
```

Module tertentu dapat menambahkan filter tambahan sesuai kebutuhan.

---

# 3.4 Standard Response

## Single Resource

```json
{
    "data": {}
}
```

---

## Collection

```json
{
    "data": [],
    "links": {},
    "meta": {}
}
```

---

# 3.5 Common Business Rules

- Semua operasi menggunakan Form Request Validation.
- Semua Create dan Update menggunakan Database Transaction.
- Field `code` harus unik (jika tersedia).
- Data yang masih digunakan oleh module lain tidak dapat dihapus.
- Data `is_active = false` tidak dapat dipilih pada transaksi baru namun tetap ditampilkan pada data historis.

---

# 3.6 Common Error Response

| HTTP | Description |
|------|-------------|
| 401 | Unauthenticated |
| 403 | Forbidden |
| 404 | Resource Not Found |
| 409 | Conflict |
| 422 | Validation Error |

---

# 3.7 Module Detail

---

# 3.7.1 Application

## Endpoint

| Method | Endpoint |
|---------|----------|
| GET | `/api/applications` |
| POST | `/api/applications` |
| GET | `/api/applications/{application}` |
| PUT | `/api/applications/{application}` |
| DELETE | `/api/applications/{application}` |

### Filter

| Parameter | Description |
|-----------|-------------|
| search | Search by Name / Code |
| is_active | Active Status |

### Request Body

```json
{
    "name": "ERP",
    "code": "ERP",
    "description": "Enterprise Resource Planning",
    "is_active": true,
    "sort_order": 1
}
```

### Business Rules

- Code harus unik.
- Tidak dapat dihapus apabila masih memiliki Application Feature.

### Implementation Reference

| Item | Value |
|------|-------|
| Controller | `ApplicationController` |
| Service | `ApplicationService` |
| Request | `StoreApplicationRequest`, `UpdateApplicationRequest` |
| Resource | `ApplicationResource` |
| Filter | `ApplicationFilter` |

---

# 3.7.2 Application Feature

## Endpoint

| Method | Endpoint |
|---------|----------|
| GET | `/api/application-features` |
| POST | `/api/application-features` |
| GET | `/api/application-features/{application_feature}` |
| PUT | `/api/application-features/{application_feature}` |
| DELETE | `/api/application-features/{application_feature}` |

### Filter

| Parameter | Description |
|-----------|-------------|
| application_id | Filter by Application |
| search | Search by Name / Code |
| is_active | Active Status |

### Request Body

```json
{
    "application_id": 1,
    "name": "Authentication",
    "code": "AUTH",
    "description": "Authentication Module",
    "is_active": true,
    "sort_order": 1
}
```

### Business Rules

- Application wajib tersedia.
- Code unik dalam satu Application.
- Tidak dapat dihapus apabila digunakan Ticket.

### Implementation Reference

| Item | Value |
|------|-------|
| Controller | `ApplicationFeatureController` |
| Service | `ApplicationFeatureService` |
| Request | `StoreApplicationFeatureRequest`, `UpdateApplicationFeatureRequest` |
| Resource | `ApplicationFeatureResource` |
| Filter | `ApplicationFeatureFilter` |

---

# 3.7.3 Ticket Category

## Endpoint

| Method | Endpoint |
|---------|----------|
| GET | `/api/ticket-categories` |
| POST | `/api/ticket-categories` |
| GET | `/api/ticket-categories/{ticket_category}` |
| PUT | `/api/ticket-categories/{ticket_category}` |
| DELETE | `/api/ticket-categories/{ticket_category}` |

### Filter

- search
- is_active

### Request Body

```json
{
    "name": "Bug",
    "code": "BUG",
    "description": "Application Bug",
    "is_active": true,
    "sort_order": 1
}
```

### Business Rules

- Code unik.
- Tidak dapat dihapus apabila digunakan Ticket.

### Implementation Reference

| Item | Value |
|------|-------|
| Controller | `TicketCategoryController` |
| Service | `TicketCategoryService` |
| Request | `StoreTicketCategoryRequest`, `UpdateTicketCategoryRequest` |
| Resource | `TicketCategoryResource` |
| Filter | `TicketCategoryFilter` |

---

# 3.7.4 Ticket Priority

## Endpoint

| Method | Endpoint |
|---------|----------|
| GET | `/api/ticket-priorities` |
| POST | `/api/ticket-priorities` |
| GET | `/api/ticket-priorities/{ticket_priority}` |
| PUT | `/api/ticket-priorities/{ticket_priority}` |
| DELETE | `/api/ticket-priorities/{ticket_priority}` |

### Filter

- search
- is_active

### Request Body

```json
{
    "name": "High",
    "code": "HIGH",
    "description": "High Priority",
    "response_hours": 2,
    "resolution_hours": 24,
    "color": "#EF4444",
    "is_active": true,
    "sort_order": 1
}
```

### Business Rules

- Code unik.
- Resolution Hour harus lebih besar atau sama dengan Response Hour.

### Implementation Reference

| Item | Value |
|------|-------|
| Controller | `TicketPriorityController` |
| Service | `TicketPriorityService` |
| Request | `StoreTicketPriorityRequest`, `UpdateTicketPriorityRequest` |
| Resource | `TicketPriorityResource` |
| Filter | `TicketPriorityFilter` |

---

# 3.7.5 Ticket Status

## Endpoint

| Method | Endpoint |
|---------|----------|
| GET | `/api/ticket-statuses` |
| POST | `/api/ticket-statuses` |
| GET | `/api/ticket-statuses/{ticket_status}` |
| PUT | `/api/ticket-statuses/{ticket_status}` |
| DELETE | `/api/ticket-statuses/{ticket_status}` |

### Filter

- search
- is_active

### Request Body

```json
{
    "name": "Draft",
    "code": "DRAFT",
    "description": "Draft Ticket",
    "color": "#6B7280",
    "icon": "edit",
    "is_initial": true,
    "is_closed": false,
    "is_resolved": false,
    "is_active": true,
    "sort_order": 1
}
```

### Business Rules

- Hanya boleh ada satu Initial Status.
- Status yang digunakan Ticket tidak dapat dihapus.

### Implementation Reference

| Item | Value |
|------|-------|
| Controller | `TicketStatusController` |
| Service | `TicketStatusService` |
| Request | `StoreTicketStatusRequest`, `UpdateTicketStatusRequest` |
| Resource | `TicketStatusResource` |
| Filter | `TicketStatusFilter` |

---

# 3.7.6 Ticket Waiting For

## Endpoint

| Method | Endpoint |
|---------|----------|
| GET | `/api/ticket-waiting-fors` |
| POST | `/api/ticket-waiting-fors` |
| GET | `/api/ticket-waiting-fors/{ticket_waiting_for}` |
| PUT | `/api/ticket-waiting-fors/{ticket_waiting_for}` |
| DELETE | `/api/ticket-waiting-fors/{ticket_waiting_for}` |

### Filter

- search
- is_active

### Request Body

```json
{
    "name": "Customer",
    "code": "CUSTOMER",
    "description": "Waiting Customer Response",
    "is_active": true,
    "sort_order": 1
}
```

### Business Rules

- Code unik.
- Tidak dapat dihapus apabila digunakan Ticket Progress.

### Implementation Reference

| Item | Value |
|------|-------|
| Controller | `TicketWaitingForController` |
| Service | `TicketWaitingForService` |
| Request | `StoreTicketWaitingForRequest`, `UpdateTicketWaitingForRequest` |
| Resource | `TicketWaitingForResource` |
| Filter | `TicketWaitingForFilter` |

---

# 3.7.7 SLA Rule

## Endpoint

| Method | Endpoint |
|---------|----------|
| GET | `/api/sla-rules` |
| POST | `/api/sla-rules` |
| GET | `/api/sla-rules/{sla_rule}` |
| PUT | `/api/sla-rules/{sla_rule}` |
| DELETE | `/api/sla-rules/{sla_rule}` |

### Filter

| Parameter | Description |
|-----------|-------------|
| ticket_category_id | Filter by Category |
| ticket_priority_id | Filter by Priority |
| is_active | Active Status |

### Request Body

```json
{
    "ticket_category_id": 1,
    "ticket_priority_id": 2,
    "response_hours": 4,
    "resolution_hours": 24,
    "is_active": true
}
```

### Business Rules

- Kombinasi Category dan Priority harus unik.
- Resolution Hour harus lebih besar atau sama dengan Response Hour.
- Category dan Priority harus aktif.

### Implementation Reference

| Item | Value |
|------|-------|
| Controller | `SlaRuleController` |
| Service | `SlaRuleService` |
| Request | `StoreSlaRuleRequest`, `UpdateSlaRuleRequest` |
| Resource | `SlaRuleResource` |
| Filter | `SlaRuleFilter` |

# 5. Ticket

Ticket merupakan modul utama pada Ticketing System yang digunakan untuk mencatat, mengelola, dan memonitor seluruh proses penanganan issue mulai dari pembuatan hingga selesai.

---

# 5.1 Workflow

```text
Draft
    │
    ▼
Submitted
    │
    ▼
Reviewed
    │
    ▼
Assigned
    │
    ▼
In Progress
    │
    ├──────────────┐
    ▼              │
Pending            │
    │              │
    ▼              │
Resume ────────────┘
    │
    ▼
Resolved
    │
    ▼
Accepted
    │
    ▼
Closed
```

---

# 5.2 Endpoint Summary

| Method | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/tickets` | Get Ticket List |
| POST | `/api/tickets` | Create Ticket |
| GET | `/api/tickets/{ticket}` | Ticket Detail |
| PUT | `/api/tickets/{ticket}` | Update Ticket |
| DELETE | `/api/tickets/{ticket}` | Delete Ticket |
| PUT | `/api/tickets/{ticket}/submit` | Submit Ticket |
| PUT | `/api/tickets/{ticket}/review` | Review Ticket |
| POST | `/api/tickets/{ticket}/assignments` | Assign Ticket |
| PUT | `/api/tickets/{ticket}/start-progress` | Start Progress |
| PUT | `/api/tickets/{ticket}/pending` | Pending |
| PUT | `/api/tickets/{ticket}/resume` | Resume |
| PUT | `/api/tickets/{ticket}/resolve` | Resolve |
| PUT | `/api/tickets/{ticket}/accept` | Accept / Reject Resolution |
| GET | `/api/tickets/{ticket}/timeline` | Timeline |

---

# 5.3 Ticket Model

| Field | Type | Required |
|---------|------|----------|
| application_feature_id | integer | ✓ |
| ticket_category_id | integer | ✓ |
| ticket_priority_id | integer | ✓ |
| subject | string | ✓ |
| description | markdown | ✓ |
| contact_name | string | ✓ |
| contact_phone | string | |
| requester_id | integer | Auto |
| ticket_status_id | integer | Auto |

---

# 5.4 GET /api/tickets

Get ticket list.

## Permission

```
ticket.view
```

---

## Pagination

| Parameter | Default |
|-----------|---------|
| page | 1 |
| per_page | 10 |

---

## Search

```http
GET /api/tickets?search=login
```

Search dilakukan pada:

- ticket_number
- subject
- description
- contact_name
- contact_phone

---

## Filter

| Parameter |
|-----------|
| application_feature_id |
| ticket_category_id |
| ticket_priority_id |
| ticket_status_id |
| requester_id |
| assigned_to |
| created_from |
| created_to |

Contoh

```http
GET /api/tickets?ticket_status_id=2
```

```http
GET /api/tickets?ticket_priority_id=1
```

```http
GET /api/tickets?application_feature_id=5
```

---

## Sorting

```http
GET /api/tickets?sort=created_at&direction=desc
```

Default

```
created_at desc
```

---

## Response

```json
{
    "data": [],
    "links": {},
    "meta": {}
}
```

---

# 5.5 POST /api/tickets

Create Ticket.

## Permission

```
ticket.create
```

---

## Request Body

```json
{
    "application_feature_id": 1,
    "ticket_category_id": 2,
    "ticket_priority_id": 1,
    "contact_name": "John Doe",
    "contact_phone": "08123456789",
    "subject": "Cannot Login",
    "description": "Application error..."
}
```

---

## Business Rules

- Ticket Number dibuat otomatis.
- Status awal selalu **Draft**.
- Requester diambil dari user login.
- SLA belum dihitung.
- Attachment pada Description akan disinkronkan otomatis.

---

# 5.6 PUT /api/tickets/{ticket}

Update Draft Ticket.

## Permission

```
ticket.update
```

---

## Business Rules

Hanya Ticket dengan status:

- Draft
- Rejected

yang dapat diperbarui.

---

# 5.7 DELETE /api/tickets/{ticket}

Delete Ticket.

## Permission

```
ticket.delete
```

---

## Business Rules

Hanya Ticket Draft yang dapat dihapus.

---

# 5.8 PUT /api/tickets/{ticket}/submit

Submit Ticket.

## Permission

```
ticket.submit
```

Business Rules

- Status harus Draft.
- Menghitung SLA.
- Mengisi submitted_at.
- Mengisi response_due_at.
- Mengisi resolution_due_at.

---

# 5.9 PUT /api/tickets/{ticket}/review

Review Ticket.

## Permission

```
ticket.review
```

Request

```json
{
    "result":"reviewed",
    "review_notes":"..."
}
```

Business Rules

- Status harus Submitted.
- Reviewer otomatis dari user login.

---

# 5.10 POST /api/tickets/{ticket}/assignments

Assign Ticket.

## Permission

```
ticket.assign
```

Request

```json
{
    "assigned_to":5,
    "assignment_notes":"Assign to Backend Developer"
}
```

Business Rules

- User harus memiliki role yang dapat menerima assignment.
- Assignment sebelumnya otomatis nonaktif.

---

# 5.11 PUT /api/tickets/{ticket}/start-progress

Request

```json
{
    "progress_notes":"Development started."
}
```

Business Rules

- Status harus Assigned.
- Membuat Ticket Progress.

---

# 5.12 PUT /api/tickets/{ticket}/pending

Request

```json
{
    "ticket_waiting_for_id":2,
    "progress_notes":"Waiting customer confirmation."
}
```

---

# 5.13 PUT /api/tickets/{ticket}/resume

Request

```json
{
    "progress_notes":"Customer has replied."
}
```

---

# 5.14 PUT /api/tickets/{ticket}/resolve

Request

```json
{
    "resolution_notes":"Bug fixed."
}
```

---

# 5.15 PUT /api/tickets/{ticket}/accept

Request

```json
{
    "result":"closed",
    "close_notes":"Issue resolved."
}
```

---

# 5.16 GET /api/tickets/{ticket}/timeline

Menampilkan seluruh aktivitas Ticket.

Timeline terdiri dari:

- Created
- Submitted
- Reviewed
- Assigned
- Reassigned
- Started
- Pending
- Resume
- Resolved
- Acceptance Rejected
- Closed

---

# 5.17 State Transition

| Current | Action | Next |
|-----------|--------|------|
| Draft | Submit | Submitted |
| Submitted | Review | Reviewed |
| Submitted | Reject | Rejected |
| Reviewed | Assign | Assigned |
| Assigned | Start Progress | In Progress |
| In Progress | Pending | Pending |
| Pending | Resume | In Progress |
| In Progress | Resolve | Resolved |
| Resolved | Accept | Closed |
| Resolved | Reject | Assigned |

---

# 5.18 Implementation Reference

| Item | Value |
|------|-------|
| Controller | `TicketController` |
| Service | `TicketService` |
| Request | `StoreTicketRequest`, `UpdateTicketRequest` |
| Resource | `TicketResource` |
| Filter | `TicketFilter` |

# 6. Ticket Comment

Ticket Comment digunakan sebagai media komunikasi antara Client dan Vendor selama proses penyelesaian Ticket.

Comment mendukung:

- Markdown
- Image Attachment
- File Attachment
- Timeline Discussion

Comment **tidak mengubah status Ticket**, melainkan hanya sebagai media komunikasi.

---

# 6.1 Workflow

```text
Create Comment
        │
        ▼
Store Comment
        │
        ▼
Sync Attachment
        │
        ▼
Return Comment Resource
```

---

# 6.2 Endpoint Summary

| Method | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/tickets/{ticket}/comments` | Get Comment List |
| POST | `/api/tickets/{ticket}/comments` | Create Comment |
| GET | `/api/tickets/{ticket}/comments/{comment}` | Get Comment Detail |
| PUT | `/api/tickets/{ticket}/comments/{comment}` | Update Comment |
| DELETE | `/api/tickets/{ticket}/comments/{comment}` | Delete Comment |

---

# 6.3 Comment Model

| Field | Type | Required |
|---------|------|----------|
| ticket_id | integer | Auto |
| content | markdown | ✓ |
| user_id | integer | Auto |
| created_at | datetime | Auto |
| updated_at | datetime | Auto |

---

# 6.4 GET /api/tickets/{ticket}/comments

Mengambil seluruh Comment milik Ticket.

## Permission

```
ticket.comment.view
```

---

## Path Parameter

| Parameter | Description |
|-----------|-------------|
| ticket | Ticket ID |

---

## Pagination

| Parameter | Default |
|-----------|---------|
| page | 1 |
| per_page | 10 |

---

## Sorting

Default

```
created_at asc
```

---

## Response

```json
{
    "data": [

        {

            "id": 1,

            "content": "Please check the latest deployment.",

            "attachments": [],

            "user": {},

            "created_at": "...",

            "updated_at": "..."

        }

    ],

    "links": {},

    "meta": {}

}
```

---

# 6.5 POST /api/tickets/{ticket}/comments

Create Comment.

## Permission

```
ticket.comment.create
```

---

## Path Parameter

| Parameter | Description |
|-----------|-------------|
| ticket | Ticket ID |

---

## Request Body

```json
{
    "content": "Deployment has been completed.\n\n![Screenshot](attachment://01JXXXXX)"
}
```

---

## Business Rules

- User diambil dari user login.
- Ticket harus tersedia.
- Attachment pada Markdown akan otomatis disinkronkan.
- Attachment berubah dari Temporary menjadi Permanent.
- Attachment yang tidak ditemukan akan menghasilkan Validation Error.

---

## Success Response

```json
{
    "data": {

        "id": 15,

        "content": "...",

        "attachments": [],

        "user": {}

    }
}
```

---

# 6.6 GET /api/tickets/{ticket}/comments/{comment}

Mengambil detail Comment.

## Permission

```
ticket.comment.view
```

---

## Path Parameter

| Parameter | Description |
|-----------|-------------|
| ticket | Ticket ID |
| comment | Comment ID |

---

## Response

```json
{
    "data": {

        "id": 15,

        "content": "...",

        "attachments": [],

        "user": {}

    }
}
```

---

# 6.7 PUT /api/tickets/{ticket}/comments/{comment}

Update Comment.

## Permission

```
ticket.comment.update
```

---

## Request Body

```json
{
    "content": "Updated comment.\n\n[Log File](attachment://01JYYYY)"
}
```

---

## Business Rules

- Hanya Author yang dapat mengubah Comment.
- Attachment Usage akan disinkronkan ulang.
- Attachment baru menjadi Permanent.
- Attachment yang sudah tidak digunakan akan dilepas dari Comment.

---

# 6.8 DELETE /api/tickets/{ticket}/comments/{comment}

Delete Comment.

## Permission

```
ticket.comment.delete
```

---

## Business Rules

- Hanya Author yang dapat menghapus Comment.
- Attachment Usage Comment ikut dihapus.
- File Attachment **tidak langsung dihapus** dari Storage.
- Cleanup Attachment mengikuti Attachment Engine.

---

# 6.9 Markdown Attachment

Comment mendukung Markdown.

Contoh

```md
## Investigation

Deployment selesai dilakukan.

![Screenshot](attachment://01JABCDE123456789)

Silakan dicoba kembali.
```

---

# 6.10 Attachment Flow

```text
Upload Attachment
        │
        ▼
Temporary Attachment
        │
        ▼
Create Comment
        │
        ▼
AttachmentUsageService::sync()
        │
        ▼
Permanent Attachment
```

---

# 6.11 Response Resource

Ticket Comment selalu mengembalikan:

```json
{
    "id": 15,

    "content": "...",

    "attachments": [],

    "user": {},

    "created_at": "...",

    "updated_at": "..."
}
```

---

# 6.12 Business Rules

- Comment tidak mengubah Status Ticket.
- Comment dapat memiliki banyak Attachment.
- Attachment hanya berasal dari Markdown.
- Attachment otomatis disinkronkan ketika Create maupun Update.
- Delete Comment hanya menghapus Attachment Usage.
- Attachment fisik tetap mengikuti lifecycle Attachment Engine.

---

# 6.13 Implementation Reference

| Item | Value |
|------|-------|
| Controller | `TicketCommentController` |
| Service | `TicketCommentService` |
| Request | `StoreTicketCommentRequest`, `UpdateTicketCommentRequest` |
| Resource | `TicketCommentResource` |
| Filter | - |

# 7. Attachment

Attachment digunakan untuk mengelola seluruh file yang diunggah pada Ticketing System.

Saat ini Attachment digunakan oleh:

- Ticket Description
- Ticket Comment

Attachment mendukung:

- Image
- Document
- Spreadsheet
- PDF
- Video (Opsional)
- File lainnya sesuai konfigurasi sistem

---

# 7.1 Workflow

```text
Upload File
      │
      ▼
Temporary Attachment
      │
      ▼
Insert Markdown
      │
      ▼
Create / Update Ticket
atau
Create / Update Comment
      │
      ▼
AttachmentUsage::sync()
      │
      ▼
Permanent Attachment
```

---

# 7.2 Attachment Lifecycle

```text
Upload

↓

Temporary

↓

Used

↓

Permanent
```

Attachment yang telah menjadi **Permanent** tidak dapat kembali menjadi Temporary.

---

# 7.3 Endpoint Summary

| Method | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/attachments` | Upload Attachment |
| GET | `/api/attachments/{attachment}/preview` | Preview Attachment |
| GET | `/api/attachments/{attachment}/download` | Download Attachment |
| DELETE | `/api/attachments/{attachment}` | Delete Temporary Attachment |

---

# 7.4 Attachment Model

| Field | Type |
|---------|------|
| id | integer |
| ulid | string |
| original_filename | string |
| filename | string |
| mime_type | string |
| extension | string |
| size | integer |
| disk | string |
| path | string |
| is_temporary | boolean |
| expired_at | datetime |
| created_by | integer |
| created_at | datetime |

---

# 7.5 POST /api/attachments

Upload Attachment.

## Permission

Authenticated User

---

## Content Type

```
multipart/form-data
```

---

## Request Body

| Field | Type | Required |
|---------|------|----------|
| file | file | ✓ |

---

## Example

```http
POST /api/attachments

Content-Type: multipart/form-data
```

---

## Success Response

```json
{
    "data": {

        "id": 1,

        "ulid": "01JZABCDEFG",

        "original_filename": "error.png",

        "mime_type": "image/png",

        "size": 325421,

        "is_temporary": true,

        "markdown": "![error.png](attachment://01JZABCDEFG)"

    }
}
```

---

## Business Rules

- File langsung diupload ke Object Storage.
- Status awal selalu Temporary.
- ULID dibuat otomatis.
- Markdown otomatis dibuat.
- Belum memiliki Attachment Usage.

---

# 7.6 GET /api/attachments/{attachment}/preview

Preview Attachment.

## Permission

Authenticated User

---

## Path Parameter

| Parameter | Description |
|------------|-------------|
| attachment | Attachment ULID |

---

## Response

Image / File Stream.

---

## Business Rules

- Digunakan untuk Preview Image.
- Tidak memaksa browser mengunduh file.

---

# 7.7 GET /api/attachments/{attachment}/download

Download Attachment.

## Permission

Authenticated User

---

## Path Parameter

| Parameter | Description |
|------------|-------------|
| attachment | Attachment ULID |

---

## Response

Binary File

---

## Business Rules

- Mengirim file asli.
- Browser akan mengunduh file.

---

# 7.8 DELETE /api/attachments/{attachment}

Delete Temporary Attachment.

## Permission

Authenticated User

---

## Path Parameter

| Parameter | Description |
|------------|-------------|
| attachment | Attachment ULID |

---

## Business Rules

Delete hanya diperbolehkan apabila:

- Attachment masih Temporary.
- Belum digunakan Ticket.
- Belum digunakan Comment.

Jika Attachment sudah Permanent maka request akan ditolak.

---

# 7.9 Markdown Format

Attachment selalu menggunakan format berikut.

Image

```markdown
![Screenshot](attachment://01JABCDEFG)
```

---

Document

```markdown
[Error Log](attachment://01JABCDEFG)
```

---

Multiple Attachment

```markdown
![Image 1](attachment://01AAAA)

![Image 2](attachment://01BBBB)

[Log](attachment://01CCCC)
```

---

# 7.10 Attachment Synchronization

Ticket maupun Comment tidak menyimpan Attachment secara langsung.

Saat Ticket atau Comment disimpan, sistem akan melakukan:

```text
Parse Markdown

↓

Extract ULID

↓

Find Attachment

↓

Validate Attachment

↓

Sync Attachment Usage

↓

Mark Permanent
```

---

# 7.11 Attachment Usage

Attachment digunakan melalui tabel Attachment Usage.

Relationship

```text
Ticket

↓

Attachment Usage

↓

Attachment
```

atau

```text
Ticket Comment

↓

Attachment Usage

↓

Attachment
```

Satu Attachment dapat digunakan oleh banyak entity apabila business rule mengizinkan.

---

# 7.12 Validation

Saat proses sinkronisasi dilakukan, sistem akan memvalidasi:

- Attachment tersedia.
- Attachment belum expired.
- Attachment belum dihapus.
- Attachment masih dapat digunakan.

Apabila salah satu validasi gagal maka Ticket atau Comment tidak akan disimpan.

---

# 7.13 Frontend Flow

```text
Select File

↓

POST /attachments

↓

Receive Markdown

↓

Insert Markdown ke Editor

↓

POST Ticket / Comment

↓

Done
```

Frontend **tidak perlu mengetahui Attachment ID**.

Frontend cukup menyimpan Markdown yang diberikan API.

---

# 7.14 Response Example

```json
{
    "data": {

        "id": 10,

        "ulid": "01JZABCDEFG",

        "original_filename": "error.png",

        "mime_type": "image/png",

        "size": 123456,

        "preview_url": "/api/attachments/01JZABCDEFG/preview",

        "download_url": "/api/attachments/01JZABCDEFG/download",

        "is_temporary": true

    }
}
```

---

# 7.15 Business Rules

- Attachment hanya digunakan melalui Markdown.
- Attachment tidak pernah dikirim melalui array id.
- Markdown merupakan satu-satunya sumber referensi Attachment.
- Attachment Usage dibuat otomatis ketika Ticket atau Comment disimpan.
- Attachment berubah menjadi Permanent setelah digunakan.
- Attachment Usage akan disinkronkan ulang ketika Ticket atau Comment diperbarui.
- Delete Ticket atau Comment hanya menghapus Attachment Usage, bukan file fisik.

---

# 7.16 Implementation Reference

| Item | Value |
|------|-------|
| Controller | `AttachmentController` |
| Service | `AttachmentService` |
| Service | `AttachmentUsageService` |
| Service | `AttachmentParserService` |
| Resource | `AttachmentResource` |