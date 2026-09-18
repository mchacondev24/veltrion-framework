# 🌌 Veltrion PHP Framework

> **AI & Agent-Powered Enterprise PHP Ecosystem**  
> *Designed with Clean Architecture, Domain-Driven Design (DDD), Local AI (Ollama), Multi-Agent Python Automation, and Universal Multi-Platform Blueprints.*

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://www.php.net/)
[![Architecture](https://img.shields.io/badge/Architecture-Clean%20Architecture-brightgreen.svg)](ARCHITECTURE.md)
[![AI Engine](https://img.shields.io/badge/AI-Ollama%20%2F%20Local%20First-purple.svg)](https://ollama.com/)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](LICENSE)

---

## 📖 Visión General

**Veltrion** es un framework y ecosistema moderno de desarrollo de software para PHP que une:
1. **Clean Architecture y DDD**: Aislamiento estricto de Reglas de Negocio, Casos de Uso e Infraestructura (sin acoplamiento a frameworks HTTP o bases de datos).
2. **Inteligencia Artificial Local First**: Integración nativa con Ollama para generación de código, análisis de requerimientos e ingeniería de prompts con privacidad absoluta.
3. **Ecosistema Multi-Agente en Python**: Agentes autónomos para auditoría de arquitectura, seguridad (SAST/DAST), calidad de código y diseño.
4. **Universal Multi-Platform Template Engine**: Motor de generación de proyectos con soporte para **PHP Clean Arch**, **Angular**, **React**, **Android (Jetpack Compose)**, **MAUI (.NET/C#)** y **Flutter (Dart)**.

---

## 🏛️ Arquitectura

El diseño sigue estrictamente los principios de **Clean Architecture**:

```
       ┌───────────────────────────────────────────────┐
       │             PRESENTATION / UI                 │
       │    (React / Web / CLI / HTTP Controllers)     │
       └───────────────────────┬───────────────────────┘
                               │
       ┌───────────────────────▼───────────────────────┐
       │             APPLICATION / USE CASES           │
       │  (CreateCustomer, ProcessOrder, GenerateDoc)  │
       └───────────────────────┬───────────────────────┘
                               │
       ┌───────────────────────▼───────────────────────┐
       │                  DOMAIN                       │
       │   (Entities, Value Objects, Domain Events)    │
       └───────────────────────▲───────────────────────┘
                               │
       ┌───────────────────────┴───────────────────────┐
       │               INFRASTRUCTURE                  │
       │  (PDO Repositories, SQLite, Ollama, Python)   │
       └───────────────────────────────────────────────┘
```

- **Domain (`app/Domain`)**: Entidades puras, Value Objects e interfaces de repositorio sin dependencias externas.
- **Use Cases (`app/UseCases`)**: Orquestación de casos de uso de negocio con transacciones via `UnitOfWork`.
- **Adapters (`app/Adapters`)**: Controladores HTTP, adaptadores de persistencia (SQLite, MySQL) y clientes de IA.
- **Python Agents (`agents/`)**: Agentes autónomos que inspeccionan y garantizan la calidad del software.

---

## ⚡ Comandos CLI Principales

Veltrion incluye una consola CLI interactiva (`php cli`):

### 🩺 Diagnóstico y Sistema
```bash
# Diagnóstico completo del ecosistema (PHP, extensiones, Ollama, SQLite, Python)
php cli doctor

# Consultar documentación interactiva
php cli docs
```

### 🧠 Inteligencia Artificial y Agentes
```bash
# Consultar a la IA local
php cli ai:ask "Explica cómo crear un caso de uso con Clean Architecture"

# Analizar un archivo de código con IA
php cli ai:analyze app/Domain/Entities/Customer.php

# Ejecutar agentes autónomos de Python (architecture, security, qa, performance)
php cli agent run architecture
php cli agent run security
```

### 📦 Universal Project Template Engine
Genera proyectos completos basados en plantillas de arquitectura listas para producción:

```bash
# Listar todos los blueprints registrados
php cli template:list

# Inspeccionar las capacidades y feature packs de una plantilla
php cli template:show tpl-android-kotlin-v1

# Resolver la mejor plantilla automáticamente para una plataforma
php cli template:resolve flutter

# Generar un proyecto nuevo desde una plantilla
php cli template:create angular MyStoreApp
php cli template:create react DashboardApp
php cli template:create android MobileApp
```

### ⚙️ CI/CD y Calidad
```bash
# Ejecutar pipeline de CI/CD
php cli pipeline:run

# Crear y rastrear especificaciones de Features
php cli feature:create "Payment Gateway Integration"
```

---

## 🛠️ Requisitos Previos

- **PHP**: >= 8.1 (Recomendado PHP 8.2+)
- **Extensiones PHP**: `pdo`, `pdo_sqlite`, `mbstring`, `xml`, `curl`
- **Composer**: 2.x
- **Python**: 3.10+ (para agentes autónomos)
- *(Opcional)* **Ollama**: Para capacidades de IA 100% locales y privadas (`http://localhost:11434`)

---

## 🚀 Instalación Rápida

```bash
# 1. Clonar el repositorio
git clone https://github.com/maxchacon/veltrion-framework.git
cd veltrion-framework

# 2. Instalar dependencias con Composer
composer install

# 3. Verificar el estado del entorno
php cli doctor
```

---

## 👨‍💻 Autor y Créditos

Creado y desarrollado por **Maxwell Chacón**  
- Email: [ing.chacon.maxwell@gmail.com](mailto:ing.chacon.maxwell@gmail.com)
- Rol: Creador y Arquitecto Principal de Veltrion PHP Framework

---

## 📄 Licencia

Este proyecto está bajo la licencia **Apache License 2.0**. Consulta el archivo `LICENSE` para más detalles.
