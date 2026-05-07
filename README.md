# Campaign Management System - Laravel

A robust **Laravel** application for creating and managing scheduled email/notification campaigns with advanced backend features.

![Project Banner](https://via.placeholder.com/800x400?text=Campaign+Management+System)

## ✨ Giới thiệu

Dự án là một **web application Laravel** cho phép Admin tạo chiến dịch (campaign), chọn danh sách người nhận, lên lịch gửi tự động theo thời gian (`send_at`). Hệ thống xử lý queue, scheduler, cache, báo cáo và tương tác AJAX.

Đây là dự án thực hành chuyên sâu Laravel, tập trung vào kiến trúc sạch và các công cụ production-ready.

## 🚀 Tính năng nổi bật

- Tạo Campaign + chọn subscribers (AJAX search)
- Lên lịch gửi tự động (`send_at`)
- Queue System xử lý gửi mail/notification theo từng recipient
- Scheduler tự động quét campaign đến hạn
- Admin Dashboard với Cache
- Reports nâng cao (Query Builder)
- Mark as read thông báo (AJAX)
- Role-based Access Control (Admin & User)

## 🛠️ Tech Stack

- **Backend**: PHP 8.2+, **Laravel** (Latest)
- **Architecture**: MVC + **Repository Pattern**
- **Database**: MySQL
- **Queue**: Laravel Queue (Database driver)
- **Containerization**: **Docker** & **Docker Compose**
- **Frontend**: Blade, jQuery, AJAX
- **Others**: Eloquent ORM, Query Builder, Cache, Scheduler, Database Transactions, Form Request Validation

## 📋 Prerequisites

- Docker & Docker Compose
- Git

## 🚀 Installation & Running (Using Docker)

### 1. Clone repository
```bash
git clone https://github.com/NguyenDucDan090904/Project-Spec.git
cd Project-Spec
```
### 2. Copy environment file
```bash
cp .env.example .env
```
### 3. Build and run Docker containers
```bash
docker-compose up --build -d
```
### 4. Install dependencies & Setup Laravel
```bash
# Cài đặt Composer packages
docker-compose exec app composer install

# Generate application key
docker-compose exec app php artisan key:generate

# Chạy migration và seed dữ liệu
docker-compose exec app php artisan migrate --seed

# Tạo symbolic link cho storage
docker-compose exec app php artisan storage:link
```
### 5. Run Queue Worker & Scheduler
Mở 2 terminal riêng biệt:

Terminal 1 - Queue Worker:
```bash
docker-compose exec app php artisan queue:work
```
Terminal 2 - Scheduler:
```bash
docker-compose exec app php artisan schedule:work
```
Ứng dụng sẽ chạy tại: 🌐 http://localhost:8000
