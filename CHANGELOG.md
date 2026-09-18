# CHANGELOG.md - Veltrion Framework

All notable changes to Veltrion PHP Framework will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.0-commercial-packaging] - 2026-09-18

### Added
- **Commercial Packaging Engine**: `PackagingEngine` producing protected commercial distributions (`php cli package:build`) with package manifests and SHA-256 checksums.
- **Code Protection Engine**: `NativeProtectionProvider` featuring source minimization, guard header injection, and `ExternalEncoderProvider` adapter for ionCube/phpGuard.
- **Licensing Engine & Digital Signatures**: Offline-first cryptographic licensing (`Ed25519`/`Sodium`/`HMAC`), feature licensing, SemVer version restrictions, trial modes, and expiration validation.
- **Machine Identity & Binding**: `MachineIdentityProvider` generating unique 16-character hardware fingerprints (`php cli license:machine`) for machine-bound licensing.
- **Integrity & Tamper Detection Engine**: `IntegrityEngine` generating SHA-256 code integrity manifests and detecting runtime tampering (`TAMPER_DETECTED`).
- **Windows Packaging & Installer Subsystem**: `WindowsPackagingEngine` (`php cli windows:build`) generating Windows distribution bundles, batch launcher wrappers, and Inno Setup installer scripts (`.iss`).
- **Commercial Release Pipeline**: `CommercialReleaseManager` (`php cli release:commercial`) orchestrating Quality Gates, Code Protection, License generation, and Release Reports.

## [1.3.0-cicd-release] - 2026-09-18

### Added
- **CI/CD Pipeline Engine**: Automated pipeline runner (`php cli ci:run`) supporting `quick`, `standard`, `full`, and `release` profiles with dry-run capabilities.
- **Quality Gates Engine**: 6 strict quality gates evaluating Build, Tests, Security SAST, Traceability RTM, Documentation, and Artifact integrity.
- **Release Engineering & Packaging**: `ArtifactManager` building deployment packages (`.zip`/`.tar`) in `storage/artifacts/` with SHA-256 checksums and `build.json` metadata.
- **Release Integrity Verifier**: `php cli release:verify` ensuring cryptographic integrity, required file presence, and total exclusion of secrets (`.env`, `.git`, keys).
- **CI Provider Generators**: `php cli ci:init` auto-generating GitHub Actions (`.github/workflows/ci.yml`) and GitLab CI (`.gitlab-ci.yml`) pipeline definitions.
- **Release Notes & Version Management**: `ReleaseManager` and `VersionManager` powering `release:prepare` and `release:check` for SemVer consistency.

## [1.2.0-feature-traceability] - 2026-09-18

### Added
- **Feature Engineering System**: End-to-end feature lifecycle tracking (`FEAT-YYYY-NNNN`) with explicit status state machine (`DRAFT` to `RELEASED`).
- **Requirements & Acceptance Criteria**: Structured requirements (`FUNCTIONAL`, `SECURITY`, `UX`, etc.) and Gherkin/BDD acceptance scenarios.
- **Requirement Traceability Matrix (RTM)**: Automatic link discovery between Requirements → Code → Tests → Security → E2E → Docs with `CONFIRMED` and `INFERRED` tags.
- **Change Impact Analysis**: `php cli feature:impact` analyzing affected modules, routes, database changes, and required agents.
- **Definition of Done (DoD) & Release Auditor**: `php cli feature:check` and `php cli release:check` verifying DoD completeness for Release Candidate deployment.
- **AI Requirements & Bug Analysis**: LLM-powered ambiguity detection (`ai:requirements`), Gherkin scenario generator (`ai:acceptance`), and root cause diagnostic engine (`ai:bug`).

## [1.1.0-ai-orchestrator] - 2026-09-18

### Added
- **AI Orchestrator**: State-machine workflow engine (`WorkflowEngine`) connecting ContextEngine, CodeGenerator, and Python Agent Suite.
- **Feature Analyzer**: Contract parser for Markdown specification files (`FEATURE.md`) with requirement & rule extraction.
- **Technical Risk Engine**: Automated severity classification (`LOW`, `MEDIUM`, `HIGH`, `CRITICAL`) with human approval gates.
- **Specialized Workflows**: Dedicated pipelines for `orchestrate`, `feature run`, `bugfix`, `refactor`, `release`, and `--dry-run`.
- **Workflow Persistence & Resume**: Full state saving and event logging in `storage/reports/workflows/<WF-ID>/` with `status`, `resume`, and `cancel` CLI commands.

## [1.0.0-alpha] - 2026-09-18


### Added
- **Core Kernel**: Built-in Dependency Injection container, PSR-4 autoloader support, and configuration manager.
- **CLI Engine**: Interactive console tool (`cli`) with ASCII banner, CRUD generators, DB migrations, doctor health checks, and AI commands.
- **Clean Architecture**: Domain entities (`Customer`), Repository interfaces, Use Cases (`CreateCustomerUseCase`, `ListCustomersUseCase`), and Unit of Work (`SQLiteUnitOfWork`).
- **Database Engine**: Multi-database support for SQLite, MySQL, and PostgreSQL with automatic migrations and switching.
- **RAD Generators**: Automated scaffolding for Entities, Repositories, Use Cases, Controllers, Views, and Tests.
- **Local AI SDK**: Ollama client integration for local prompt execution and documentation semantic search.
- **Agent Ecosystem**: Python agent framework runner for QA, Security, UX, and Business auditing.
- **Web Workbench**: React + Tailwind interactive workbench for browser execution, CLI terminal, and AI documentation search.
