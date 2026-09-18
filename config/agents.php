<?php
/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
 return [ 'python_path' => 'python3', 'agents_dir' => __DIR__ . '/../agents', 'reports_dir' => __DIR__ . '/../storage/reports', 'active_agents' => [ 'qa' => 'agents/qa/qa_agent.py', 'security' => 'agents/security/security_agent.py', 'ux' => 'agents/ux/ux_agent.py', 'business' => 'agents/business/business_agent.py', 'performance' => 'agents/performance/performance_agent.py', ] ]; 