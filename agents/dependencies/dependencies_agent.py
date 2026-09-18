#!/usr/bin/env python3
import sys
import os
import json
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from core.agent_base import AgentBase

class DependenciesAgent(AgentBase):
    def __init__(self):
        super().__init__("DependenciesAgent", "dependencies")

    def run_audit(self, project_root: str = "."):
        composer_json = os.path.join(project_root, "composer.json")
        package_json = os.path.join(project_root, "package.json")

        if os.path.exists(composer_json):
            try:
                with open(composer_json, "r", encoding="utf-8") as f:
                    data = json.load(f)
                    reqs = data.get("require", {})
                    self.add_finding("INFO", f"Composer audit: {len(reqs)} paquetes requeridos", str(list(reqs.keys())), "composer.json")
            except Exception as e:
                self.add_finding("MEDIUM", "Error al analizar composer.json", str(e), "composer.json")

        if os.path.exists(package_json):
            try:
                with open(package_json, "r", encoding="utf-8") as f:
                    data = json.load(f)
                    deps = data.get("dependencies", {})
                    self.add_finding("INFO", f"NPM audit: {len(deps)} dependencias instaladas", str(list(deps.keys())), "package.json")
            except Exception as e:
                self.add_finding("MEDIUM", "Error al analizar package.json", str(e), "package.json")

        self.add_recommendation("Revisar periódicamente las dependencias con 'composer audit' y 'npm audit'.")
        self.save_report()

if __name__ == "__main__":
    agent = DependenciesAgent()
    agent.run_audit()
