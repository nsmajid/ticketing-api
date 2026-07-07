# Decision Log

> Dokumen ini mencatat seluruh keputusan arsitektur, desain, workflow, dan implementasi yang mempengaruhi project.
>
> Setiap perubahan desain **WAJIB** dicatat pada dokumen ini sebelum atau bersamaan dengan implementasi.
>
> Jangan menghapus keputusan lama. Gunakan status **Superseded** apabila sebuah keputusan sudah digantikan.

---

# Format

Setiap keputusan menggunakan format berikut.

## Decision

Nomor unik.

Contoh.

```
ADR-001
```

---

## Status

- Proposed
- Accepted
- Superseded
- Rejected

---

## Date

Tanggal keputusan.

---

## Context

Masalah yang ingin diselesaikan.

---

## Decision

Keputusan yang dipilih.

---

## Alternatives

Alternatif yang dipertimbangkan.

---

## Consequences

Dampak keputusan.

---

# ADR-001

## Title

Menggunakan Modular Monolith

---

Status

```
Accepted
```

---

Context

Project diperkirakan akan berkembang menjadi cukup besar.

Namun pada fase MVP jumlah developer masih sedikit.

---

Decision

Project menggunakan **Modular Monolith**.

Bukan Microservice.

---

Alternatives

- Microservice
- Clean Architecture
- Modular Monolith

---

Consequences

Keuntungan.

- Deployment sederhana.
- Debugging mudah.
- Tidak ada distributed transaction.
- Cocok untuk tim kecil.

Kekurangan.

- Seluruh module berada pada satu aplikasi.

---

# ADR-002

## Title

Tidak menggunakan Repository Pattern

---

Status

```
Accepted
```

---

Context

Laravel telah memiliki Eloquent ORM.

Repository akan menambah kompleksitas.

---

Decision

Business Logic langsung menggunakan Eloquent melalui Service.

---

Alternatives

- Repository Pattern
- Eloquent

---

Consequences

Keuntungan.

- Kode lebih sederhana.
- Lebih sedikit abstraction.
- Lebih mudah dipahami developer Laravel.

---

# ADR-003

## Title

Business Logic hanya berada pada Service

---

Status

```
Accepted
```

---

Context

Business Rule mulai bertambah.

---

Decision

Semua Business Rule dipindahkan ke Service.

Controller tetap tipis.

---

Consequences

Controller lebih mudah dibaca.

Logic tidak tersebar.

---

# ADR-004

## Title

Menggunakan BaseService

---

Decision

Semua Service mewarisi BaseService.

---

Alasan.

Mengurangi duplikasi.

Menyediakan helper.

- transaction()
- filteredPaginate()

---

# ADR-005

## Title

Attachment menggunakan ULID

---

Context

Attachment akan digunakan pada Markdown.

---

Decision

Attachment menggunakan ULID.

Database tetap menggunakan BIGINT sebagai Primary Key.

---

Alternatives

- UUID
- BIGINT
- ULID

---

Consequences

Keuntungan.

- Lebih pendek dibanding UUID.
- Aman dipublikasikan.
- Tidak mengekspos ID database.

---

# ADR-006

## Title

Markdown menggunakan attachment://ULID

---

Decision

Format.

```md
![Image](attachment://ULID)

[file.pdf](attachment://ULID)
```

---

Alternatives

- URL langsung
- Signed URL
- attachment://

---

Consequences

Keuntungan.

- Storage independent.
- Mudah migrasi MinIO → R2.
- Database tidak berubah ketika storage berubah.

---

# ADR-007

## Title

Attachment dipisahkan dari Ticket

---

Decision

Attachment menjadi module independen.

---

Context

Attachment akan digunakan oleh:

- Ticket
- Comment
- Progress
- Review
- Resolve
- Close

---

Consequences

Lebih reusable.

---

# ADR-008

## Title

Menggunakan attachment_usages

---

Decision

Relasi Attachment menggunakan tabel.

```
attachment_usages
```

---

Alternatives

- Morph Relation
- Pivot
- attachment_usages

