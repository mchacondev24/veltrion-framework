# Veltrion CI/CD Pipeline & Release Engineering System

## Descripción General

El **Veltrion CI/CD & Release Engineering System** es el motor automatizado que conecta el Framework PHP, los Agentes de IA Python, el Context Engine, el Orchestrator y el Feature Engine con repositorios GitHub / GitLab para automatizar la integración continua, evaluación de Quality Gates y generación de artefactos seguros de release.

---

## Arquitectura de CI/CD & Quality Gates

```
+-----------------------------------------------------------------------------------+
|                            VELTRION CI/CD PIPELINE                                |
+-----------------------------------------------------------------------------------+
  |
  +---> 1. CHECKOUT & GIT (GitManager: Branch, Commit, Changed Files)
  |
  +---> 2. LINT & SYNTAX (php -l bootstrap/app.php & Static Validation)
  |
  +---> 3. UNIT & INTEGRATION TESTS (Suite de pruebas integradas)
  |
  +---> 4. SECURITY AUDIT (SecurityAgent SAST & Critical Vulnerability Gate)
  |
  +---> 5. REQUIREMENT TRACEABILITY (FeatureEngine RTM Discovery)
  |
  +---> 6. QUALITY GATES (Build, Tests, Security, Traceability, Docs, Artifact)
  |
  +---> 7. RELEASE PACKAGING & SHA-256 (ArtifactManager: build.json, package.zip)
  |
  +---> 8. ARTIFACT VERIFICATION (ReleaseVerifier: Cryptographic Checksum & Secrets Filter)
```

---

## Comandos CLI Disponibles

### 1. Ejecución del Pipeline de CI/CD
```bash
# Ejecución estándar del pipeline
php cli ci:run

# Especificando perfiles de ejecución
php cli ci:run --profile quick
php cli ci:run --profile standard
php cli ci:run --profile full
php cli ci:run --profile release

# Modo Dry-Run (simulación sin modificar archivos)
php cli ci:run --dry-run
```

### 2. Estado y Reportes
```bash
# Consultar estado del último pipeline y Quality Gates
php cli ci:status
```

### 3. Generación de Configuración para CI Providers
```bash
# Generar workflow de GitHub Actions (.github/workflows/ci.yml)
php cli ci:init github

# Generar pipeline de GitLab CI (.gitlab-ci.yml)
php cli ci:init gitlab
```

### 4. Release Engineering & Auditorías
```bash
# Verificar Readiness y Quality Gates para Release Candidate
php cli release:check

# Preparar Release Notes y metadatos de versión
php cli release:prepare

# Construir paquete de despliegue .zip/.tar con checksum SHA-256
php cli release:package

# Verificación de integridad criptográfica de artefactos
php cli release:verify [path_to_zip]
```

---

## Quality Gates Implementados

| Gate | Criterio de Aprobación |
|---|---|
| **BuildGate** | Sintaxis PHP correcta en archivos principales y arranque sin errores |
| **TestsGate** | Execution exitosa de todas las pruebas unitarias e integradas |
| **SecurityGate** | Cero (0) vulnerabilidades de severidad CRÍTICA reportadas por SecurityAgent |
| **TraceabilityGate** | Matriz RTM generada y mapeo de Requisitos -> Código -> Tests confirmado |
| **DocumentationGate** | Presencia y consistencia de README.md, FEATURES.md y guías en `docs/` |
| **ArtifactGate** | Artefacto empaquetado sin archivos `.env`, `.git` o llaves privadas, con checksum SHA-256 válido |

---

## Verificación de Artefactos de Release

El comando `php cli release:verify` realiza las siguientes comprobaciones de seguridad e integridad:

1. **Cryptographic Checksum**: Compara el hash SHA-256 del paquete contra `package.zip.sha256`.
2. **Required Files**: Confirma la inclusión de `composer.json`, `cli`, `bootstrap/app.php`, etc.
3. **Secrets Exclusion**: Verifica la **ausencia total** de `.env`, `.git`, `.pem`, `.key`, y credenciales.
4. **Metadata Alignment**: Compara la información con `storage/artifacts/build.json`.
