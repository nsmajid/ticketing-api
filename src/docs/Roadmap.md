# Roadmap

> Dokumen ini berisi roadmap pengembangan Ticketing System.
>
> Roadmap menjadi acuan utama urutan implementasi fitur.
>
> Seluruh fitur baru harus ditambahkan ke dokumen ini sebelum mulai diimplementasikan.

---

# Status

| Icon | Arti |
|------|------|
| ✅ | Selesai |
| 🚧 | Sedang dikerjakan |
| 📋 | Direncanakan |
| 🔒 | Ditunda |
| ❌ | Dibatalkan |

---

# Milestone

## Milestone 1 - Foundation

Status

```
✅ Completed
```

### Scope

- Project Setup
- Docker
- Authentication
- User
- Role
- Permission
- Shared Module
- BaseService
- BaseFilter

---

## Deliverable

- Laravel 13
- Docker
- Authentication
- Permission
- Modular Structure

---

# Milestone 2 - Master Data

Status

```
✅ Completed
```

### Scope

- Application
- Application Feature
- Ticket Category
- Ticket Priority
- Ticket Status
- SLA Rule

---

## Deliverable

Master data dapat digunakan seluruh module.

---

# Milestone 3 - Ticket Core

Status

```
✅ Completed
```

### Scope

- Ticket CRUD
- Ticket Resource
- Ticket Filter
- Ticket Workflow
- Ticket Timeline

---

## Deliverable

Ticket sudah dapat dibuat dan dikelola.

---

# Milestone 4 - Attachment Engine

Status

```
🚧 In Progress
```

---

## Scope

### Attachment Upload

Status

```
✅
```

---

### Attachment Preview

Status

```
✅
```

---

### Attachment Download

Status

```
✅
```

---

### Markdown Strategy

Status

```
✅
```

---

### Attachment Parser

Status

```
✅
```

---

### Attachment Usage

Status

```
✅
```

---

### Attachment Resource

Status

```
🚧
```

---

### Ticket Description Integration

Status

```
📋
```

---

### Comment Integration

Status

```
🚧
```

---

### Progress Integration

Status

```
📋
```

---

### Review Integration

Status

```
📋
```

---

### Resolve Integration

Status

```
📋
```

---

### Close Integration

Status

```
📋
```

---

### Scheduler Cleanup

Status

```
📋
```

---

## Deliverable

Attachment Engine reusable untuk seluruh module.

---

# Milestone 5 - Comment Engine

Status

```
🚧 In Progress
```

---

## Scope

- CRUD Comment
- Permission
- Attachment
- Markdown
- Resource

---

## Status

| Fitur | Status |
|---------|--------|
| Design | ✅ |
| Migration | ✅ |
| Model | ✅ |
| Permission | ✅ |
| Service | ✅ |
| Controller | ✅ |
| Middleware | ✅ |
| Resource | 🚧 |
| Attachment Integration | 🚧 |
| Testing | 📋 |

---

# Milestone 6 - Progress Engine

Status

```
📋 Planned
```

---

## Scope

- Progress
- Markdown
- Attachment
- Timeline

---

# Milestone 7 - Review Engine

Status

```
📋 Planned
```

---

## Scope

- Review
- Timeline
- Permission

---

# Milestone 8 - Resolve Engine

Status

```
📋 Planned
```

---

# Scope

- Resolve
- Timeline
- Attachment

---

# Milestone 9 - Close Engine

Status

```
📋 Planned
```

---

# Scope

- Close Ticket
- Acceptance
- Timeline

---

# Milestone 10 - Notification

Status

```
📋 Planned
```

---

## Scope

- Email
- In App
- Queue
- Template

---

# Milestone 11 - Internal Notes

Status

```
📋 Planned
```

---

## Scope

- Markdown
- Attachment
- Vendor Only

---

# Milestone 12 - Dashboard

Status

```
📋 Planned
```

---

## Scope

- Summary
- SLA
- Statistics
- Charts

---

# Milestone 13 - Reporting

Status

```
📋 Planned
```

---

## Scope

- Export Excel
- Export PDF
- SLA Report

---

# Milestone 14 - Knowledge Base

Status

```
📋 Planned
```

---

## Scope

- Article
- Markdown
- Attachment
- Search

---

# Milestone 15 - Production Ready

Status

```
📋 Planned
```

---

## Scope

- Queue
- Scheduler
- Logging
- Monitoring
- Backup
- Security
- Performance

---

# Checklist Pengembangan

## Foundation

- [x] Docker
- [x] Authentication
- [x] Permission
- [x] Shared Module

---

## Master Data

- [x] Application
- [x] Feature
- [x] Category
- [x] Priority
- [x] Status
- [x] SLA

---

## Ticket

- [x] CRUD
- [x] Workflow
- [x] Timeline
- [x] Filter

---

## Attachment

- [x] Upload
- [x] Download
- [x] Preview
- [x] Parser
- [x] Usage
- [x] Markdown
- [ ] Scheduler
- [ ] Cleanup

---

## Comment

- [x] CRUD
- [x] Permission
- [x] Middleware
- [x] Service
- [ ] Attachment Resource
- [ ] Testing

---

## Future

- [ ] Progress
- [ ] Review
- [ ] Resolve
- [ ] Close
- [ ] Notification
- [ ] Internal Notes
- [ ] Dashboard
- [ ] Reporting
- [ ] Knowledge Base

---

# Dependency Roadmap

```text
Foundation
      │
      ▼
Master Data
      │
      ▼
Ticket Core
      │
      ▼
Attachment Engine
      │
      ▼
Comment Engine
      │
      ▼
Progress
      │
      ▼
Review
      │
      ▼
Resolve
      │
      ▼
Close
      │
      ▼
Notification
      │
      ▼
Dashboard
      │
      ▼
Reporting
      │
      ▼
Production Ready
```

---

# Definition of Done

Sebuah milestone dianggap selesai apabila memenuhi seluruh kriteria berikut:

- Migration selesai.
- Model selesai.
- Seeder selesai (jika diperlukan).
- Request selesai.
- Resource selesai.
- Service selesai.
- Controller selesai.
- Middleware selesai.
- Route selesai.
- API Testing selesai.
- Dokumentasi diperbarui.
- Roadmap diperbarui.
- Decision Log diperbarui (jika terdapat perubahan desain).

---

# Catatan

Roadmap bersifat dinamis.

Perubahan urutan implementasi diperbolehkan selama:

- Tidak melanggar Architecture.md.
- Tidak bertentangan dengan Decision Log.md.
- Dependency antar module tetap terjaga.