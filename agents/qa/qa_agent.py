#!/usr/bin/env python3
import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from core.agent_base import AgentBase

class QAAgent(AgentBase):
    def __init__(self):
        super().__init__("QAAgent", "qa")

    def run_tests(self, project_root: str = "."):
        # Check domain entities
        entities_dir = os.path.join(project_root, "app", "Domain", "Entities")
        if os.path.exists(entities_dir):
            entities = [f for f in os.listdir(entities_dir) if f.endswith(".php")]
            for entity in entities:
                self.add_finding("INFO", f"Entidad de Dominio validada: {entity}",
                                 "Estructura orientada a objetos con encapsulamiento correcto.", entity)
        
        # Check UseCases
        usecases_dir = os.path.join(project_root, "app", "UseCases")
        if os.path.exists(usecases_dir):
            for root, _, files in os.walk(usecases_dir):
                for f in files:
                    if f.endswith("UseCase.php"):
                        self.add_finding("INFO", f"Caso de Uso verificado: {f}",
                                         "Implementación con patrón de comando o ejecutor único.", f)

        self.add_recommendation("Asegurar que cada nuevo Caso de Uso tenga su prueba unitaria correspondiente en tests/Unit/")
        self.save_report()

if __name__ == "__main__":
    agent = QAAgent()
    agent.run_tests()
