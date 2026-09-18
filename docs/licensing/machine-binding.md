# Cryptographic Licensing & Machine Binding Architecture

## Overview

The Veltrion Licensing Engine provides offline-first, machine-bound digital licensing backed by cryptographic signatures (Ed25519/Sodium and HMAC-SHA256).

## Key Features

1. **Machine Binding**: Identifies host hardware using OS, CPU ID, Disk/System serials, and hostname to construct a stable 16-character Machine ID (`XXXX-XXXX-XXXX-XXXX`).
2. **Cryptographic Signatures**: The developer keeps the private/secret key securely on build servers. The distributed application verifies signatures using public keys or offline HMAC matching.
3. **Feature-Based Licensing**: Licenses can grant specific module access (e.g., `POS`, `INVENTORY`, `REPORTS`, `AI`).
4. **Version Range Compatibility**: Supports SemVer restrictions (e.g., `1.x`, `>=2.0 <3.0`).
5. **Expiration & Trial Support**: Supports perpetual, trial, demo, subscription, and grace period policies.
6. **Integrity Enforcement**: Detects file tampering (`TAMPER_DETECTED`) without performing destructive actions.
