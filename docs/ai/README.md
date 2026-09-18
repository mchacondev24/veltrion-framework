# Veltrion AI Engine - Guía Principal

- **Framework**: Veltrion Framework PHP v1.0.0
- **Autor / Creador Original**: Maxwell Chacón
- **Proveedor Predeterminado**: Ollama (Local LLM - `http://localhost:11434`)

## Índice de Documentación

1. [Instalación](installation.md)
2. [Configuración](configuration.md)
3. [Integración con Ollama](ollama.md)
4. [Comandos CLI de IA](cli.md)
5. [Proveedores (AIProviderInterface)](providers.md)
6. [Gestor de Contexto (AIContext & Seguridad)](context.md)
7. [Generación de Código](code-generation.md)
8. [Testing de IA](testing.md)
9. [Seguridad & Privacidad](security.md)
10. [Solución de Problemas](troubleshooting.md)

---

## Filosofía del Motor de IA

En Veltrion PHP, la Inteligencia Artificial es una capacidad **nativa pero desacoplada**:
- **Procesamiento 100% Local**: Funciona por defecto con Ollama local sin enviar datos a la nube.
- **Fallbacks Graceful**: Si Ollama no está activo, el framework no falla; entra en modo de respuesta asistida estática.
- **Dry-Run & Confirmación**: La IA propone estructuras en Clean Architecture; los cambios solo se aplican tras confirmación del desarrollador (`[Y] Aplicar`).
