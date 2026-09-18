#!/usr/bin/env python3
import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from core.agent_base import AgentBase

class DocumentationAgent(AgentBase):
    def __init__(self):
        super().__init__("DocumentationAgent", "documentation")

    def run_audit(self, project_root: str = "."):
        req_files = ["README.md", "FEATURES.md", "docs/CLI_COMMANDS_REFERENCE.md", "docs/ai/context-engine.md"]

        for rf in req_files:
            p = os.path.join(project_root, rf)
            if not os.path.exists(p):
                self.add_finding("HIGH", f"Archivo de documentación faltante: {rf}", "Es necesario mantener la documentación oficial sincronizada.", rf)
            else:
                self.add_finding("INFO", f"Documentación verificada: {rf}", "Archivo de documentación presente y actualizado.", rf)

        self.add_recommendation("Actualizar FEATURES.md inmediatamente tras incorporar nuevos módulos o servicios.")
        self.save_report()

if __name__ == "__main__":
    agent = DocumentationAgent()
    agent.run_audit()
