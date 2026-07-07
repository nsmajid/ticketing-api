# Project Summary

## Tentang Project

Project ini merupakan aplikasi **Ticketing System** yang digunakan untuk mengelola komunikasi, pelaporan masalah, permintaan perubahan, dan proses penyelesaian pekerjaan antara **Vendor** dan **Client**.

Project dikembangkan menggunakan **Laravel 13** dengan pendekatan **Modular Architecture** agar mudah dikembangkan dalam jangka panjang.

---

# Tujuan Project

Membangun sistem Ticketing yang:

- scalable
- maintainable
- reusable
- mudah dikembangkan
- mudah dipahami developer baru
- siap digunakan pada lingkungan production

Project dirancang agar mampu berkembang menjadi platform Service Desk yang lebih lengkap tanpa perlu melakukan perubahan arsitektur besar.

---

# Ruang Lingkup

Saat ini project berfokus pada modul berikut.

- Ticket Management
- Ticket Workflow
- Assignment
- Review
- Progress
- Timeline
- Comment
- Attachment Management
- User & Role Management
- Permission Management
- Master Data

Roadmap berikutnya akan mencakup:

- Notification
- Internal Notes
- Email Notification
- SLA Monitoring
- Dashboard
- Reporting
- Knowledge Base

---

# Teknologi

## Backend

- Laravel 13
- PHP 8.4
- MySQL

## Development

- Docker
- Docker Compose
- MinIO

## Production

- Cloudflare R2
- Cloud Run / VPS (akan ditentukan)

## Authentication

- Laravel Authentication
- Spatie Permission

---

# Arsitektur

Project menggunakan **Modular Architecture**.

Contoh struktur.

```
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

Model tetap berada pada

```
app/Models
```

Sedangkan Controller, Service, Request, Resource dipisahkan berdasarkan Module.

---

# Filosofi Pengembangan

Project mengikuti beberapa prinsip utama.

## 1.

Business Logic hanya berada di Service.

---

## 2.

Controller tetap tipis.

---

## 3.

Resource hanya melakukan serialisasi.

---

## 4.

Semua upload file menggunakan Attachment Engine.

---

## 5.

Semua perubahan database menggunakan Migration.

---

## 6.

Gunakan Enum sebanyak mungkin.

---

# Workflow Ticket

Workflow utama.

```
Draft

↓

Submitted

↓

Reviewed

↓

Assigned

↓

In Progress

↓

Pending

↓

Resolved

↓

Closed
```

Workflow dapat berkembang pada masa depan tanpa mengubah struktur dasar.

---

# Attachment Engine

Attachment merupakan module reusable.

Attachment dapat digunakan oleh:

- Ticket Description
- Comment
- Progress
- Review
- Resolve
- Close
- Knowledge Base

Attachment **bukan milik Ticket** maupun **Comment**.

Attachment menggunakan engine tersendiri.

---

# Markdown Strategy

Seluruh rich text menggunakan Markdown.

Attachment menggunakan format.

Image.

```md
![Alt](attachment://ULID)
```

File.

```md
[NamaFile.pdf](attachment://ULID)
```

Database **tidak pernah** menyimpan URL file.

---

# Permission

Permission menggunakan Spatie Permission.

Pattern.

```
ticket.*

ticket.comment.*

ticket.review.*

ticket.assignment.*
```

Business Rule tetap berada di Service.

---

# Status Pengembangan

## Selesai

- Master Data
- User
- Role
- Permission
- Ticket CRUD
- Ticket Timeline
- Ticket Comment
- Attachment Upload
- Attachment Usage
- Markdown Strategy

---

## Sedang Dikembangkan

- Attachment Engine
- Markdown Integration
- Attachment Resource
- Scheduler Cleanup

---

## Roadmap Berikutnya

- Progress Attachment
- Review Attachment
- Resolve Attachment
- Close Attachment
- Notification
- Internal Notes
- Email Notification
- Dashboard
- Reporting

---

# Prinsip Desain

Project mengutamakan:

1. Consistency
2. Maintainability
3. Reusability
4. Scalability

Implementasi yang sedikit lebih panjang tetapi mudah dipahami lebih disukai dibanding implementasi yang terlalu kompleks.

---

# Dokumen Acuan

Sebelum melakukan pengembangan, baca dokumen berikut secara berurutan.

1. Instruction.md
2. Project Summary.md
3. Architecture.md
4. Database.md
5. Workflow.md
6. Coding Convention.md
7. Decision Log.md

---

# Ringkasan Arsitektur

```
Client

↓

REST API

↓

Controller

↓

Service

↓

Model

↓

Database
```

Untuk fitur Attachment.

```
Upload

↓

AttachmentService

↓

Storage

↓

Attachment

↓

AttachmentParser

↓

AttachmentUsage

↓

Resource
```

---

# Status Dokumen

Versi

```
1.0
```

Status

```
Active
```

Dokumen ini akan terus diperbarui mengikuti perkembangan project.