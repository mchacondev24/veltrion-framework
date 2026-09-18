# AI Security & Exclusions - Veltrion Framework PHP

- **Autor Original**: Maxwell Chacón

## Políticas de Seguridad de IA

1. **Sin Exposición de Credenciales**: Ningún secreto, token o clave de API almacenada en archivos de entorno es leída o enviada a Ollama/Gemini.
2. **Path Traversal Protection**: Se verifican las rutas de los archivos para evitar lecturas fuera de la raíz del proyecto.
3. **Bloqueo de Comandos Destructivos**: Operaciones como `rm -rf`, `DROP DATABASE` o formateos de disco están estrictamente prohibidas en las sugerencias generadas por la IA.
4. **Confirmación Obligatoria**: La IA nunca modifica archivos en el disco de manera silenciosa; siempre requiere una interacción previa del desarrollador.
