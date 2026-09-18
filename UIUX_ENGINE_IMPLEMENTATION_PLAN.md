# 🎨 UI/UX Generation Engine — Implementation Plan (Fase #13)

## 1. Arquitectura Actual del Framework
Veltrion opera como un ecosistema empresarial PHP estructurado bajo **Clean Architecture** y principios SOLID:
- **Core Domain (`app/Domain`)**: Entidades de negocio (`Customer`), Value Objects e interfaces de repositorio sin dependencias externas.
- **Casos de Uso (`app/UseCases`)**: Orquestación transaccional (`CreateCustomerUseCase`, `ListCustomersUseCase`) con `UnitOfWork`.
- **Adaptadores (`app/Adapters`)**: Repositorios SQLite, PDO y soporte de IA.
- **AI Engine (`app/Services/AI`)**: Servicio central `AIService`, cliente local `OllamaClient`, motor de contexto `ContextEngine`, orquestador de flujos `Orchestrator` y gestor de features `FeatureManager`.
- **Universal Project Template Engine (`app/Services/Template`)**: Registro `TemplateRegistry`, resolución de blueprints `TemplateResolver`, y generador de proyectos `TemplateGenerator` para PHP API, Angular, React, Android (Compose), MAUI (.NET/C#) y Flutter (Dart).
- **Knowledge Base (`app/Services/Knowledge`)**: Proveedores de conocimiento estructurado (`TemplateKnowledgeProvider`).
- **Ecosistema Multi-Agente en Python (`agents/`)**: Agentes especializados (`architecture`, `security`, `qa`, `ux`, `database`, etc.) comunicados mediante reportes JSON.
- **CLI (`cli`)**: Consola interactiva para administración, diagnóstico, generación y testing.

---

## 2. Componentes Reutilizables Identificados
- `TemplateEngine`, `TemplateRegistry` y `PlatformType` para asociar UI generada a los blueprints de plataformas existentes.
- `AIService` y `OllamaClient` para inferencia local sin dependencias en la nube (con fallback determinista de reglas si Ollama está inactivo).
- `FeatureManager`, `TraceabilityEngine` y `CompletenessChecker` para mapear Requirements -> Features -> Screens -> Components -> Código.
- `ContextFilter` y `ContextEngine` para escanear entidades, esquemas de BD y DTOs protegiendo credenciales y secretos.
- `AgentBase` y `UXAgent` en Python para auditorías automáticas de usabilidad, contraste y jerarquía visual.
- Pipeline de Quality Gates y Testing de `cli` para verificación continua.

---

## 3. Componentes Faltantes (A Construir en Fase #13)
1. **Core UI/UX Engine (`app/Services/UIUX/UIUXEngine.php`)**: Orquestador principal que une análisis, planificación, tokens de diseño y generación de código multi-plataforma.
2. **Modelo Formal UX (`app/Services/UIUX/DTOs/`, `Enums/`)**: DTOs inmutables para UserPersona, UserRole, UserJourney, UserFlow, ScreenModel, ScreenAction, ScreenState, UIComponent, FormField, ValidationRule, DesignToken, Theme, Breakpoint y AccessibilityRule.
3. **UX Analyzer & User Flow Engine (`app/Services/UIUX/Planning/`)**: Analizador de roles/personas y constructor de grafos dirigidos (DAG) de flujos de usuario con detección de ciclos y pantallas inalcanzables.
4. **Screen & Navigation Planner (`app/Services/UIUX/Planning/`)**: Planificador jerárquico de pantallas y árboles de navegación adaptados a Web, Móvil, Desktop y Tablet.
5. **Design System & Tokens Engine (`app/Services/UIUX/DesignSystem/`)**: Generador y gestor de Design Tokens (colores, tipografía, espaciado, radio, sombras, breakpoints) y soporte para temas Light, Dark y Custom.
6. **Universal Component Registry & Composer (`app/Services/UIUX/Components/`)**: Catálogo exhaustivo de componentes (botones, formularios, tablas, diálogos, métricas KPI, alertas, estados vacíos/carga/error) y compositores para vistas complejas.
7. **Generadores Especializados de UI (`app/Services/UIUX/Forms/`, `CRUD/`, `Dashboard/`, `MasterDetail/`)**: Generación de formularios a partir de entidades/esquemas, CRUD completo, dashboards con visualizaciones de datos y vistas maestro-detalle (ej. pedidos + ítems).
8. **Responsive & Accessibility Engines (`app/Services/UIUX/Responsive/`, `Accessibility/`)**: Breakpoints abstractos (XS a XXL) y reglas WCAG 2.1 AA con severidades (CRITICAL a INFO).
9. **Quality & Validation Engine (`app/Services/UIUX/Validation/`)**: Detección de Dead Ends, navegación rota, estados faltantes (loading/error/empty) y formularios sin validación.
10. **Platform UI Adapters (`app/Services/UIUX/Adapters/`)**:
   - `ReactUIAdapter` (TypeScript + React 18 + Tailwind CSS)
   - `AngularUIAdapter` (TypeScript + Angular 17+ Standalone + Signals)
   - `FlutterUIAdapter` (Dart + Flutter Widgets + Riverpod)
   - `AndroidComposeUIAdapter` (Kotlin + Jetpack Compose + MVVM)
   - `MauiUIAdapter` (C# 12 + XAML + MVVM Toolkit)
   - `PhpWebUIAdapter` (PHP + HTML5 + Tailwind / Clean Views)
11. **Idempotencia y Protección (`app/Services/UIUX/Idempotency/`)**: Gestión de metadata de generación (`GENERATED`, `CUSTOM`, `PROTECTED`) y prevención de sobreescritura destructiva.
12. **Integración con Universal Application Factory (`app/Services/Factory/UniversalApplicationFactory.php`)**: Pipeline unificado: Requirements -> Architecture -> Database -> API -> UX Analysis -> Screen Planning -> Design System -> Component Planning -> Platform Adapter -> Code Generation -> Tests.
13. **Knowledge Base Provider (`app/Services/Knowledge/Providers/UIUXKnowledgeProvider.php`)**: Indexación de modelos UX y Design Tokens en la base de conocimiento.
14. **CLI Commands (`cli`)**: `ux:analyze`, `ux:flows`, `ux:screens`, `ux:design`, `ux:validate`, `ux:accessibility`, `ux:generate`, `ux:diff`, `ux:report`, `factory:create`.

---

## 4. Archivos a Modificar
- `cli`: Registro de comandos `ux:*` y `factory:create`, actualización del comando `test` para validar el motor UI/UX.
- `agents/ux/ux_agent.py`: Ampliación del agente UX para auditar el grafo de navegación, contraste y estados UI generados.
- `app/Services/Knowledge/Providers/TemplateKnowledgeProvider.php`: Enlace de UI/UX capabilities.
- `README.md`, `CHANGELOG.md`: Registro de la nueva capacidad de UI/UX Multi-Plataforma.

---

## 5. Archivos Nuevos
- `app/Services/UIUX/Contracts/*` (Interfaces de adaptadores, renderers y servicios).
- `app/Services/UIUX/Enums/*` (Plataformas, estados UI, severidades, categorías, breakpoints).
- `app/Services/UIUX/DTOs/*` (Modelos de datos formales de UX y pantallas).
- `app/Services/UIUX/DesignSystem/*` (Tokens y temas).
- `app/Services/UIUX/Components/*` (Catálogo y composición).
- `app/Services/UIUX/Planning/*` (UXAnalyzer, UserFlowGenerator, ScreenPlanner, NavigationPlanner).
- `app/Services/UIUX/Forms/FormGenerator.php`.
- `app/Services/UIUX/CRUD/CrudUIGenerator.php`.
- `app/Services/UIUX/Dashboard/DashboardGenerator.php`.
- `app/Services/UIUX/MasterDetail/MasterDetailUIGenerator.php`.
- `app/Services/UIUX/Responsive/ResponsiveEngine.php`.
- `app/Services/UIUX/Accessibility/AccessibilityEngine.php`.
- `app/Services/UIUX/Validation/UIValidator.php`.
- `app/Services/UIUX/Testing/VisualTestEngine.php`.
- `app/Services/UIUX/Adapters/*` (Adaptadores para React, Angular, Flutter, Android Compose, MAUI y PHP Web).
- `app/Services/UIUX/Idempotency/FileMetadataManager.php`.
- `app/Services/UIUX/Knowledge/UIUXKnowledgeProvider.php`.
- `app/Services/UIUX/UIUXEngine.php`.
- `app/Services/Factory/UniversalApplicationFactory.php`.
- `docs/13-uiux-generation-engine.md`.
- `examples/golden-projects/*` (Golden tests para las 6 plataformas).

---

## 6. Riesgos y Mitigación
- **Riesgo**: Acoplamiento del modelo abstracto con sintaxis específica de React o Flutter.
  - *Mitigación*: Modelo UX puramente neutral (`ScreenModel`, `UIComponent`, `FormField`, `DesignToken`), delega el 100% de la sintaxis y renderizado al `PlatformUIAdapter`.
- **Riesgo**: Sobreescritura accidental de código editado manualmente por desarrolladores.
  - *Mitigación*: `FileMetadataManager` con tags de propiedad (`GENERATED`, `CUSTOM`, `PROTECTED`) y hashing SHA-256 de contenido base.
- **Riesgo**: Generación de pantallas o botones ficticios sin valor funcional.
  - *Mitigación*: Trazabilidad estricta (`REQ -> FEAT -> FLOW -> SCREEN -> COMP -> CODE`) validada por `UIValidator`.

---

## 7. Orden de Implementación por Etapas
- **Etapa 1**: Enums, DTOs y Contratos del Modelo UX Universal.
- **Etapa 2**: Design System Engine, Design Tokens y Themes.
- **Etapa 3**: Component Registry, Component Composer y Generadores (Forms, CRUD, Dashboard, Master-Detail).
- **Etapa 4**: UX Analyzer, User Flow Generator (DAG), Screen Planner y Navigation Planner.
- **Etapa 5**: Responsive Engine, Accessibility Engine (WCAG AA), UI State Engine y UI Validator.
- **Etapa 6**: Platform UI Adapters (React, Angular, Flutter, Android Compose, .NET MAUI, PHP Web) y File Metadata Manager.
- **Etapa 7**: Visual Test Engine, UIUXEngine y Universal Application Factory.
- **Etapa 8**: Knowledge Base Provider, Actualización de UXAgent en Python y comandos CLI.
- **Etapa 9**: Golden Projects, Pruebas Integradas y Verificación.
- **Etapa 10**: Documentación técnica, Feature Spec y Git commit.
