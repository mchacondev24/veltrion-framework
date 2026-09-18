# AI Code Generation - Veltrion Framework PHP

- **Autor Original**: Maxwell Chacón

## Flujo Seguro de Generación de Código

1. **Recepción de Solicitud**: `php cli ai:code "<prompt>"`
2. **Análisis de Cambios**: `ChangeAnalyzer` determina si la solicitud corresponde a un módulo RAD (`make:crud`) o a un cambio de servicio.
3. **Construcción de Plan**: `GenerationPlan` detalla los pasos y archivos a modificar.
4. **Generación de Diff**: `DiffGenerator` calcula las diferencias contra el código fuente actual.
5. **Paso de Confirmación**: Muestra el plan y el diff y solicita confirmación explicita (`[Y] Aplicar / [N] Cancelar`).
6. **Aplicación e Integración**: Al confirmar, escribe los archivos y actualiza el caché de contexto.
7. **Verificación**: Ejecuta la suite de pruebas unitarias para garantizar cero regresiones.
