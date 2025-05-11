# 🛍️ TshirtsEcomShop

**TshirtsEcomShop** is a clean and functional e-commerce website for selling t-shirts and hoodies. The project is built with a Laravel backend, PostgreSQL as a database and a simple frontend using HTML5, CSS, and vanilla JavaScript. It provides a smooth shopping experience for users and an admin panel for managing products.

---

## 🚀 Features

- Product catalog with filtering and search
- Product detail pages with images and descriptions
- Shopping cart and checkout functionality
- Admin dashboard for managing products
- User registration and login system

---

## 🛠️ Tech Stack

- **Backend:** Laravel (PHP)
- **Frontend:** HTML5, CSS3, JavaScript
- **Database:** PostgreSQL
- **Templating:** Blade (Laravel)

---

<!---
## 📁 Project Structure

```
├── app/                  # Application core (Models, Controllers, etc.)
├── public/               # Publicly accessible assets (HTML, JS, CSS)
├── resources/            # Blade templates and frontend assets
├── routes/               # Application routes
├── database/             # Migrations and seeders
├── .env.example          # Environment configuration template
├── composer.json         # PHP dependencies
└── ...
```

---
-->



## Installation
Follow these steps to set up the project locally:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/De1ik/TshirtsEcomShop.git
   cd TshirtsEcomShop
   cd back-laravel
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Create the `.env` file**


4. **Configure the `.env` file:**
   Update the `.env` file with your PostgreSQL database details:
   ```bash
   APP_KEY=
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=<YOUR_DB_NAME>
   DB_USERNAME=<YOUR_DB_USERNAME>
   DB_PASSWORD=<YOUR_DB_PASSWORD>
   ```

5. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations:**
   ```bash
   php artisan migrate
   ```

7. **Seed the database:**
   ```bash
   php artisan db:seed
   ```

8. **Link the storage directory:**
   ```bash
   php artisan storage:link
   ```

9. **Start the development server:**
   ```bash
   php artisan serve
   ```

10. **Access the application:**
    Visit [http://localhost:8000](http://localhost:8000) in your browser.

---

## 🧑‍💻 Author

Made with ❤️ by:
1. [De1ik](https://github.com/De1ik)
2. [KovalenkoDima236961](https://github.com/KovalenkoDima236961)

<!---
---

## 📄 License

This project is licensed under the [MIT License](LICENSE).
-->
