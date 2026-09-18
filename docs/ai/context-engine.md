# Context Engine - Veltrion Framework PHP

- **Autor Original**: Maxwell Chacón
- **Licencia**: Apache 2.0 (Open Source)

## Descripción General

El **Context Engine** es el componente de inteligencia contextual de Veltrion PHP. Permite que el modelo de IA local (Ollama) o remoto (Gemini) comprenda la totalidad del proyecto antes de generar respuestas o código fuente.

## Componentes de la Arquitectura

```
Services/AI/Context/
├── ContextEngine.php       # Fachada principal (implements ContextProviderInterface)
├── ProjectScanner.php      # Escáner recursivo del proyecto
├── ContextFilter.php       # Filtro de exclusión de seguridad (.env, llaves, logs, vendor)
├── ContextRanker.php       # Algoritmo de scoring de relevancia de archivos por consulta
├── ContextChunker.php      # Estimador y truncador de límites de tokens (12,000 max)
├── ContextFormatter.php    # Formateador de prompts estructurados en Markdown
├── ContextCache.php        # Cache del índice del proyecto en storage/cache/ai_context.json
└── ContextBuilder.php      # Orquestador del pipeline de contexto
```

## Comandos CLI

- `php cli ai:context`: Genera y construye el prompt de contexto actual.
- `php cli ai:context --stats`: Muestra estadísticas del índice (archivos, fecha de actualización, tamaño).
- `php cli ai:context --refresh`: Fuerza el reescaneo completo del proyecto y actualiza el caché.
- `php cli ai:context --clear`: Borra el caché de contexto.

## Filtros de Seguridad Inviolables

El filtro `ContextFilter` garantiza que los siguientes patrones nunca sean enviados a los LLM:
- `.env`, `.env.*`, `credentials.json`, `secrets.json`, `passwords.txt`
- `*.key`, `*.pem`, `*.crt`, `*.p12`, `*.pfx`
- `storage/logs/*`, `storage/cache/*`
- `vendor/*`, `node_modules/*`, `.git/*`
