<?php
/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
 require_once __DIR__ . '/../../../bootstrap/app.php'; use Veltrion\Services\AI\AIService; use Veltrion\Services\AI\PromptManager; $ai = new AIService(); $prompt = PromptManager::forCodeGeneration("Product"); $res = $ai->generate($prompt); echo "=== VELTRION AI GENERATE CRUD PROPOSAL ===\n"; echo $res->getContent() . "\n"; 