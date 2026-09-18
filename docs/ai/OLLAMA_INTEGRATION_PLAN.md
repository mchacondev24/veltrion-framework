# OLLAMA_INTEGRATION_PLAN.md - Plan de Integración de IA Local en Veltrion PHP

- **Proyecto**: Veltrion Framework PHP v1.0.0
- **Autor / Creador Original**: Maxwell Chacón
- **Licencia**: Apache 2.0 (Open Source)

---

## 1. Arquitectura Actual del Proyecto

Veltrion PHP es un framework empresarial estructurado bajo **Clean Architecture** y **DDD**:
- `bootstrap/Container.php`: Contenedor PSR-11 con auto-wiring.
- `config/`: Archivos de configuración centralizados (`app.php`, `database.php`, `ai.php`, `security.php`, `agents.php`).
- `app/Domain/`: Entidades puras (`Customer`), Value Objects (`Email`), Interfaces de Repositorio y Unit of Work.
- `app/UseCases/`: Casos de uso desacoplados.
- `app/Adapters/`: Repositorios PDO SQLite, Controllers HTTP, cliente de soporte AI.
- `cli`: Interfaz de consola ejecutable desde terminal (`php cli`).

---

## 2. Puntos de Integración de IA

La integración de IA con Ollama se diseñará como un módulo completamente **desacoplado y enchufable** mediante el patrón **Strategy / Provider**:

```
           ┌──────────────────────────────────────────────┐
           │                   CLI / UI                   │
           │         (php cli ai:ask, php cli ai:code)    │
           └──────────────────────┬───────────────────────┘
                                  │
           ┌──────────────────────▼───────────────────────┐
           │                  AIService                   │
           │         (Orquestación & Contexto)            │
           └──────────────────────┬───────────────────────┘
                                  │
           ┌──────────────────────▼───────────────────────┐
           │             AIProviderInterface              │
           └──────────┬───────────────────────┬───────────┘
                      │                       │
     ┌────────────────▼──────────────┐ ┌──────▼──────────────────────┐
     │        OllamaProvider         │ │        GeminiProvider        │
     │   (Local HTTP:11434/api)      │ │      (Google GenAI SDK)      │
     └───────────────────────────────┘ └──────────────────────────────┘
```

---

## 3. Clases Nuevas y Estructura

```
app/Services/AI/
├── Contracts/
│   └── AIProviderInterface.php
├── DTOs/
│   ├── AIRequest.php
│   └── AIResponse.php
├── Exceptions/
│   ├── AIException.php
│   ├── OllamaConnectionException.php
│   └── AIModelException.php
├── Providers/
│   ├── OllamaProvider.php
│   └── GeminiProvider.php
├── AIContext.php          (Filtro de seguridad y colector de contexto de archivos)
├── PromptManager.php      (Plantillas de prompts para código, tests y docs)
└── AIService.php          (Fachada del servicio de IA registrado en el Contenedor DI)
```

---

## 4. Estrategia de Filtro de Seguridad (Privacidad Local)

`AIContext` aplicará una lista negra automática antes de enviar cualquier información a Ollama o proveedores externos:
- Excluye: `.env`, `.env.*`, claves API, contraseñas, tokens JWT, certificados, certificados SSL, claves privadas SSH y logs de `storage/logs/`.

---

## 5. Estrategia de Pruebas (Testing)

- **Unit Tests**: Pruebas con Mocks de respuestas HTTP para `OllamaProvider` sin requerir que el daemon Ollama esté corriendo.
- **Graceful Fallback**: Si Ollama no está activo (`php cli ai:status`), el framework no generará errores fatales; mostrará un estado informativo y continuará operando normalmente.

---

## 6. Créditos y Atribución

Toda la documentación, el encabezado de los comandos CLI, el archivo `composer.json` y el `README.md` mantendrán de forma explícita la atribución a su creador original: **Maxwell Chacón**.
