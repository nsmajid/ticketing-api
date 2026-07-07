# Module Specification

> Dokumen ini menjelaskan spesifikasi setiap module pada Ticketing System.
>
> Fokus dokumen ini adalah menjelaskan tanggung jawab setiap module, dependency, API, database, workflow, dan roadmap pengembangannya.
>
> Detail implementasi masing-masing module dijelaskan pada dokumen tersendiri apabila diperlukan.

---

# Daftar Module

Saat ini project terdiri dari module berikut.

| Module | Status |
|---------|--------|
| Shared | ✅ |
| User | ✅ |
| Application | ✅ |
| Ticket | ✅ |
| Ticket Comment | ✅ |
| Ticket Activity | ✅ |
| Attachment | ✅ |
| Notification | 🚧 |
| Internal Notes | 📋 |
| Knowledge Base | 📋 |

---

# Template Module

Seluruh module mengikuti struktur berikut.

```

Module

├── Responsibility

├── Database

├── Service

├── Controller

├── Request

├── Resource

├── Filter

├── Middleware

├── API

├── Permission

├── Workflow

├── Dependency

└── Future Development

```

---

# Shared Module

## Responsibility

Shared merupakan module yang berisi komponen yang digunakan oleh seluruh module.

Module lain boleh bergantung kepada Shared.

Sebaliknya Shared tidak boleh bergantung kepada module lain.

---

## Isi Module

- BaseService
- BaseFilter
- Trait
- Shared Enum
- Helper
- Response Utility

---

## Dependency

```

Semua Module

↓

Shared

```

---

## Future

- Shared Exception
- Shared Event
- Shared DTO

---

# User Module

## Responsibility

Mengelola data pengguna.

- CRUD User
- Role
- Permission
- Authentication

---

## Database

```

users

roles

permissions

model_has_roles

role_has_permissions

```

---

## Service

```

UserService

RoleService

PermissionService

```

---

## API

```

GET /users

POST /users

PUT /users/{id}

DELETE /users/{id}

```

---

## Dependency

```

Shared

↓

User

```

---

## Future

- Avatar
- MFA
- Session Management

---

# Application Module

## Responsibility

Master data aplikasi.

Application.

Application Feature.

---

## Database

```

applications

application_features

```

---

## Service

```

ApplicationService

ApplicationFeatureService

```

---

## Dependency

```

Shared

↓

Application

```

---

# Ticket Module

## Responsibility

Module utama.

Mengelola seluruh lifecycle Ticket.

---

## Database

```

tickets

ticket_statuses

ticket_priorities

ticket_categories

sla_rules

```

---

## Service

```

TicketService
```

---

## Workflow

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

---

## Dependency

```

Ticket

↓

Attachment

↓

Timeline

↓

Comment

```

Ticket menjadi root entity.

---

## Future

- SLA Escalation
- AI Summary
- AI Classification

---

# Ticket Comment Module

## Responsibility

Mengelola komunikasi pada Ticket.

Comment bersifat linear.

Tidak nested.

---

## Database

```

ticket_comments

```

---

## Service

```

TicketCommentService
```

---

## API

```

GET /tickets/{ticket}/comments

POST /tickets/{ticket}/comments

PUT /ticket-comments/{id}

DELETE /ticket-comments/{id}

```

---

## Permission

```

ticket.comment.create

ticket.comment.update

ticket.comment.delete

```

---

## Workflow

Comment dapat dibuat.

Submitted

↓

Closed (exclusive)

---

## Dependency

```

TicketComment

↓

Attachment

↓

Timeline

```

---

## Future

- Quote Reply
- Reaction
- Read Status

---

# Ticket Activity Module

## Responsibility

Menyimpan Timeline.

Bukan Audit.

Bukan Comment.

---

## Database

```

ticket_activities

```

---

## Service

```

TicketActivityService
```

---

## Dependency

```

Ticket

↓

Activity

```

---

## Future

- Activity Filter
- Export Timeline

---

# Attachment Module

## Responsibility

Mengelola seluruh file.

Attachment bersifat reusable.

Tidak dimiliki oleh Ticket.

Tidak dimiliki oleh Comment.

---

## Database

```

attachments

attachment_usages

```

---

## Service

```

AttachmentService

AttachmentParserService

AttachmentUsageService
```

---

## Storage

Development

```

MinIO

```

Production

```

Cloudflare R2

```

---

## Workflow

```

Upload

↓

Temporary

↓

Parser

↓

Usage

↓

Permanent

```

---

## Dependency

Semua module yang memiliki Markdown bergantung kepada Attachment.

```

Ticket

↓

Attachment

Comment

↓

Attachment

Progress

↓

Attachment

Review

↓

Attachment

```

---

## Future

- Image Resize
- OCR
- Antivirus Scan
- Thumbnail Generator

---

# Notification Module

## Status

Roadmap.

---

## Responsibility

Mengirim notifikasi.

- Email
- In App
- Webhook

---

## Future

- Queue
- Push Notification
- WhatsApp

---

# Internal Notes Module

## Status

Roadmap.

---

## Responsibility

Catatan internal Vendor.

Tidak terlihat Client.

---

## Future

- Mention
- Attachment
- Markdown

---

# Knowledge Base Module

## Status

Roadmap.

---

## Responsibility

Pusat dokumentasi penyelesaian masalah.

Terintegrasi dengan Ticket.

---

# Dependency Matrix

```

Shared

│

├── User

├── Application

├── Ticket

│     │

│     ├── TicketComment

│     ├── TicketActivity

│     └── Attachment

│

└── Notification

```

Dependency harus mengarah ke bawah.

Tidak boleh terjadi Circular Dependency.

---

# Penambahan Module Baru

Sebelum membuat module baru pastikan.

- Responsibility jelas.
- Tidak tumpang tindih dengan module lain.
- Memiliki Service sendiri.
- Mengikuti Coding Convention.
- Dicatat pada Roadmap.
- Ditambahkan ke dokumen ini.

---

# Ringkasan

Setiap module harus memiliki:

- Responsibility yang jelas.
- Dependency yang sederhana.
- Service sebagai pusat Business Logic.
- API yang konsisten.
- Database yang terpisah berdasarkan domain.
- Dokumentasi pada dokumen ini.

Module baru wajib mengikuti pola yang sama agar arsitektur project tetap konsisten.