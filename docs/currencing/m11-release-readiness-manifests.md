# Currencing M11: Release/readiness manifests

M11 packages Currencing as a clearer Symfony-delivery component by adding manifest,
agent instructions, install notes, inventory, and readiness metadata.

## Goal

Make the component inspectable by humans and automation without relying on chat context.

## Added files

```text
manifest.yaml
agent.md
docs/currencing/install.md
docs/currencing/inventory.md
docs/currencing/readiness.md
```

## Status

Currencing is architecture/business-ready for:

- currency metadata catalog;
- ISO code validation;
- minor-unit precision;
- money normalization;
- display formatting;
- selector/read output;
- neighbor integration contracts;
- monetary rounding policy selection;
- conversion-boundary handshake.

Runtime proof, host-app wiring, migrations execution, and browser/UI proof are separate
final hardening steps.
