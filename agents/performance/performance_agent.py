#!/usr/bin/env python3
import sys
import os
import time
import subprocess
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from core.agent_base import AgentBase

class PerformanceAgent(AgentBase):
    def __init__(self):
        super().__init__("PerformanceAgent", "performance")

    def run_audit(self, project_root: str = "."):
        cli_path = os.path.join(project_root, "cli")
        if os.path.exists(cli_path):
            start = time.time()
            res = subprocess.run(["php", cli_path, "-v"], capture_output=True, text=True)
            elapsed = time.time() - start

            if elapsed > 0.5:
                self.add_finding("MEDIUM", "Arranque de CLI lento (> 500ms)", f"Tiempo de boot: {round(elapsed*1000, 2)}ms", "cli")
            else:
                self.add_finding("INFO", "Velocidad de arranque CLI óptima", f"Tiempo de boot: {round(elapsed*1000, 2)}ms (< 500ms)", "cli")

        self.add_recommendation("Usar opcache en producción para acelerar la carga de clases PHP.")
        self.save_report()

if __name__ == "__main__":
    agent = PerformanceAgent()
    agent.run_audit()
