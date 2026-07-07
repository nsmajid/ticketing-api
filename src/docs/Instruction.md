# Instruction

> Dokumen ini merupakan aturan utama pengembangan Ticketing System.
>
> Semua implementasi baru **WAJIB** mengikuti dokumen ini.
> Apabila terdapat konflik antara implementasi dan dokumen ini, maka dokumen ini menjadi acuan utama sampai terdapat keputusan desain baru yang dicatat pada `Decision Log.md`.

---

# Tujuan Project

Membangun aplikasi Ticketing System yang scalable, maintainable, reusable, dan mudah dikembangkan dalam jangka panjang.

Project dikembangkan menggunakan pendekatan **Modular Architecture** dengan Laravel 13.

Prioritas utama:

1. Readability
2. Maintainability
3. Consistency
4. Scalability

Kode yang sedikit lebih panjang tetapi mudah dipahami lebih diutamakan daripada kode yang terlalu kompleks.

---

# Prinsip Pengembangan

Seluruh implementasi harus mengikuti prinsip berikut.

## 1. Business Logic hanya berada di Service

Controller **tidak boleh** berisi business logic.

Controller hanya bertugas:

- menerima request
- memanggil Service
- mengembalikan Resource

Contoh yang benar

```
Controller

↓

Service

↓

Model
```

---

## 2. Resource hanya bertugas serialisasi

Resource tidak boleh melakukan:

- query database
- business logic
- validasi

Resource hanya mengubah Model menjadi Response API.

---

## 3. Request hanya untuk validasi

Semua validasi Request berada pada FormRequest.

Business Rule tetap berada di Service.

---

## 4. Service menjadi pusat business logic

Semua proses:

- Create
- Update
- Delete
- Review
- Assignment
- Attachment
- Timeline

harus diproses melalui Service.

---

## 5. Gunakan BaseService

Seluruh Service harus mewarisi:

```
BaseService
```

Gunakan helper yang sudah tersedia seperti:

- transaction()
- filteredPaginate()

Jangan membuat implementasi transaction manual apabila BaseService sudah menyediakan helper.

---

## 6. Gunakan baseQuery()

Semua query utama harus berasal dari:

```
baseQuery()
```

Jangan melakukan query berulang dengan eager loading berbeda-beda.

Semua eager loading ditempatkan pada baseQuery().

---

## 7. Gunakan Enum

Seluruh nilai yang bersifat tetap harus menggunakan Enum.

Contoh:

- TicketStatusCode
- Role
- Permission
- AttachmentOwnerType

Hindari penggunaan string literal.

Contoh yang tidak diperbolehkan

```
if ($status == 'submitted')
```

Gunakan

```
TicketStatusCode::Submitted
```

---

# Arsitektur Project

Project menggunakan Modular Architecture.

Struktur utama:

```
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

Model tetap berada pada

```
app/Models
```

Sedangkan Controller, Service, Resource, Request dipisahkan berdasarkan Module.

---

# Repository Pattern

Project **TIDAK menggunakan Repository Pattern**.

Gunakan Eloquent langsung melalui Service.

---

# Coding Style

Seluruh implementasi mengikuti style berikut.

## Constructor Injection

Gunakan Dependency Injection.

Contoh

```php
public function __construct(
    private TicketService $service,
) {}
```

Jangan menggunakan Facade apabila Dependency Injection lebih sesuai.

---

## Method

Gunakan format.

```php
public function update(
    Ticket $ticket,
    array $data
): Ticket
```

---

## PHPDoc

Seluruh public method harus memiliki PHPDoc.

---

## Naming

Method menggunakan nama sederhana.

Contoh.

```
index()

show()

create()

update()

delete()
```

Hindari:

```
createNewTicket()

updateTicketData()
```

---

# Business Rule

Business Rule menggunakan helper.

Contoh.

```
ensureEditable()

ensureCreatable()

ensureAssignable()

ensureReviewable()

ensureDeletable()
```

Jangan menuliskan business rule berulang pada beberapa method.

---

# Database

Gunakan Migration.

Jangan melakukan perubahan schema langsung pada database.

Seluruh perubahan schema harus melalui Migration.

---

# API

API menggunakan REST.

Gunakan nested route apabila entity merupakan child.

Contoh.

```
/tickets/{ticket}/comments
```

lebih disukai daripada

```
/comments
```

---

Gunakan Resource untuk seluruh response.

Jangan mengembalikan Model secara langsung.

---

# Attachment Engine

Seluruh Attachment menggunakan Attachment Engine.

Jangan membuat upload file secara langsung pada Module lain.

Seluruh upload dilakukan melalui:

```
AttachmentService
```

---

Markdown selalu menggunakan format.

Image

```
![Alt](attachment://ULID)
```

File

```
[NamaFile.pdf](attachment://ULID)
```

Jangan menyimpan URL file pada database.

---

Attachment bersifat reusable.

Attachment dapat digunakan oleh:

- Ticket
- Comment
- Progress
- Review
- Resolve
- Close
- Knowledge Base

---

# Resource

Seluruh entity yang memiliki Markdown harus memiliki property:

```
attachments
```

Contoh.

```
{
    "description":"...",

    "attachments":[]
}
```

atau

```
{
    "content":"...",

    "attachments":[]
}
```

---

# Permission

Permission menggunakan Spatie Permission.

Format.

```
ticket.*

ticket.comment.*

ticket.review.*

ticket.assignment.*
```

Controller menggunakan:

```
HasMiddleware
```

Business Rule tetap berada di Service.

---

# Error Handling

Saat ini project menggunakan:

```
abort(...)
```

untuk Business Rule.

Apabila di masa depan dilakukan migrasi ke Custom Exception, maka seluruh module harus mengikuti pola yang sama.

Jangan mencampurkan dua pendekatan.

---

# Decision Log

Apabila terdapat perubahan desain:

- Attachment
- Workflow
- Database
- API
- Permission

Maka wajib memperbarui:

```
Decision Log.md
```

Sebelum implementasi dilakukan.

---

# Roadmap

Sebelum membuat fitur baru:

1. Periksa Roadmap.md
2. Pastikan tidak bertentangan dengan Decision Log.md
3. Ikuti Coding Convention.md
4. Implementasikan sesuai Architecture.md

---

# Checklist Sebelum Memberikan Kode

Sebelum menulis implementasi baru, pastikan:

- [ ] Sudah membaca Project Summary.md
- [ ] Sudah membaca Decision Log.md
- [ ] Mengikuti Coding Convention.md
- [ ] Tidak menduplikasi business logic
- [ ] Menggunakan Enum jika memungkinkan
- [ ] Menggunakan Service Pattern
- [ ] Tidak menambahkan Repository Pattern
- [ ] Tidak melakukan query di Resource
- [ ] Tidak menyimpan URL file pada database
- [ ] Menggunakan Attachment Engine jika terdapat upload file

---
# Prinsip Utama

Prioritas pengembangan:

1. Konsistensi
2. Maintainability
3. Reusability
4. Scalability

Project lebih mengutamakan arsitektur yang bersih dibanding implementasi yang cepat.

Seluruh implementasi baru harus mempertahankan filosofi tersebut.