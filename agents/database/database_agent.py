#!/usr/bin/env python3
import sys
import os
import sqlite3
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from core.agent_base import AgentBase

class DatabaseAgent(AgentBase):
    def __init__(self):
        super().__init__("DatabaseAgent", "database")

    def run_audit(self, project_root: str = "."):
        db_path = os.path.join(project_root, "storage", "database.sqlite")
        migrations_dir = os.path.join(project_root, "database", "migrations")

        if not os.path.exists(migrations_dir):
            self.add_finding("MEDIUM", "Carpeta de migraciones no encontrada", "database/migrations/ no existe.", "database/migrations")
        else:
            m_files = [f for f in os.listdir(migrations_dir) if f.endswith(".php") or f.endswith(".sql")]
            if not m_files:
                self.add_finding("LOW", "Sin archivos de migración", "No se encontraron migraciones registradas.", migrations_dir)

        if os.path.exists(db_path):
            try:
                conn = sqlite3.connect(db_path)
                cursor = conn.cursor()
                cursor.execute("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%';")
                tables = cursor.fetchall()
                if not tables:
                    self.add_finding("LOW", "Base de datos SQLite vacía", "No hay tablas creadas en storage/database.sqlite", db_path)
                else:
                    self.add_finding("INFO", f"Base de datos operativa con {len(tables)} tablas", f"Tablas: {[t[0] for t in tables]}", db_path)
                conn.close()
            except Exception as e:
                self.add_finding("HIGH", "Error de lectura en SQLite", str(e), db_path)
        else:
            self.add_finding("INFO", "Base de datos SQLite aún no instanciada", "Ejecute 'php cli db:migrate' para generar la base de datos.", db_path)

        self.add_recommendation("Usar siempre migraciones versionadas y ejecutar backups con 'php cli db:switch' antes de cambiar de motor.")
        self.save_report()

if __name__ == "__main__":
    agent = DatabaseAgent()
    agent.run_audit()
