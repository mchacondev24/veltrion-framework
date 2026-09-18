# Commercial Packaging & Code Protection System

## Overview

The Veltrion Framework features an optional **Commercial Packaging, Protection, Licensing & Distribution System**.
While the core framework remains **100% Open Source**, applications built on top of the framework can be packaged, protected, and licensed for commercial software distribution.

```
+-------------------------------------------------------------+
|               Veltrion Open Source Framework                |
+-------------------------------------------------------------+
                              |
                              v
                  Packaging & Release Engine
                              |
     +------------------------+------------------------+
     |                        |                        |
     v                        v                        v
Code Protection           Licensing Subsystem      Windows Distribution
(Minifier & Guards)     (Ed25519 & Machine ID)     (Launchers & Installers)
```

## CLI Commands

### Packaging Engine
- `php cli package:build [AppName] [CustomerName]` - Builds a protected commercial zip package
- `php cli package:verify [path]` - Verifies SHA-256 integrity and structure
- `php cli package:list` - Lists available commercial release packages

### Code Protection Engine
- `php cli protect:check` - Displays active protection provider status

### Licensing & Machine Identity
- `php cli license:machine` - Displays local technical hardware fingerprint and unique Machine ID (`XXXX-XXXX-XXXX-XXXX`)
- `php cli license:create <Prod> <Customer> [MachineID]` - Generates a digitally signed license file
- `php cli license:inspect [license.json]` - Inspects and verifies digital signature and expiration
- `php cli license:install <license.json>` - Installs license into `storage/license.json`
- `php cli license:revoke` - Removes installed license

### Windows Packaging Engine
- `php cli windows:build [AppName]` - Generates Windows bundle, batch launch wrapper & Inno Setup `.iss` script

### Commercial Release Pipeline
- `php cli release:commercial [AppName] [Customer]` - Runs full commercial release pipeline including Quality Gates, Code Protection, License generation, and Release Report.
