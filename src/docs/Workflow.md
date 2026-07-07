# Workflow

> Dokumen ini menjelaskan seluruh alur bisnis (Business Workflow) dan alur sistem (System Workflow) pada Ticketing System.
>
> Seluruh perubahan workflow wajib diperbarui pada dokumen ini sebelum dilakukan implementasi.

---

# Daftar Isi

1. Workflow Ticket
2. Workflow Comment
3. Workflow Attachment
4. Workflow Timeline
5. Workflow Assignment
6. Workflow Review
7. Workflow Progress
8. Workflow Notification
9. Workflow Internal Notes
10. Workflow Diagram

---

# 1. Workflow Ticket

Ticket merupakan entity utama pada sistem.

Seluruh module lain berpusat pada Ticket.

---

## Lifecycle Ticket

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

## Penjelasan Status

| Status | Keterangan |
|----------|------------|
| Draft | Ticket baru dibuat dan belum dikirim. |
| Submitted | Ticket telah dikirim oleh Client. |
| Reviewed | Ticket telah diperiksa oleh Vendor. |
| Assigned | Ticket telah ditugaskan kepada Developer atau PIC. |
| In Progress | Sedang dikerjakan. |
| Pending | Menunggu informasi tambahan atau pihak lain. |
| Resolved | Solusi telah diberikan dan menunggu konfirmasi. |
| Closed | Ticket selesai dan tidak dapat dimodifikasi lagi. |

---

## Business Rule

### Draft

Diizinkan:

- Edit Ticket
- Delete Ticket

Tidak diizinkan:

- Comment
- Assignment
- Progress

---

### Submitted

Diizinkan:

- Comment
- Review
- Assignment

---

### Reviewed

Diizinkan:

- Assignment
- Comment

---

### Assigned

Diizinkan:

- Progress
- Comment

---

### In Progress

Diizinkan:

- Progress
- Comment

---

### Pending

Diizinkan:

- Comment
- Progress

---

### Resolved

Diizinkan:

- Comment

---

### Closed

Tidak diperbolehkan:

- Edit Ticket
- Delete Ticket
- Comment
- Progress
- Assignment

Ticket hanya dapat dibaca.

---

# 2. Workflow Comment

Comment digunakan sebagai media komunikasi antara Vendor dan Client.

Comment bersifat linear.

Tidak menggunakan nested comment.

---

## Flow

```text
Create

↓

Update

↓

Delete
```

---

## Struktur

```text
Ticket

↓

Comment

↓

Comment

↓

Comment
```

Semua comment berada pada level yang sama.

---

## Business Rule

Comment hanya dapat dibuat apabila Ticket berada pada status:

- Submitted
- Reviewed
- Assigned
- In Progress
- Pending
- Resolved

Comment tidak dapat dibuat pada:

- Draft
- Closed

---

### Create

Syarat:

- User memiliki permission.
- Ticket masih aktif.
- Minimal memiliki content atau attachment.

---

### Update

Hanya dapat dilakukan oleh:

- Author
- Super Admin

Syarat:

- Comment belum memiliki reply setelahnya (bukan comment terakhir tidak perlu menggunakan created_at, tetapi berdasarkan urutan komentar).
- Ticket belum Closed.

---

### Delete

Hard Delete.

Hanya:

- Author
- Super Admin

Syarat:

- Belum ada comment setelahnya.
- Ticket belum Closed.

---

# 3. Workflow Attachment

Attachment menggunakan engine tersendiri.

Attachment tidak dimiliki oleh Ticket maupun Comment.

---

## Upload

```text
User

↓

Upload File

↓

AttachmentService

↓

Object Storage

↓

attachments

↓

Return Markdown
```

---

## Integrasi Markdown

Editor menyimpan.

```md
![Screenshot](attachment://ULID)

[Laporan.pdf](attachment://ULID)
```

---

## Submit

```text
Comment

↓

AttachmentParserService

↓

Extract ULID

↓

AttachmentUsageService

↓

attachment_usages

↓

markPermanent()
```

---

## Lifecycle

```text
Upload

↓

Temporary

↓

Used

↓

Permanent
```

Attachment yang telah menjadi Permanent tidak akan kembali menjadi Temporary.

---

## Cleanup

Scheduler.

```text
Temporary

↓

Expired

↓

Tidak memiliki Usage

↓

Delete File

↓

Delete Database
```

---

# 4. Workflow Timeline

Timeline dibuat otomatis oleh sistem.

Timeline bukan Comment.

Timeline bukan Audit.

---

## Trigger

Timeline dibuat ketika:

- Ticket dibuat.
- Ticket di-review.
- Ticket di-assign.
- Ticket berubah status.
- Progress ditambahkan.
- Ticket ditutup.

---

# 5. Workflow Assignment

```text
Review

↓

Assign User

↓

Assigned

↓

Developer menerima Ticket
```

---

## Business Rule

Assignment hanya dapat dilakukan apabila:

- Ticket telah di-review.
- Ticket belum Closed.

---

# 6. Workflow Review

```text
Submitted

↓

Review

↓

Approved

↓

Assignment
```

Review hanya dilakukan satu kali pada workflow utama.

---

# 7. Workflow Progress

```text
Developer

↓

Tambah Progress

↓

Timeline

↓

Comment (Opsional)

↓

Attachment (Opsional)
```

Progress dapat dilakukan berkali-kali selama Ticket masih aktif.

---

# 8. Workflow Notification

> Status: Roadmap

Trigger Notification.

- Ticket dibuat.
- Ticket di-review.
- Ticket di-assign.
- Comment baru.
- Progress baru.
- Ticket Closed.

---

# 9. Workflow Internal Notes

> Status: Roadmap

Internal Notes hanya dapat dilihat oleh Vendor.

Tidak terlihat oleh Client.

Tidak mempengaruhi Timeline maupun Comment.

---

# 10. Workflow Diagram

## High Level Workflow

```text
Client

↓

Create Ticket

↓

Submitted

↓

Vendor Review

↓

Assignment

↓

Progress

↓

Resolved

↓

Client Verification

↓

Closed
```

---

## Comment Workflow

```text
Ticket

↓

Comment

↓

Attachment Parser

↓

Attachment Usage

↓

Timeline
```

---

## Attachment Workflow

```text
Upload

↓

Temporary

↓

Markdown

↓

Submit

↓

Parser

↓

Usage

↓

Permanent
```

---

# Ringkasan

Workflow pada project mengikuti prinsip berikut.

- Ticket menjadi root entity.
- Semua proses berpusat pada Ticket.
- Attachment menggunakan engine terpisah.
- Timeline dibuat otomatis oleh sistem.
- Comment bersifat linear.
- Workflow harus dapat dikembangkan tanpa mengubah struktur dasar.