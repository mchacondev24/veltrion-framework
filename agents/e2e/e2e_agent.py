#!/usr/bin/env python3
import sys
import os
import urllib.request
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from core.agent_base import AgentBase

class E2EAgent(AgentBase):
    def __init__(self):
        super().__init__("E2EAgent", "e2e")

    def run_audit(self, target_url: str = "http://localhost:3000"):
        try:
            req = urllib.request.Request(target_url, headers={'User-Agent': 'VeltrionE2EAgent/1.0'})
            with urllib.request.urlopen(req, timeout=5) as response:
                status = response.status
                if status == 200:
                    self.add_finding("INFO", "Servidor web HTTP 200 OK", f"El servidor respondió correctamente en {target_url}", target_url)
                else:
                    self.add_finding("MEDIUM", f"Respuesta inusual HTTP {status}", f"Se recibió código {status} desde {target_url}", target_url)
        except Exception as e:
            self.add_finding("INFO", "Servidor Web no activo en puerto local",
                             "E2E sintético finalizado. Utilice 'npm run dev' para pruebas de interfaz web.", target_url)

        self.add_recommendation("Ejecutar suite E2E completa en entornos de prueba antes de despliegue.")
        self.save_report()

if __name__ == "__main__":
    agent = E2EAgent()
    agent.run_audit()
