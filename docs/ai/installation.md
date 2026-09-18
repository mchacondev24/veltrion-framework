# Instalación de Ollama e IA Local en Veltrion PHP

- **Autor Original**: Maxwell Chacón

## Pasos para Activar Ollama en Local

### 1. Instalar Ollama en tu sistema
- **Linux / macOS**:
  ```bash
  curl -fsSL https://ollama.com/install.sh | sh
  ```
- **Windows**: Descarga el ejecutable desde [ollama.com](https://ollama.com).

### 2. Descargar el modelo preferido
```bash
ollama pull llama3
```

### 3. Verificar el servidor local
```bash
ollama serve
```

### 4. Validar desde la CLI de Veltrion PHP
```bash
php cli ai:status
```
Deberías ver:
```
✓ Ollama (Local LLM) activo
✓ Conexión exitosa a http://localhost:11434
✓ Modelo llama3 disponible
```
