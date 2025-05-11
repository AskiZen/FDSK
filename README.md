# FDSK – DSK Secure File Server

**FDSK** (DSK Secure File Server) — це захищений веб-сервер для локального зберігання та обміну файлами з розмежуванням прав доступу. Проєкт розроблений на PHP і призначений для використання у внутрішніх мережах, де важлива простота, безпека та контроль доступу.

## 🔐 Можливості

* **Ролі доступу:**

  * **Користувач** (пароль: `12345`):

    * Перегляд локальних файлів
    * Завантаження файлів
    * Вихід з облікового запису
  * **Адміністратор** (пароль: `admin`):

    * Усі можливості користувача
    * Завантаження нових файлів
    * Видалення існуючих файлів
    * Зміна паролів користувачів
    * Перегляд та керування анонімними формами

* **Анонімна передача файлів:**

  * Можливість надсилати файли без авторизації через `anon_form.php`
  * Перегляд усіх надісланих анонімних форм у `anon_list.php`

* **Безпека:**

  * Система фільтрації файлів (`global_filter.php`)
  * Блокування IP-адрес та користувачів (`banned.php`, `banned.txt`)
  * Сесії та базова авторизація без використання бази даних

## 📂 Структура

* `index.php` — сторінка входу
* `upload.php` — завантаження файлів (тільки для адміністратора)
* `download.php` — завантаження файлів
* `admin_panel.php` — адмін-панель
* `manage_users.php` — керування обліковими записами
* `anon_form.php` — форма для анонімного завантаження
* `anon_list.php` — список анонімних завантажень
* `banned.php` / `banned.txt` — система блокування
* `global_filter.php` — фільтрація потенційно шкідливих файлів
* `style.css` — стилі інтерфейсу
* `bg.js` — візуальні ефекти (фон та анімації)

## ⚙️ Встановлення

1. **Вимоги:**

   * PHP 7.0 або вище
   * Веб-сервер (Apache, Nginx тощо)
   * Внутрішня або локальна мережа (рекомендовано)

2. **Кроки:**

   * Склонуйте репозиторій:

     ```bash
     git clone https://github.com/Askisan/FDSK.git
     ```
   * Перенесіть файли до веб-каталогу вашого сервера
   * Надайте права на запис/читання, якщо потрібно

3. **Налаштування:**

   * За потреби змініть паролі у відповідних файлах
   * Додайте або змініть IP-адреси в `banned.txt`
   * Налаштуйте правила фільтрації в `global_filter.php`

## 🧪 Як користуватися

* Перейдіть за адресою (наприклад: `http://localhost/FDSK/`)
* Увійдіть з обліковими даними:

  * Користувач: `user` / Пароль: `12345`
  * Адмін: `admin` / Пароль: `admin`
* Користуйтеся можливостями відповідно до вашої ролі

## 📄 Ліцензія

Цей проєкт поширюється за ліцензією [GNU GPL-3.0](https://www.gnu.org/licenses/gpl-3.0.html). Ви можете вільно використовувати, змінювати та розповсюджувати програму відповідно до умов ліцензії.

---

# FDSK – DSK Secure File Server

**FDSK** (DSK Secure File Server) is a secure local web server for storing and managing files with role-based access control. This PHP-based project is designed for internal or private networks where simplicity, safety, and access control are required.

## 🔐 Features

* **Access Roles:**

  * **User** (password: `12345`):

    * View available files
    * Download files
    * Log out
  * **Administrator** (password: `admin`):

    * All user permissions
    * Upload new files
    * Delete existing files
    * Change user and admin passwords
    * View and manage anonymous submissions

* **Anonymous Uploads:**

  * Upload files without logging in via `anon_form.php`
  * View anonymous uploads via `anon_list.php`

* **Security:**

  * File filtering via `global_filter.php`
  * IP/user banning system (`banned.php`, `banned.txt`)
  * Session handling and simple password authentication
  * No database required

## 📂 Project Structure

* `index.php` — login screen
* `upload.php` — upload files (admin only)
* `download.php` — file downloads
* `admin_panel.php` — admin dashboard
* `manage_users.php` — user management
* `anon_form.php` — anonymous upload form
* `anon_list.php` — list of anonymous files
* `banned.php` / `banned.txt` — banned IP list and logic
* `global_filter.php` — file type filtering
* `style.css` — UI styling
* `bg.js` — visual effects for background and transitions

## ⚙️ Installation

1. **Requirements:**

   * PHP 7.0 or newer
   * Web server (e.g., Apache, Nginx)
   * Local or internal network (recommended)

2. **Steps:**

   * Clone the repository:

     ```bash
     git clone https://github.com/Askisan/FDSK.git
     ```
   * Move the contents to your web server’s root directory
   * Ensure proper read/write permissions are set

3. **Configuration:**

   * Update login credentials if needed
   * Edit `banned.txt` to block specific IP addresses
   * Adjust file upload rules in `global_filter.php`

## 🧪 Usage

* Open your browser and go to `http://localhost/FDSK/` (or your server address)
* Log in using the provided credentials:

  * User: `user` / Password: `12345`
  * Admin: `admin` / Password: `admin`
* Use the interface according to your role to manage or access files

## 📄 License

This project is licensed under the [GNU GPL-3.0](https://www.gnu.org/licenses/gpl-3.0.html). You are free to use, modify, and distribute the software in compliance with the license terms.
