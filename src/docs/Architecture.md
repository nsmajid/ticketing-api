# Architecture

> Dokumen ini menjelaskan arsitektur utama Ticketing System.
>
> Dokumen ini menjadi acuan seluruh pengembangan backend dan harus dibaca sebelum melakukan implementasi fitur baru.

---

# Daftar Isi

1. Tujuan Arsitektur
2. Karakteristik Sistem
3. Arsitektur Tingkat Tinggi
4. Layer Architecture
5. Modular Architecture
6. Dependency Rule
7. Folder Structure
8. Request Lifecycle
9. Database Architecture
10. Storage Architecture
11. Business Rule Architecture
12. Design Principles
13. Future Architecture

---

# 1. Tujuan Arsitektur

Arsitektur dirancang untuk memenuhi kebutuhan berikut.

- Mudah dipelihara.
- Mudah dipahami developer baru.
- Mendukung pengembangan jangka panjang.
- Mengurangi duplikasi kode.
- Memisahkan Business Logic dari HTTP Layer.
- Memungkinkan penambahan module baru tanpa perubahan besar.

Prioritas utama project.

1. Maintainability
2. Consistency
3. Reusability
4. Scalability

---

# 2. Karakteristik Sistem

Project menggunakan pendekatan:

- Modular Monolith
- REST API
- Stateless
- Service Layer Pattern
- Domain Oriented Module
- Eloquent ORM

Project **tidak menggunakan**

- Microservice
- Repository Pattern
- CQRS
- Event Sourcing

Keputusan ini diambil untuk menjaga kompleksitas tetap rendah selama fase MVP.

---

# 3. High Level Architecture

```text
                        Browser / Mobile App
                                 │
                                 ▼
                           REST API
                                 │
                                 ▼
                          Middleware Layer
                                 │
                                 ▼
                           Controller Layer
                                 │
                                 ▼
                         Request Validation
                                 │
                                 ▼
                           Service Layer
                     ┌───────────┴───────────┐
                     │                       │
                     ▼                       ▼
                Business Rule         External Service
                     │          (Storage / Mail / Queue)
                     ▼
                  Eloquent ORM
                     │
                     ▼
                   MySQL
```

Semua request mengikuti alur tersebut.

Business Logic tidak boleh berada di Controller maupun Model.

---

# 4. Layer Architecture

Project dibagi menjadi lima layer utama.

```text
Presentation Layer

↓

Application Layer

↓

Domain Layer

↓

Persistence Layer

↓

Infrastructure Layer
```

---

## Presentation Layer

Berisi:

- Controller
- Request
- Resource
- Middleware

Tanggung jawab:

- menerima request
- validasi input
- serialisasi response

Tidak diperbolehkan:

- query database
- business rule
- upload file
- manipulasi workflow

---

## Application Layer

Berisi:

- Service
- Filter

Seluruh Business Logic berada pada layer ini.

Semua perubahan data harus dilakukan melalui Service.

---

## Domain Layer

Berisi:

- Model
- Enum

Model hanya merepresentasikan entity.

Model tidak boleh mengandung Business Logic yang kompleks.

---

## Persistence Layer

Menggunakan:

- MySQL
- Eloquent ORM

Semua perubahan schema dilakukan melalui Migration.

---

## Infrastructure Layer

Berisi:

- Storage
- Mail
- Queue
- Scheduler

Contoh.

Development

- MinIO

Production

- Cloudflare R2

---

# 5. Modular Architecture

Project menggunakan pendekatan Domain Module.

```text
app/

Attachment/
Application/
Notification/
Shared/
Ticket/
TicketActivity/
TicketComment/
User/

Models/
```

Setiap module memiliki struktur serupa.

```text
Module/

Controllers/

Requests/

Resources/

Services/

Filters/

Enums/

Middleware/
```

Model tetap berada pada:

```text
app/Models
```

Tujuannya:

- mengurangi circular dependency
- mempermudah pencarian file
- menjaga konsistensi

---

# 6. Dependency Rule

Dependency hanya boleh mengarah ke bawah.

```text
Controller

↓

Service

↓

Model
```

Tidak diperbolehkan.

```text
Model

↓

Service
```

atau

```text
Resource

↓

Service
```

atau

```text
Request

↓

Model
```

---

Module boleh menggunakan Service module lain.

Contoh.

```text
TicketCommentService

↓

AttachmentParserService

↓

AttachmentUsageService
```

Sebaliknya.

```text
AttachmentService

↓

TicketCommentService
```

tidak diperbolehkan.

Dependency harus satu arah.

---

# 7. Folder Structure

```text
app/

Attachment/
Application/
Notification/
Shared/
Ticket/
TicketActivity/
TicketComment/
User/

Models/
```

Shared berisi komponen yang dapat digunakan seluruh module.

Contoh.

- BaseService
- BaseFilter
- Trait
- Helper
- Shared Enum

---

# 8. Request Lifecycle

```text
HTTP Request

↓

Middleware

↓

Controller

↓

Form Request

↓

Service

↓

Model

↓

Database

↓

Resource

↓

JSON Response
```

Seluruh request mengikuti lifecycle tersebut.

---

# 9. Database Architecture

Database menggunakan pendekatan Relational Database.

Entity utama.

- Ticket
- Comment
- Attachment
- User

Semua relasi dijelaskan pada `Database.md`.

Architecture.md hanya menjelaskan hubungan antar layer.

---

# 10. Storage Architecture

Database hanya menyimpan metadata.

File fisik berada pada Object Storage.

```text
Application

↓

AttachmentService

↓

Object Storage

↓

MinIO / R2
```

Keuntungan.

- storage independent
- mudah migrasi
- scalable

---

# 11. Business Rule Architecture

Business Rule hanya berada pada Service.

Business Rule menggunakan helper.

Contoh.

```php
ensureEditable()

ensureCreatable()

ensureReviewable()

ensureAssignable()

ensureDeletable()
```

Business Rule tidak boleh berada pada:

- Controller
- Resource
- Request
- Model

---

# 12. Design Principles

Project mengikuti prinsip berikut.

## Single Responsibility Principle

Satu class memiliki satu tanggung jawab.

---

## Separation of Concerns

Presentation dipisahkan dari Business Logic.

---

## DRY

Logic yang digunakan lebih dari satu module harus dipindahkan ke Shared.

---

## Composition over Inheritance

Gunakan Dependency Injection.

Hindari inheritance yang berlebihan.

---

## Consistency over Cleverness

Kode yang konsisten lebih diutamakan daripada implementasi yang terlalu pintar tetapi sulit dipahami.

---

# 13. Future Architecture

Arsitektur dipersiapkan untuk mendukung.

- Queue
- Redis
- WebSocket
- Notification
- AI
- OCR
- Knowledge Base

tanpa mengubah struktur dasar project.

---

# Architecture Decision

Project memilih:

✅ Modular Monolith

karena:

- lebih sederhana
- lebih mudah dipelihara
- deployment lebih mudah
- tidak membutuhkan distributed transaction

Project **tidak menggunakan Microservice** pada tahap awal.

Apabila suatu saat project berkembang sangat besar, module dapat dipisahkan menjadi service terpisah tanpa perubahan besar pada Business Layer.

---

# Ringkasan

Prinsip utama arsitektur.

- Modular
- Maintainable
- Reusable
- Consistent
- Scalable

Seluruh implementasi baru wajib mengikuti dokumen ini.