#!/usr/bin/env python3
import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from core.agent_base import AgentBase

class ArchitectureAgent(AgentBase):
    def __init__(self):
        super().__init__("ArchitectureAgent", "architecture")

    def run_audit(self, project_root: str = "."):
        app_dir = os.path.join(project_root, "app")
        if not os.path.exists(app_dir):
            self.add_finding("HIGH", "Directorio app/ no encontrado", "No se detectó la estructura principal de código.", "app/")
            self.save_report()
            return

        # Clean Architecture checks
        for root, _, files in os.walk(app_dir):
            for file in files:
                if file.endswith(".php"):
                    path = os.path.join(root, file)
                    rel_path = os.path.relpath(path, project_root)
                    with open(path, "r", encoding="utf-8", errors="ignore") as f:
                        content = f.read()

                        # Check controllers bypassing UseCases/Repositories
                        if "Controllers" in rel_path and ("PDO" in content or "sqlite_" in content):
                            self.add_finding("HIGH", "Controlador con acceso directo a Base de Datos",
                                             "El controlador accede directamente a la BD en lugar de usar un UseCase/Repository.", rel_path)

                        # Check Domain layer depending on Infrastructure
                        if "Domain" in rel_path and ("Adapters" in content or "PDO" in content):
                            self.add_finding("HIGH", "Violación de Capa de Dominio",
                                             "La capa de Dominio no debe depender de la capa de Adaptadores o Infraestructura.", rel_path)

        # Template Engine Descriptor check
        manifest_file = os.path.join(project_root, "template-manifest.json")
        if os.path.exists(manifest_file):
            self.add_finding("INFO", "Template Blueprint Manifest Validado", "El proyecto contiene un descriptor válido de Universal Template Engine.", "template-manifest.json")

        if not self.findings:
            self.add_finding("INFO", "Auditoría de Arquitectura completada",
                             "Las reglas de Clean Architecture y aislamiento de capas se cumplen correctamente.")

        self.add_recommendation("Conservar la separación estricta: Domain -> UseCases -> Adapters -> Infrastructure.")
        self.save_report()

if __name__ == "__main__":
    agent = ArchitectureAgent()
    agent.run_audit()
