# ARCHITECTURE.md - Veltrion Framework Architecture Specification

## 1. Architectural Philosophy
Veltrion PHP is designed around **Clean Architecture**, **SOLID principles**, and **Domain-Driven Design (DDD)**. 

The core business logic is completely isolated from HTTP frameworks, database drivers, UI frameworks, or external AI services.

```
       ┌───────────────────────────────────────────────┐
       │             PRESENTATION / UI                 │
       │    (React / Web / CLI / HTTP Controllers)    │
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

## 2. Core Layers

### A. Domain (`app/Domain`)
- **Entities**: Mutable business objects with identity (e.g. `Customer`, `Order`).
- **Value Objects**: Immutable attributes (e.g. `Email`, `Money`, `Address`).
- **Repository Interfaces**: Contracts for data persistence (`CustomerRepositoryInterface`).
- **Domain Events**: Internal domain notifications (`OrderCreatedEvent`).

### B. Use Cases (`app/UseCases`)
- Single-responsibility application services that orchestrate Domain entities and Repositories.
- Implements transaction demarcation via `UnitOfWork`.

### C. Adapters & Infrastructure (`app/Adapters`)
- **Repositories**: Concrete persistence drivers (SQLite PDO, MySQL, Memory).
- **HTTP**: Router, Requests, Responses, Middlewares, API Controllers.
- **AI**: Local Ollama Client and Gemini Fallback Service.

### D. Python Agents Runtime (`agents/`)
- Independent Python runner for QA, E2E browser automation, security SAST/DAST, UX auditing, and business flow checking.
- Interoperates with PHP CLI via JSON reports in `storage/tests/`.

## 3. Database Switching & Unit of Work
- **Unit of Work Pattern**:
  ```php
  $uow = $container->get(UnitOfWorkInterface::class);
  $uow->begin();
  try {
      $customerRepo->save($customer);
      $orderRepo->save($order);
      $uow->commit();
  } catch (\Throwable $e) {
      $uow->rollback();
      throw $e;
  }
  ```
- **Database Switcher**: Standardized schema analysis & data exporter that handles MySQL, PostgreSQL, and SQLite migrations without data loss.

## 4. Architectural Decision Records (ADR)
Located in `docs/adr/`:
- `ADR-001-Clean-Architecture.md`: Strict layer isolation.
- `ADR-002-Repository-Pattern.md`: Interface contracts over concrete drivers.
- `ADR-003-Unit-of-Work.md`: Explicit multi-repository transactions.
- `ADR-004-Ollama-AI.md`: Local privacy-first LLM orchestration.
- `ADR-005-Python-Agent-Orchestration.md`: Decoupled multi-agent QA system.
