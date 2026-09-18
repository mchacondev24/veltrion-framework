#!/usr/bin/env python3
import sys
import os
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from core.agent_base import AgentBase

class SecurityAgent(AgentBase):
    def __init__(self):
        super().__init__("SecurityAgent", "security")

    def run_audit(self, project_root: str = "."):
        # SAST Scan for raw SQL concatenation or plain passwords
        for root, _, files in os.walk(os.path.join(project_root, "app")):
            for file in files:
                if file.endswith(".php"):
                    path = os.path.join(root, file)
                    with open(path, "r", encoding="utf-8", errors="ignore") as f:
                        content = f.read()
                        if "SELECT " in content and " ." in content and "WHERE" in content:
                            self.add_finding("HIGH", "Posible concatenación SQL directa",
                                             "Se detectó patrón de consulta SQL construida mediante concatenación.", path)
                        if "md5(" in content or "sha1(" in content:
                            self.add_finding("MEDIUM", "Uso de algoritmo de hash inseguro",
                                             "Se encontró md5() o sha1(). Utilice Argon2id o password_hash().", path)

        # Check .env
        env_path = os.path.join(project_root, ".env")
        if os.path.exists(env_path):
            with open(env_path, "r", encoding="utf-8") as f:
                env_content = f.read()
                if "GEMINI_API_KEY=AIza" in env_content:
                    self.add_finding("CRITICAL", "API Key expuesta en .env",
                                     "Se detectó una clave API explícita guardada en el archivo de entorno.", ".env")

        if not self.findings:
            self.add_finding("INFO", "Auditoría de Seguridad completada sin fallos críticos",
                             "Todas las reglas estáticas de análisis de código limpio pasaron correctamente.")

        self.add_recommendation("Mantener actualizados las dependencias del contenedor y no subir secretos al repositorio Git.")
        self.save_report()

if __name__ == "__main__":
    agent = SecurityAgent()
    agent.run_audit()
