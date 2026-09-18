# Python Multi-Agent System - Veltrion Framework PHP

- **Autor Original**: Maxwell Chacón
- **Licencia**: Apache 2.0 (Open Source)

## Visión General

Veltrion Framework PHP integra una suite multiagente autónoma en Python coordinada por un `AgentOrchestrator` central. Cada agente es especializado y produce evidencia trazable y reproducible en formato JSON y Markdown dentro de `storage/reports/`.

```
                    ┌──────────────────┐
                    │    Developer     │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │     php cli      │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │ AgentOrchestrator│
                    └────────┬─────────┘
                             │
       ┌─────────────────────┼─────────────────────┐
       ▼          ▼          ▼          ▼          ▼
      QA       Security  Architecture Database  Performance ...
```

## Agentes Especializados Disponibles

| Agente | Categoría | Descripción |
|---|---|---|
| **QA** | Testing & Quality | Inspecciona pruebas unitarias, cobertura, regresiones y aserciones. |
| **Security** | SAST & Secret Detection | Análisis estático para SQLi, contraseñas planas y secretos expuestos. |
| **Architecture** | Clean Architecture | Verifica acoplamiento, principios SOLID e independencia de capas. |
| **Database** | DB Integrity & Migrations | Valida esquema de SQLite, migraciones y consistencia DDL. |
| **Dependencies** | Composer & NPM Audit | Analiza paquetes requeridos en `composer.json` y `package.json`. |
| **E2E** | Synthetic HTTP Testing | Ejecuta pruebas End-To-End sintéticas de la interfaz web HTTP. |
| **Performance** | Benchmarks & Memory | Mide tiempo de boot del CLI y optimización de memoria PHP. |
| **UX** | Accessibility & States | Revisa estados vacíos, formularios y accesibilidad web. |
| **Business** | Domain Validation | Evalúa consistencia de reglas de negocio en UseCases. |
| **Docs** | Documentation Sync | Verifica la completitud de `README.md`, `FEATURES.md` y guías. |

## Comandos CLI de Agentes

```bash
# Listar todos los agentes registrados
php cli agent list

# Ejecutar un agente individual
php cli agent run security
php cli agent run qa
php cli agent run architecture

# Ejecutar la suite completa de 10 agentes
php cli agent run all

# Ejecutar en modo CI/CD Pipeline
php cli agent ci
```

## Estructura de Reportes

Toda ejecución escribe los hallazgos en:
- `storage/reports/summary.json`: Reporte unificado en JSON
- `storage/reports/summary.md`: Reporte formateado en Markdown para revisiones de código y CI/CD
