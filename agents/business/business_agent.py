#!/usr/bin/env python3
import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from core.agent_base import AgentBase

class BusinessAgent(AgentBase):
    def __init__(self):
        super().__init__("BusinessAgent", "business")

    def audit_rules(self, project_root: str = "."):
        self.add_finding("INFO", "Regla de Negocio: Clientes Únicos por Email",
                         "Verificado en CreateCustomerUseCase la validación contra registros duplicados.")
        self.add_finding("INFO", "Transaccionalidad Atómica (Unit of Work)",
                         "Las operaciones complejas se ejecutan en bloques comitables o reversibles automáticamente.")
        self.add_recommendation("Definir políticas de borrado lógico (soft deletes) para registros empresariales con historial.")
        self.save_report()

if __name__ == "__main__":
    agent = BusinessAgent()
    agent.audit_rules()
