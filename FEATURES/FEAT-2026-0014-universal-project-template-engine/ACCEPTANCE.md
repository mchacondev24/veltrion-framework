# Acceptance Criteria: Universal Project Template Engine

## Scenario 1: Registro e Inspección de Plantillas
- **Given** el Template Registry inicializado
- **When** se ejecuta `php cli template:list`
- **Then** se devuelven 6 plantillas oficiales (PHP, Angular, React, Android, MAUI, Flutter)
- **And** cada plantilla muestra sus capacidades y Feature Packs asociados.

## Scenario 2: Resolución Inteligente de Blueprint
- **Given** una solicitud para la plataforma `flutter` con requisito de `auth`
- **When** se invoca `TemplateResolver::resolve('flutter')`
- **Then** se devuelve `tpl-flutter-dart-v1` con un score de resolución positivo
- **And** el Feature Pack `flutter-riverpod-auth` queda automáticamente seleccionado.

## Scenario 3: Generación de Proyecto Completo
- **Given** la plataforma `angular` y el nombre de proyecto `MyStoreApp`
- **When** se ejecuta `php cli template:create angular MyStoreApp`
- **Then** se crea la estructura física en disco
- **And** se genera el archivo `template-manifest.json` con metadatos completos.
