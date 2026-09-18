#!/usr/bin/env python3
import sys
import os
import json
import subprocess
from datetime import datetime

class AgentOrchestrator:
    def __init__(self, project_root: str = "."):
        self.project_root = os.path.abspath(project_root)
        self.agents_dir = os.path.join(self.project_root, "agents")
        self.reports_dir = os.path.join(self.project_root, "storage", "reports")
        os.makedirs(self.reports_dir, exist_ok=True)

        self.registered_agents = {
            "qa": "qa/qa_agent.py",
            "security": "security/security_agent.py",
            "architecture": "architecture/architecture_agent.py",
            "database": "database/database_agent.py",
            "dependencies": "dependencies/dependencies_agent.py",
            "e2e": "e2e/e2e_agent.py",
            "performance": "performance/performance_agent.py",
            "ux": "ux/ux_agent.py",
            "business": "business/business_agent.py",
            "docs": "documentation/documentation_agent.py",
            "documentation": "documentation/documentation_agent.py"
        }

    def run_agent(self, agent_name: str) -> dict:
        if agent_name not in self.registered_agents:
            return {"error": f"Agente '{agent_name}' no registrado."}

        rel_path = self.registered_agents[agent_name]
        full_path = os.path.join(self.agents_dir, rel_path)

        if not os.path.exists(full_path):
            return {"error": f"Script del agente no encontrado en: {full_path}"}

        print(f"  ▶ Ejecutando Agente: {agent_name.upper()} ({rel_path})...")
        cmd = ["python3", full_path]
        proc = subprocess.run(cmd, capture_output=True, text=True, cwd=self.project_root)

        report_file = os.path.join(self.reports_dir, f"{agent_name.lower()}agent_report.json")
        if not os.path.exists(report_file):
            # Try fallback naming pattern
            report_file = os.path.join(self.reports_dir, f"{agent_name.lower()}_report.json")

        if os.path.exists(report_file):
            try:
                with open(report_file, "r", encoding="utf-8") as f:
                    return json.load(f)
            except Exception as e:
                return {"error": f"Error al leer reporte de {agent_name}: {str(e)}"}

        return {"output": proc.stdout, "status": "COMPLETED"}

    def run_all(self) -> dict:
        print("🤖 [ORCHESTRATOR] Iniciando orquestación de suite completa de agentes Python...\n")
        results = {}
        unique_agents = ["security", "architecture", "database", "dependencies", "qa", "e2e", "performance", "ux", "business", "docs"]

        for name in unique_agents:
            res = self.run_agent(name)
            results[name] = res

        summary = self.generate_summary(results)
        return summary

    def generate_summary(self, results: dict) -> dict:
        total_findings = 0
        total_critical = 0
        total_high = 0

        summary_data = {
            "timestamp": datetime.now().isoformat(),
            "agents_executed": len(results),
            "agents": results
        }

        # Save summary JSON
        summary_json = os.path.join(self.reports_dir, "summary.json")
        with open(summary_json, "w", encoding="utf-8") as f:
            json.dump(summary_data, f, indent=2, ensure_ascii=False)

        # Save summary Markdown
        summary_md = os.path.join(self.reports_dir, "summary.md")
        md_lines = [
            "# Reporte Unificado de Orquestación de Agentes Veltrion",
            f"- **Fecha**: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}",
            f"- **Agentes Ejecutados**: {len(results)}",
            "",
            "## Resumen por Agente",
            ""
        ]

        for agent, data in results.items():
            status = data.get("status", "PASSED")
            findings_count = data.get("summary", {}).get("total_findings", 0)
            md_lines.append(f"### Agente `{agent.upper()}` - Estado: **{status}**")
            md_lines.append(f"- Hallazgos totales: {findings_count}")
            md_lines.append("")

        with open(summary_md, "w", encoding="utf-8") as f:
            f.write("\n".join(md_lines))

        print(f"\n✔ [ORCHESTRATOR] Reporte unificado generado en {summary_json} y {summary_md}")
        return summary_data

if __name__ == "__main__":
    target = sys.argv[1] if len(sys.argv) > 1 else "all"
    orchestrator = AgentOrchestrator()
    if target == "all":
        orchestrator.run_all()
    else:
        res = orchestrator.run_agent(target)
        print(json.dumps(res, indent=2, ensure_ascii=False))
