# Configuración de IA en Veltrion PHP

- **Autor Original**: Maxwell Chacón

## Archivo de Configuración: `config/ai.php`

```php
return [
    'enabled' => processEnv('AI_ENABLED', 'true') === 'true',
    'provider' => processEnv('AI_PROVIDER', 'ollama'),
    'ollama' => [
        'host' => processEnv('OLLAMA_HOST', 'http://localhost:11434'),
        'model' => processEnv('OLLAMA_MODEL', 'llama3'),
        'timeout' => (int) processEnv('OLLAMA_TIMEOUT', '120'),
    ],
    'gemini' => [
        'api_key' => processEnv('GEMINI_API_KEY', ''),
        'model' => processEnv('GEMINI_MODEL', 'gemini-2.5-flash'),
    ],
];
```

## Variables de Entorno en `.env`

```env
AI_ENABLED=true
AI_PROVIDER=ollama
OLLAMA_HOST=http://localhost:11434
OLLAMA_MODEL=llama3
OLLAMA_TIMEOUT=120
```
