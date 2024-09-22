# Kominfo

- dikarenakan docker tidak berjalan seperti yang diinginkan sehingga program perlu dijalankan secara manual

PHP versi: 8.0.25

Database:

- Server Version: 10.4.27-MariaDB
- host: localhost
- port: 3306
- username: root
- password:
- database: kominfo

LANGKAH-LANGKAH:

1. download atau clone repository ini
2. masukkan repository yang telah didownload ke dalam folder xampp/htdocs
3. jalankan modul Apache & MySQL pada xampp
4. jalankan query kominfo.sql
5. API dapat diakses melalui dengan menggunakan request method yang tertera:
   - http://localhost/kominfo/api/products (GET, POST)
   - http://localhost/kominfo/api/products/{id} (GET, PUT, DELETE)
   - http://localhost/kominfo/api/orders (GET, POST)
   - http://localhost/kominfo/api/orders/{id} (GET, DELETE)
