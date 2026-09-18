<?php
namespace Veltrion\Services\Template\Blueprints;

use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\DTOs\CapabilityMatrix;
use Veltrion\Services\Template\DTOs\FeaturePack;
use Veltrion\Services\Template\Enums\PlatformType;
use Veltrion\Services\Template\Enums\TemplateStatus;
use Veltrion\Services\Template\Enums\FeaturePackType;

class ReactWebAppBlueprint
{
    public static function create(): TemplateManifest
    {
        $capabilities = new CapabilityMatrix(
            capabilities: [
                'spa' => true,
                'hooks' => true,
                'tailwind_css' => true,
                'vite_fast_build' => true,
                'state_management' => true,
                'routing' => true,
            ],
            constraints: [
                'node_version' => '>=18.0',
                'typescript' => '^5.0',
            ]
        );

        $authPack = new FeaturePack(
            id: 'react-auth-context',
            name: 'React AuthContext & ProtectedRoute Pack',
            type: FeaturePackType::AUTH,
            description: 'Provides AuthContext provider, useAuth hook, and ProtectedRoute wrapper component',
            dependencies: ['lucide-react' => '^0.344.0'],
            files: [
                'src/context/AuthContext.tsx' => "import React, { createContext, useContext, useState } from 'react';\n\ninterface AuthContextType {\n  user: any;\n  login: (data: any) => void;\n  logout: () => void;\n}\n\nconst AuthContext = createContext<AuthContextType | null>(null);\n\nexport const AuthProvider: React.FC<{children: React.ReactNode}> = ({ children }) => {\n  const [user, setUser] = useState<any>(null);\n  const login = (data: any) => setUser(data);\n  const logout = () => setUser(null);\n  return <AuthContext.Provider value={{ user, login, logout }}>{children}</AuthContext.Provider>;\n};\n\nexport const useAuth = () => useContext(AuthContext)!;\n"
            ]
        );

        return new TemplateManifest(
            id: 'tpl-react-web-app-v1',
            name: 'React 18 + Vite + TypeScript Application Blueprint',
            version: '18.2.0',
            platform: PlatformType::REACT,
            language: 'TypeScript',
            architecture: 'Modern React (Functional Components, Hooks, Context API, Tailwind CSS)',
            status: TemplateStatus::STABLE,
            capabilities: $capabilities,
            dependencies: [
                'react' => '^18.2.0',
                'react-dom' => '^18.2.0',
                'vite' => '^5.1.0',
                '@types/react' => '^18.2.0',
                'tailwindcss' => '^3.4.0',
                'lucide-react' => '^0.344.0'
            ],
            structure: [
                'src/components' => 'Reusable UI components',
                'src/context' => 'Application state providers',
                'src/hooks' => 'Custom React hooks',
                'src/pages' => 'Route view pages',
                'src/App.tsx' => 'Main App component root',
                'src/main.tsx' => 'React DOM root render entry point',
                'vite.config.ts' => 'Vite build engine configuration',
                'package.json' => 'NPM package dependencies'
            ],
            supportedFeaturePacks: [
                'auth' => $authPack,
            ],
            qualityGates: [
                'eslint_max_warnings' => '0',
            ],
            description: 'High-performance React 18 SPA template bundled with Vite, Tailwind CSS, Lucide icons, and TypeScript strict mode.'
        );
    }
}
