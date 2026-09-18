# Requirements: Universal Project Template Engine

## Functional Requirements
- **REQ-TPL-001**: El registro (`TemplateRegistry`) debe almacenar y recuperar plantillas por ID y plataforma target.
- **REQ-TPL-002**: El resolutor (`TemplateResolver`) debe evaluar coincidencia de plataforma, matriz de capacidades y restricciones técnicas con un score de 0 a 100%.
- **REQ-TPL-003**: El generador (`TemplateGenerator`) debe procesar variables dinámicas (`appName`, `namespace`, `author`, `year`) y superponer Feature Packs seleccionados.
- **REQ-TPL-004**: Debe proveer plantillas reales para 6 plataformas principales: PHP API, Angular, React, Android, MAUI y Flutter.
- **REQ-TPL-005**: La interfaz CLI debe ofrecer comandos para listar (`template:list`), inspeccionar (`template:show`), resolver (`template:resolve`) y generar (`template:create`) proyectos.
- **REQ-TPL-006**: La Knowledge Base debe integrar el `TemplateKnowledgeProvider` para disponibilizar metadatos de plantillas a agentes de IA.

## Non-Functional Requirements
- **NFR-TPL-001**: La resolución de plantillas debe tardar menos de 50ms.
- **NFR-TPL-002**: La generación de un proyecto completo debe realizarse en menos de 2 segundos.
