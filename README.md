# 🎵 OnlyDance — Backend API (Symfony)

The **OnlyDance Symfony Backend** provides the server-side logic and data management for the OnlyDance AR ecosystem. It handles user-created dances, authentication, data storage, and acts as the central API layer between the Unity AR client, the Vue web frontend, and the database.

> 🌐 Live: [onlydance.at](https://onlydance.at)

---

## 🚀 Features

### 💃 Dance Management
- Create, update, delete, and fetch user-created dances
- Store individual dance steps, metadata, and choreography sequences
- Full input validation for all dance-related data

### 🌟 Online Dances
- Predefined, classic choreographies created by OnlyDance
- Read-only endpoints optimized for mobile AR and web clients

### 🔐 Authentication & User Management
- User registration and login
- Secure password hashing
- Token-based authentication via **JWT**

### 🔌 RESTful API
- Clean REST structure with consistent JSON request/response format
- CORS-enabled for Unity AR and Vue web clients
- Designed for scalability and low latency

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Framework | Symfony (PHP) |
| Server | FrankenPHP |
| Containerization | Docker / Docker Compose |
| Database | Doctrine ORM |
| Authentication | JWT |
| Templating | Twig |
| CI/CD | GitHub Actions |

---

## 📦 Project Structure

```
OnlyDance-AR-Symfony/
├── src/                   # Application source (Controllers, Entities, Services)
├── config/                # Symfony configuration
├── migrations/            # Doctrine database migrations
├── templates/             # Twig templates
├── public/                # Web root
├── frankenphp/            # FrankenPHP server config
├── .github/workflows/     # GitHub Actions CI/CD
├── compose.yaml           # Docker Compose (base)
├── compose.override.yaml  # Docker Compose (dev)
├── compose.prod.yaml      # Docker Compose (production)
├── Dockerfile
└── .env / .env.dev / .env.prod
```

---

## 🚀 Getting Started

### Prerequisites
- Docker & Docker Compose

### Development Setup

```bash
git clone https://github.com/WeidenauerErik/OnlyDance-AR-Symfony.git
cd OnlyDance-AR-Symfony

# Start development environment
docker compose up -d

# Run database migrations
docker compose exec php bin/console doctrine:migrations:migrate
```

The API will be available at `https://localhost`.

### Production Deployment

```bash
docker compose -f compose.yaml -f compose.prod.yaml up -d
```

---

## 🔗 Related Repositories

| Repository | Description |
|---|---|
| [OnlyDance-AR-Unity](https://github.com/WeidenauerErik/OnlyDance-AR-Unity) | AR mobile client (Unity) |
| [OnlyDance-WEB-Vue](https://github.com/WeidenauerErik/OnlyDance-WEB-Vue) | Web frontend (Vue 3) |

---

## ✨ Author

**Erik Weidenauer**

---

## 📄 License

[MIT](LICENSE)