---

Consequences

Keuntungan.

- Mendukung banyak entity.
- Mudah ditambah metadata.
- Tidak bergantung pada Morph.

---

# ADR-009

## Title

attachment_usages dianggap Entity

---

Decision

attachment_usages bukan Pivot biasa.

Memiliki Model sendiri.

---

Consequences

Lebih fleksibel apabila nanti ditambahkan.

- created_by
- order
- source
- visibility

---

# ADR-010

## Title

Comment dibuat Linear

---

Decision

Comment tidak menggunakan Nested Comment.

---

Alternatives

- Nested
- Thread
- Linear

---

Consequences

Keuntungan.

- UI sederhana.
- Query ringan.
- Tidak memerlukan recursive tree.

---

# ADR-011

## Title

Comment hanya dapat diubah oleh Author

---

Decision

Update/Delete hanya.

- Author
- Super Admin

Dan.

- Belum ada comment berikutnya.
- Ticket belum Closed.

---

Consequences

Diskusi tetap konsisten.

---

# ADR-012

## Title

Attachment Temporary

---

Decision

Attachment di-upload sebagai.

```
temporary = true
```

Setelah digunakan.

↓

```
temporary = false
```

---

Consequences

Menghindari orphan file.

---

# ADR-013

## Title

Attachment tidak pernah kembali Temporary

---

Decision

Lifecycle.

```
Temporary

↓

Permanent
```

One-way.

---

Consequences

State sederhana.

---

# ADR-014

## Title

Semua entity Markdown memiliki property attachments

---

Decision

Response API.

```json
{
    "content":"...",

    "attachments":[]
}
```

---

Alternatives

Frontend melakukan parsing sendiri.

---

Consequences

Frontend lebih sederhana.

---

# ADR-015

## Title

Attachment diproses oleh Attachment Engine

---

Decision

Module lain tidak boleh upload file.

Semua upload dilakukan melalui.

```
AttachmentService
```

---

Consequences

Storage menjadi terpusat.

---

# ADR-016

## Title

Resource tidak boleh melakukan Query

---

Decision

Semua query dilakukan pada Service.

Resource hanya serialisasi.

---

Consequences

Menghindari N+1 Query.

---

# ADR-017

## Title

baseQuery menjadi sumber seluruh Query

---

Decision

Seluruh eager loading berada pada.

```
baseQuery()
```

---

Consequences

Konsisten.

---

# ADR-018

## Title

Enum digunakan sebanyak mungkin

---

Decision

Status.

Role.

Permission.

Owner Type.

menggunakan Enum.

---

Consequences

Mengurangi string literal.

---

# ADR-019

## Title

Controller menggunakan HasMiddleware

---

Decision

Permission dicek pada Middleware.

Business Rule tetap di Service.

---

Consequences

Tanggung jawab lebih jelas.

---

# ADR-020

## Title

Storage Development dan Production dipisahkan

---

Decision

Development.

```
MinIO
```

Production.

```
Cloudflare R2
```

---

Consequences

Migrasi storage cukup mengubah ENV.

Tidak mengubah database.

---

# ADR-021

## Title

Project menggunakan Modular Documentation

---

Decision

Dokumentasi dipisahkan menjadi.

- Architecture
- Database
- Workflow
- Module
- API
- Coding Convention
- Decision Log

---

Consequences

Lebih mudah dipelihara.

Tidak ada dokumen raksasa.

---

# Cara Menambahkan Decision Baru

Gunakan nomor berikutnya.

Contoh.

```
ADR-022
```

Jangan mengubah nomor lama.

Apabila keputusan diganti.

Gunakan.

```
Status

Superseded
```

Lalu buat ADR baru yang menjelaskan perubahan.

---

# Ringkasan

Decision Log merupakan dokumen historis project.

Dokumen ini menjelaskan **mengapa** sebuah keputusan diambil, bukan hanya **apa** yang diimplementasikan.

Seluruh developer dan AI wajib membaca dokumen ini sebelum melakukan perubahan desain.