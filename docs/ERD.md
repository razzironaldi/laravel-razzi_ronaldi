# ERD

Tabel users:
- id
- name
- email
- password
- role
- created_at
- updated_at

Tabel products:
- id
- name
- sku
- category
- price
- stock
- is_active
- description
- created_at
- updated_at

Tabel personal_access_tokens:
- id
- tokenable_type
- tokenable_id
- name
- token
- abilities
- last_used_at
- expires_at
- created_at
- updated_at

Relasi:
users memiliki banyak personal_access_tokens.
products berdiri sebagai modul utama CRUD.