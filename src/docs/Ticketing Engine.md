# Ticket Engine

> Dokumen ini menjelaskan seluruh implementasi, arsitektur, workflow, dan business rule pada Ticket Engine.
>
> Ticket merupakan **Root Entity** pada Ticketing System. Seluruh module lain berpusat pada Ticket.

---

# Daftar Isi

1. Tujuan
2. Responsibility
3. Database
4. Dependency
5. Lifecycle
6. Status Workflow
7. Business Rule
8. Ticket Structure
9. Ticket Module
10. Ticket API
11. Attachment Integration
12. Timeline Integration
13. Comment Integration
14. Permission
15. Future Development

---

# 1. Tujuan

Ticket digunakan untuk mencatat seluruh:

- Incident
- Bug Report
- Service Request
- Change Request
- Technical Support
- Enhancement

Ticket menjadi pusat seluruh aktivitas pada sistem.

---

# 2. Responsibility

Ticket Engine bertanggung jawab terhadap:

- Create Ticket
- Update Ticket
- Delete Draft Ticket
- Submit Ticket
- Review Ticket
- Assignment
- Status Workflow
- Progress
- Resolution
- Close Ticket
- Timeline
- Comment
- Attachment

Ticket **tidak** bertanggung jawab terhadap:

- Upload File
- Notification
- User Authentication

---

# 3. Database

## Tabel

```
tickets
```

Master Data.

```
ticket_statuses

ticket_priorities

ticket_categories

sla_rules

application_features
```

Child Entity.

```
ticket_comments

ticket_activities

attachment_usages
```

---

# 4. Dependency

Ticket merupakan Root Module.

```text
                 Ticket
                    │
        ┌───────────┼───────────┐
        │           │           │
        ▼           ▼           ▼
    Comment     Activity   Attachment
        │
        ▼
    Progress (Future)
        │
        ▼
     Review (Future)
```

Semua module bergantung kepada Ticket.

Ticket tidak bergantung kepada module lain selain Shared.

---

# 5. Lifecycle

```text
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

# 6. Status Workflow

## Draft

Status awal.

Diizinkan:

- Edit
- Delete

Tidak diizinkan:

- Assignment
- Comment
- Progress

---

## Submitted

Ticket telah dikirim.

Diizinkan:

- Review
- Comment

---

## Reviewed

Ticket telah diverifikasi Vendor.

Diizinkan:

- Assignment

---

## Assigned

Ticket telah diberikan kepada PIC.

Diizinkan:

- Progress
- Comment

---

## In Progress

Developer sedang bekerja.

Diizinkan:

- Progress
- Comment

---

## Pending

Menunggu informasi tambahan.

Diizinkan:

- Comment
- Progress

---

## Resolved

Pekerjaan selesai.

Menunggu konfirmasi Client.

---

## Closed

Ticket selesai.

Seluruh perubahan ditolak.

---

# 7. Business Rule

## Create Ticket

Syarat.

- User Login
- Memiliki Permission
- Application Feature dipilih
- Category dipilih
- Priority dipilih

Status awal.

```
Draft
```

---

## Submit Ticket

Syarat.

- Draft
- Data valid

Status berubah.

```
Draft

↓

Submitted
```

---

## Review Ticket

Hanya Vendor.

Status.

```
Submitted

↓

Reviewed
```

---

## Assignment

Hanya setelah Review.

```
Reviewed

↓

Assigned
```

---

## Progress

Hanya ketika Ticket aktif.

```
Assigned

↓

In Progress
```

---

## Resolve

Status.

```
In Progress

↓

Resolved
```

---

## Close

Status.

```
Resolved

↓

Closed
```

---

# 8. Ticket Structure

## Ticket

Memiliki.

```
Ticket

├── Description

├── Status

├── Priority

├── Category

├── Application Feature

├── SLA

├── Attachments

├── Timeline

├── Comments

└── Activities
```

---

# 9. Ticket Module

## Service

```
TicketService
```

Tanggung jawab.

- CRUD
- Workflow
- Validation

---

## Controller

```
TicketController
```

---

## Request

```
StoreTicketRequest

UpdateTicketRequest

ReviewTicketRequest
```

---

## Resource

```
TicketResource
```

---

## Filter

```
TicketFilter
```

---

# 10. Ticket API

## Create

```
POST /tickets
```

---

## List

```
GET /tickets
```

---

## Detail

```
GET /tickets/{ticket}
```

---

## Update

```
PUT /tickets/{ticket}
```

---

## Delete

```
DELETE /tickets/{ticket}
```

---

## Review

```
POST /tickets/{ticket}/review
```

---

## Assignment

```
POST /tickets/{ticket}/assignment
```

---

## Progress

```
POST /tickets/{ticket}/progress
```

---

## Resolve

```
POST /tickets/{ticket}/resolve
```

---

## Close

```
POST /tickets/{ticket}/close
```

---

# 11. Attachment Integration

Description menggunakan Markdown.

Attachment menggunakan Attachment Engine.

```md
![Screenshot](attachment://ULID)

[Laporan.pdf](attachment://ULID)
```

Response API.

```json
{
    "description":"...",

    "attachments":[]
}
```

Attachment diproses melalui.

```
AttachmentParserService

↓

AttachmentUsageService
```

Ticket tidak pernah melakukan upload file secara langsung.

---

# 12. Timeline Integration

Timeline dibuat otomatis.

Trigger.

- Ticket dibuat
- Submit
- Review
- Assignment
- Progress
- Resolve
- Close

Timeline bukan Comment.

Timeline tidak dapat diedit.

---

# 13. Comment Integration

Comment merupakan child Ticket.

Relationship.

```
Ticket

↓

Comments
```

Comment menggunakan Attachment Engine.

Comment bersifat linear.

---

# 14. Permission

Permission utama.

```
ticket.view

ticket.view.own

ticket.view.assigned

ticket.create

ticket.update

ticket.delete

ticket.review

ticket.assign

ticket.progress

ticket.resolve

ticket.close
```

Business Rule tetap berada pada Service.

---

# 15. Search & Filter

Ticket dapat difilter berdasarkan.

- Status
- Priority
- Category
- Application Feature
- Assignee
- Reporter
- Date
- Keyword

Menggunakan.

```
TicketFilter
```

---

# 16. Response Structure

Ticket selalu mengembalikan.

```json
{
    "id": 1,

    "ticket_number": "...",

    "status": {},

    "priority": {},

    "category": {},

    "application_feature": {},

    "reporter": {},

    "assignee": {},

    "description": "...",

    "attachments": [],

    "created_at": "...",

    "updated_at": "..."
}
```

---

# 17. Design Decision

Ticket menjadi Root Entity.

Seluruh module lain bergantung kepada Ticket.

Keuntungan.

- Workflow lebih sederhana.
- Integrasi lebih mudah.
- Timeline terpusat.
- Attachment reusable.
- Mudah dikembangkan.

---

# 18. Future Development

Direncanakan.

- SLA Escalation
- Recurring Ticket
- Parent / Child Ticket
- Merge Ticket
- Split Ticket
- AI Classification
- AI Summary
- AI Suggested Reply
- AI Auto Assignment
- Customer Satisfaction Survey

---

# Ringkasan

Ticket Engine merupakan pusat seluruh sistem.

Seluruh proses bisnis dimulai dari Ticket.

Module lain tidak boleh mengubah workflow Ticket secara langsung.

Seluruh perubahan status dan business rule harus dilakukan melalui **TicketService** agar konsisten dan mudah dipelihara.