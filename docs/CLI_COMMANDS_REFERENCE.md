# Referencia Completa de Comandos CLI - Veltrion Framework PHP v1.0.0

- **Autor / Creador Original**: Maxwell Chacón
- **Licencia**: Apache 2.0 (Open Source)
- **Ejecutable**: `php cli <comando> [argumentos]`

---

## 📌 Índice de Comandos

1. [Comandos del Sistema y Diagnóstico](#1-sistema-y-diagnóstico)
2. [Comandos de Módulos y Generación RAD](#2-módulos-y-generación-rad)
3. [Comandos de Base de Datos y Migraciones](#3-base-de-datos-y-migraciones)
4. [Comandos de Agentes Autónomos de IA (Python)](#4-agentes-autónomos-python)
5. [Comandos de Inteligencia Artificial Local (Ollama SDK)](#5-inteligencia-artificial-local-ollama)
6. [Comandos de Pruebas Unitarias e Integradas](#6-pruebas-y-calidad)

---

## 1. Sistema y Diagnóstico

### `php cli doctor`
Realiza un chequeo exhaustivo del entorno de ejecución:
- Versión de PHP (>= 8.1)
- Extensiones de PHP cargadas (`pdo`, `pdo_sqlite`, `mbstring`, `xml`, `curl`)
- Entorno de ejecución de Python 3
- Estado de Composer
- Conexión a la Base de Datos PDO
- Estado del servidor local de IA Ollama (`http://localhost:11434`)

**Ejemplo:**
```bash
php cli doctor
```

### `php cli --version` / `php cli -v`
Muestra la versión actual del framework y los créditos del autor.

---

## 2. Módulos y Generación RAD

### `php cli make:crud <NombreModulo>`
Genera un módulo empresarial completo en **Clean Architecture**:
- Entidad de Dominio (`app/Domain/Entities/`)
- Interfaz de Repositorio (`app/Domain/Repositories/`)
- Implementación de Repositorio SQLite PDO (`app/Adapters/Repositories/`)
- Caso de Uso (`app/UseCases/`)
- Migración SQL DDL (`database/migrations/`)

**Ejemplo:**
```bash
php cli make:crud Invoice
```

---

## 3. Base de Datos y Migraciones

### `php cli db:migrate`
Ejecuta todas las migraciones SQL pendientes en la carpeta `database/migrations/`.

### `php cli db:status`
Muestra el listado de tablas existentes en la base de datos configurada y el conteo de registros por tabla.

### `php cli db:switch [driver]`
Migra el esquema DDL y los datos entre diferentes motores (SQLite, MySQL, PostgreSQL).

**Ejemplo:**
```bash
php cli db:switch postgresql
```

---

## 4. Agentes Autónomos Python

### `php cli agent list`
Lista los 10 agentes especializados disponibles y sus responsabilidades.

### `php cli agent run <agente|all>`
Ejecuta uno o todos los agentes de la suite multiagente:
- `qa`: Auditoría de pruebas unitarias, regresiones y aserciones.
- `security`: Escaneo SAST para detección de SQLi, contraseñas y secretos expuestos.
- `architecture`: Validación de Clean Architecture y aislamiento de capas.
- `database`: Inspección de esquema SQLite, tablas e integridad de migraciones.
- `dependencies`: Auditoría de paquetes en `composer.json` y `package.json`.
- `e2e`: Pruebas End-To-End sintéticas de la interfaz web HTTP.
- `performance`: Medición de tiempo de boot del CLI y consumo de memoria.
- `ux`: Auditoría de interfaz web, accesibilidad y estados.
- `business`: Evaluación de reglas de negocio en Casos de Uso.
- `docs`: Verificación de integridad de documentación Markdown.

### `php cli agent ci`
Ejecuta la suite multiagente en modo CI/CD Pipeline devolviendo códigos de salida estándar (0 = Éxito, >0 = Fallo) para integración con GitHub Actions.

**Ejemplo:**
```bash
php cli agent list
php cli agent run security
php cli agent run all
php cli agent ci
```

---

## 5. Inteligencia Artificial Local (Ollama)

### `php cli ai`
Despliega el menú interactivo con todas las opciones de asistencia inteligente.

### `php cli ai:status`
Verifica la disponibilidad del servidor Ollama en `http://localhost:11434` y lista los modelos descargados sin producir errores fatales.

### `php cli ai:models`
Consulta y lista los modelos de IA instalados localmente.

### `php cli ai:ask "<instrucción>"`
Realiza una consulta a la IA incluyendo el contexto seguro del proyecto (excluyendo secretos).

**Ejemplo:**
```bash
php cli ai:ask "¿Cómo implementar el patrón Repository con PDO?"
```

### `php cli ai:chat`
Abre una sesión de chat interactiva en la consola.

### `php cli ai:code <concepto>`
Analiza la arquitectura y genera una propuesta de código con confirmación previa (`[Y] Aplicar / [N] Cancelar`).

### `php cli ai:test <clase>`
Genera una suite de pruebas unitarias automatizadas para la clase especificada.

### `php cli ai:docs <tema>`
Genera documentación oficial en formato Markdown.

### `php cli ai:analyze`
Ejecuta una auditoría estática de principios SOLID y seguridad asistida por IA.

### `php cli ai:explain <ruta/archivo.php>`
Explica las responsabilidades, patrones y flujo de ejecución del archivo dado.

---

## 6. Pruebas y Calidad

### `php cli test`
Ejecuta la suite integrada de pruebas para verificar el funcionamiento de `UnitOfWork`, `CreateCustomerUseCase`, `ListCustomersUseCase` y la conectividad del motor de IA.
