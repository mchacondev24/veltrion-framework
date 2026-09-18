# ADR-001: Clean Architecture en Veltrion PHP

- **Estado**: Aceptado
- **Contexto**: Se requiere una arquitectura sostenible que permita evolucionar el software empresarial sin acoplar las reglas de negocio a la infraestructura.
- **Decisión**: Adoptar Clean Architecture dividida en 4 capas estrictas: Domain, UseCases, Adapters e Infrastructure.
- **Consecuencias**:
  - (+) Las pruebas unitarias del dominio no requieren base de datos activa.
  - (+) Es posible cambiar de base de datos (p.ej. de SQLite a PostgreSQL) sin alterar el dominio.
  - (-) Aumenta ligeramente la cantidad inicial de archivos por módulo.
