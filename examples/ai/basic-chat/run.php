<?php
/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
 require_once __DIR__ . '/../../../bootstrap/app.php'; use Veltrion\Services\AI\AIService; $ai = new AIService(); $res = $ai->generate("Hola, ¿cuáles son las capacidades del Veltrion Framework PHP?"); echo "=== VELTRION AI BASIC CHAT EXAMPLE ===\n"; echo "Proveedor: " . $res->getProvider() . "\n"; echo "Modelo: " . $res->getModel() . "\n\n"; echo $res->getContent() . "\n"; 