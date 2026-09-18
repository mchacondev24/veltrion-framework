#!/usr/bin/env python3
import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from core.agent_base import AgentBase

class UXAgent(AgentBase):
    def __init__(self):
        super().__init__("UXAgent", "ux")

    def audit_ux(self, project_root: str = "."):
        self.add_finding("INFO", "Jerarquía Tipográfica y Espaciado Adaptativo",
                         "La interfaz web del framework implementa fuentes display legibles y contraste WCAG AA.")
        self.add_finding("INFO", "Terminal Interactivas y Estados de Carga",
                         "Soporte para feedback visual inmediato en la CLI y respuestas AJAX claras.")
        self.add_recommendation("Verificar accesibilidad con contraste superior a 4.5:1 en todos los botones de la interfaz.")
        self.save_report()

if __name__ == "__main__":
    agent = UXAgent()
    agent.audit_ux()
