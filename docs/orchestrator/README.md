# AI Orchestrator & Autonomous Development Pipeline - Veltrion Framework PHP

- **Autor Original**: Maxwell Chacón
- **Licencia**: Apache 2.0 (Open Source)

## Visión General

El **AI Orchestrator** es el cerebro de orquestación autónoma de desarrollo en Veltrion PHP. Se encarga de transformar solicitudes de usuarios o contratos de especificación en formato Markdown (`FEATURE.md`) en un ciclo de vida completo de desarrollo auditado y verificado.

```
FEATURE / USER REQUEST
        ↓
   UNDERSTAND (FeatureAnalyzer)
        ↓
  CONTEXT ENGINE (Project Indexing & Security Exclusions)
        ↓
     PLANNER (Technical Plan Generation)
        ↓
   RISK ENGINE (Severity & Approval Policy)
        ↓
 CODE GENERATION (Clean Architecture & RAD)
        ↓
    DIFF REVIEW (Visual Delta & Change Applying)
        ↓
 AUTOMATED TESTS (Unit & Integration Test Loop - Max 3 Retries)
        ↓
 SECURITY GATE (Python SAST Security Agent)
        ↓
    E2E GATE (Synthetic HTTP E2E Agent)
        ↓
 DOCUMENTATION (Markdown Sync & Changelog)
        ↓
 FINAL REVIEW (Unified Report in storage/reports/workflows/<WF-ID>/)
```

## Máquina de Estados del Workflow

El orquestador transiciona explícitamente entre los siguientes estados:
- `CREATED` → `ANALYZING` → `PLANNING` → `WAITING_APPROVAL` → `GENERATING` → `APPLYING` → `TESTING` → `SECURITY_CHECK` → `E2E_CHECK` → `DOCUMENTING` → `REVIEWING` → `COMPLETED` (o `FAILED` / `CANCELLED`)

## Comandos CLI

```bash
# Modo interactivo de orquestación
php cli orchestrate

# Orquestar solicitud por argumento
php cli orchestrate "crear módulo de inventario"

# Ejecutar contrato de Feature en Markdown
php cli feature run FEATURES/cliente.md

# Ver estado de los workflows ejecutados
php cli orchestrate:status

# Reanudar o cancelar un workflow
php cli orchestrate:resume WF-20260918-0001
php cli orchestrate:cancel WF-20260918-0001

# Pipelines especializados
php cli orchestrate:bugfix "error 500 al guardar cliente"
php cli orchestrate:refactor "separar capas de infraestructura"
php cli orchestrate:release
```

## Evaluación de Riesgo Técnico (`RiskEngine`)

Clasifica automáticamente el impacto técnico antes de ejecutar cambios:
- **`LOW`**: Cambios en capa de presentación o nuevos archivos aislados.
- **`MEDIUM`**: Modificaciones en UseCases o Repositorios.
- **`HIGH` / `CRITICAL`**: Cambios en esquemas SQL de base de datos, autenticación, seguridad o eliminación de archivos. Requiere confirmación explícita.
