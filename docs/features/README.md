# Feature Engineering & Requirements Traceability System - Veltrion PHP

- **Autor Original**: Maxwell Chacón
- **Licencia**: Apache 2.0 (Open Source)

## Visión General

El **Sistema de Ingeniería de Feature y Trazabilidad de Requisitos** proporciona visibilidad end-to-end sobre cada funcionalidad desarrollada en el ecosistema Veltrion Framework PHP. Asegura la relación directa entre:

```
IDEA → REQUIREMENTS → FEATURE → PLAN → ARCHITECTURE → CODE → TESTS → SECURITY → E2E → DOCUMENTATION → RELEASE
```

## Formato de Identificadores

- **Features**: `FEAT-YYYY-NNNN` (ej. `FEAT-2026-0001`)
- **Requisitos**: `REQ-0001`, `REQ-0002` (Tipos: `FUNCTIONAL`, `NON_FUNCTIONAL`, `BUSINESS`, `SECURITY`, `PERFORMANCE`, `UX`, `TECHNICAL`)
- **Bugs**: `BUG-YYYYMMDD-HHMMSS`

## Estructura de Directorios de Feature (`FEATURES/`)

```
FEATURES/
└── FEAT-2026-0001-gestion-clientes/
    ├── FEATURE.md         # Metadatos principales, descripción y dependencias
    ├── REQUIREMENTS.md    # Lista formal de requisitos estructurados
    ├── ACCEPTANCE.md      # Criterios de aceptación (Gherkin / BDD)
    ├── PLAN.md            # Plan técnico de arquitectura y ejecución
    └── TRACEABILITY.md    # Matriz de trazabilidad de requisitos (RTM)
```

## Comandos CLI

```bash
# Crear una nueva Feature
php cli feature:create "Gestión de Clientes"

# Ver detalles y trazabilidad de una Feature
php cli feature:show FEAT-2026-0001

# Listar todas las features
php cli feature:list
php cli feature:list --status PLANNED

# Ejecutar el workflow orquestado autónomo de la Feature
php cli feature:run FEAT-2026-0001

# Generar Plan Técnico
php cli feature:plan FEAT-2026-0001

# Análisis de Impacto de Cambios (Change Impact Analysis)
php cli feature:impact FEAT-2026-0001

# Comprobar Definition of Done (DoD) & Definition of Ready (DoR)
php cli feature:check FEAT-2026-0001

# Matriz de Trazabilidad Global
php cli trace FEAT-2026-0001

# Comprobar estado de Release Candidate
php cli release:check

# Asistencia de IA en Requisitos, Escenarios de Aceptación y Bugs
php cli ai:requirements FEAT-2026-0001
php cli ai:acceptance FEAT-2026-0001
php cli ai:bug "El cliente no se guarda en SQLite"
```
