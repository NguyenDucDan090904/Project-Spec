# Campaign Management System - Laravel

Một ứng dụng web Laravel mini chuyên tạo và quản lý **chiến dịch gửi thông báo / email** theo lịch trình (scheduled campaigns).

## ✨ Giới thiệu

Dự án mô phỏng hệ thống gửi campaign (thông báo, email marketing) với đầy đủ các tính năng thực tế: tạo campaign, lên lịch gửi, xử lý queue, scheduler, cache, báo cáo và AJAX tương tác.

Đây là dự án thực hành chuyên sâu Laravel, tập trung vào các khái niệm quan trọng trong backend development.

## 🚀 Tính năng chính

- **Quản lý Campaign**: Tạo chiến dịch, chọn người nhận, đặt lịch gửi (`send_at`)
- **Queue & Job**: Gửi email/notification theo từng recipient bằng Laravel Queue
- **Scheduler**: Cron job tự động quét và dispatch campaign đến hạn
- **Dashboard**: Thống kê nhanh với Cache
- **Reports**: Báo cáo chi tiết sử dụng Query Builder (Join, Group By, Aggregation)
- **Notification System**: Người dùng xem và đánh dấu đã đọc thông báo
- **Role-based Access Control**: Phân quyền rõ ràng giữa Admin và User

## 🛠️ Tech Stack

- **Framework**: Laravel (phiên bản mới nhất)
- **Backend**: PHP 8.2+
- **Database**: MySQL
- **Queue**: Laravel Queue (Database driver)
- **Frontend**: Blade + jQuery + AJAX
- **Authentication**: Laravel Breeze / Laravel UI
- **Others**: Eloquent ORM, Form Request, Database Transaction, Cache, Scheduler

## 📋 Yêu cầu hệ thống

- PHP 8.2+
- Composer
- MySQL
- Node.js & npm (để compile assets)

## 🛠️ Cài đặt & Chạy dự án

### 1. Clone repository
```bash
git clone https://github.com/NguyenDucDan090904/[tên-repo].git
cd [tên-repo]
