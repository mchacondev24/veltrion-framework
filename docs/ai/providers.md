# Guía de Extensión: Crear un Nuevo Proveedor de IA

- **Autor Original**: Maxwell Chacón

Para agregar un nuevo proveedor de IA (ej: OpenAI, Anthropic, Azure) a Veltrion PHP:

1. Crea la clase implementando `AIProviderInterface`:

```php
namespace Veltrion\Services\AI\Providers;

use Veltrion\Services\AI\Contracts\AIProviderInterface;
use Veltrion\Services\AI\DTOs\AIRequest;
use Veltrion\Services\AI\DTOs\AIResponse;

class CustomAIProvider implements AIProviderInterface
{
    public function getName(): string { return 'Custom Provider'; }
    public function isAvailable(): bool { return true; }
    public function listModels(): array { return ['model-1']; }
    public function generate(AIRequest $request): AIResponse
    {
        return new AIResponse("Respuesta", $this->getName(), "model-1", true);
    }
}
```

2. Regístralo en `config/ai.php` o inyéctalo en `AIService`.
