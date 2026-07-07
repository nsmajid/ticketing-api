# Database Design

> Dokumen ini menjelaskan desain database Ticketing System.
>
> Dokumen ini merupakan referensi utama seluruh struktur database yang digunakan pada project.
>
> Seluruh perubahan struktur database harus diperbarui pada dokumen ini.

---

# Daftar Isi

1. Design Principles
2. Database Convention
3. Entity Relationship
4. Master Data
5. Transaction Data
6. Attachment Engine
7. User & Permission
8. Index Strategy
9. Naming Convention
10. Future Database

---

# 1. Design Principles

Database dirancang menggunakan prinsip:

- Relational Database
- Normalisasi hingga minimal 3NF
- Soft Delete hanya digunakan pada entity tertentu
- Foreign Key digunakan pada seluruh relasi yang memungkinkan
- Index dibuat berdasarkan kebutuhan query
- Metadata file dipisahkan dari Object Storage

---

# 2. Database Convention

## Primary Key

Seluruh tabel menggunakan.

```sql
id BIGINT UNSIGNED AUTO_INCREMENT
```

Sebagai Primary Key.

---

## ULID

Entity tertentu memiliki ULID.

Contoh.

```
attachments
```

ULID digunakan sebagai identifier publik.

Database tetap menggunakan ID sebagai relasi.

---

## Timestamp

Seluruh tabel menggunakan.

```
created_at

updated_at
```

Soft Delete hanya digunakan apabila benar-benar diperlukan.

---

## Migration

Seluruh perubahan struktur database wajib menggunakan Migration.

Perubahan manual pada production database tidak diperbolehkan.

---

# 3. Entity Relationship

## Master Data

```
applications

application_features

ticket_categories

ticket_priorities

ticket_statuses

sla_rules
```

---

## Transaction

```
tickets

ticket_comments

ticket_activities
```

---

## Attachment

```
attachments

attachment_usages
```

---

## User

```
users

roles

permissions
```

---

# 4. Master Data

## applications

### Fungsi

Master aplikasi.

Contoh.

- HRIS
- ERP
- Mobile Apps

---

### Digunakan oleh

```
application_features
```

---

## application_features

### Fungsi

Master fitur pada aplikasi.

Contoh.

```
HRIS

↓

Attendance

↓

Leave

↓

Payroll
```

Ticket akan mengacu pada feature.

Bukan application.

---

## ticket_categories

Master kategori Ticket.

---

## ticket_priorities

Master prioritas.

Contoh.

- Low
- Medium
- High
- Critical

---

## ticket_statuses

Master status workflow.

Status bersifat dinamis.

Workflow menggunakan status ini.

---

## sla_rules

Menyimpan SLA.

Contoh.

Response Time.

Resolution Time.

---

# 5. Transaction

## tickets

### Fungsi

Entity utama.

Semua module bergantung kepada Ticket.

---

### Relationship

```
Ticket

↓

Comment

↓

Timeline

↓

Attachment

↓

Progress

↓

Review
```

---

### Kolom Penting

- application_feature_id
- category_id
- priority_id
- status_id

---

## ticket_comments

### Fungsi

Komentar pada Ticket.

Comment bersifat linear.

Tidak nested.

---

### Relationship

```
Ticket

1

↓

N

Comment
```

---

### Attachment

Comment menggunakan Attachment Engine.

---

## ticket_activities

### Fungsi

Timeline.

Bukan Comment.

Bukan Audit.

---

# 6. Attachment Engine

## attachments

### Fungsi

Menyimpan metadata file.

File fisik berada pada Object Storage.

---

### Kolom Penting

| Kolom | Keterangan |
|--------|------------|
| id | Primary Key |
| ulid | Public Identifier |
| disk | Nama Storage |
| directory | Folder |
| path | Full Object Path |
| filename | Nama File Storage |
| original_filename | Nama File Asli |
| extension | Ekstensi |
| mime_type | MIME |
| checksum | SHA256 |
| size | Ukuran |
| uploaded_by | User Upload |
| is_temporary | Status Temporary |
| expired_at | Masa Berlaku |

---

### Lifecycle

```
Upload

↓

Temporary

↓

Used

↓

Permanent

↓

Archive (future)
```

---

## attachment_usages

### Fungsi

Menghubungkan Attachment dengan entity lain.

---

### Relationship

```
Attachment

↓

Attachment Usage

↓

Ticket

Comment

Progress

Review

Close
```

---

### Kolom

| Kolom | Keterangan |
|--------|------------|
| attachment_id | FK Attachment |
| owner_type | Enum Entity |
| owner_id | ID Entity |

---

### Mengapa dibuat terpisah?

Karena satu Attachment dapat digunakan banyak entity.

Selain itu Attachment bersifat reusable.

---

# 7. User & Permission

Menggunakan Spatie Permission.

Tabel.

```
users

roles

permissions

model_has_roles

model_has_permissions

role_has_permissions
```

Tidak dilakukan modifikasi besar terhadap struktur bawaan Spatie.

---

# 8. Index Strategy

Index dibuat berdasarkan query yang paling sering digunakan.

Contoh.

```
tickets.status_id

tickets.priority_id

tickets.application_feature_id
```

---

Attachment.

```
attachments.ulid

attachments.is_temporary

attachment_usages.owner_type

attachment_usages.owner_id
```

---

User.

```
users.email
```

---

# 9. Naming Convention

## Table

Plural.

Contoh.

```
tickets

attachments

ticket_comments
```

---

## Foreign Key

Gunakan.

```
ticket_id

status_id

attachment_id
```

Bukan.

```
id_ticket
```

---

## Boolean

Gunakan prefix.

```
is_

has_
```

Contoh.

```
is_active

is_closed

is_temporary
```

---

## Enum

Gunakan kode.

Contoh.

```
Submitted

Resolved

Closed
```

Jangan menggunakan integer magic number.

---

# 10. Future Database

Direncanakan akan ditambahkan.

```
ticket_progress

ticket_review

ticket_close

internal_notes

notifications

notification_logs

email_logs

knowledge_bases
```

---

# Database Decision

Project menggunakan:

- BIGINT sebagai Primary Key
- ULID sebagai Public Identifier
- Relational Database
- Object Storage
- Attachment Engine
- Enum
- Foreign Key

Keputusan tersebut dibuat agar database tetap konsisten, scalable, dan mudah dikembangkan.

---

# Ringkasan

Prinsip utama desain database.

- Normalisasi
- Konsistensi
- Reusability
- Skalabilitas
- Integritas Data

Setiap perubahan struktur database harus memperbarui dokumen ini terlebih dahulu sebelum implementasi Migration dilakukan.