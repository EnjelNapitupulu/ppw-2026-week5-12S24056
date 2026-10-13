# Bukti Audit SQL: Eager Loading

Halaman: `GET /buku` (`Buku::with('kategori')->latest()->paginate(10)`)

```
[SQL AUDIT] select count(*) as aggregate from "bukus"
[SQL AUDIT] select * from "bukus" order by "created_at" desc limit 10 offset 0
[SQL AUDIT] select * from "kategoris" where "kategoris"."id" in (1, 2, 5)
```

Total 3 kueri dan tidak bertambah seiring jumlah baris.
Query `sessions` adalah bawaan Laravel (SESSION_DRIVER=database).