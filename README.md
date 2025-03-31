Website fake giao diện shọp pe -> by Hoaily19

### Cài cái này đâu tiên cho tôi.
```bash
composer install
```
### Chuyển .env.example thành .env
```bash
copy .env.example .env
```
### Tạo database
```bash
php artisan migrate
```
### Tạo app_key
```bash
php artisan key:generate
```

### Cài thư viện tải hình ảnh
```bash
php artisan storage:link
```

### Chạy dự án laravel
```bash
php artisan serve
```
### Thư viện socialite 
```bash
composer require laravel/socialite
```