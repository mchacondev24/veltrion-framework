#!/usr/bin/env python3
import json
import sys
import os
from datetime import datetime

class AgentBase:
    def __init__(self, name: str, category: str):
        self.name = name
        self.category = category
        self.findings = []
        self.recommendations = []

    def add_finding(self, severity: str, title: str, description: str, location: str = ""):
        self.findings.append({
            "severity": severity,  # CRITICAL, HIGH, MEDIUM, LOW, INFO
            "title": title,
            "description": description,
            "location": location
        })

    def add_recommendation(self, rec: str):
        self.recommendations.append(rec)

    def generate_report(self) -> dict:
        severity_counts = {"CRITICAL": 0, "HIGH": 0, "MEDIUM": 0, "LOW": 0, "INFO": 0}
        for f in self.findings:
            sev = f.get("severity", "INFO")
            severity_counts[sev] = severity_counts.get(sev, 0) + 1

        return {
            "agent": self.name,
            "category": self.category,
            "timestamp": datetime.now().isoformat(),
            "status": "PASSED" if severity_counts["CRITICAL"] == 0 and severity_counts["HIGH"] == 0 else "WARNINGS_FOUND",
            "summary": {
                "total_findings": len(self.findings),
                "severity_counts": severity_counts
            },
            "findings": self.findings,
            "recommendations": self.recommendations
        }

    def save_report(self, output_dir: str = "storage/reports"):
        os.makedirs(output_dir, exist_ok=True)
        report = self.generate_report()
        filepath = os.path.join(output_dir, f"{self.name.lower()}_report.json")
        with open(filepath, "w", encoding="utf-8") as f:
            json.dump(report, f, indent=2, ensure_ascii=False)
        print(json.dumps(report, indent=2, ensure_ascii=False))
        return report
