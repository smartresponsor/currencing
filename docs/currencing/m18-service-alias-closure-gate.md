# M18 Service alias closure gate

M18 adds a framework-free gate that reduces Symfony container/autowiring risk before local `bin/console` proof.

The gate scans `src/` for constructor-injected `App\ServiceInterface\Currency\*Interface` contracts and verifies that:

- every injected Currencing service interface has an explicit alias in `config/services/currencing.yaml`;
- every alias points to an existing `App\Service\Currency\*` implementation;
- every implementation explicitly implements the aliased interface;
- `config/packages/currencing.yaml` remains parameter/package-only and does not duplicate service aliases.

Run it with the other framework-free gates:

```bash
php tools/currencing-service-alias-closure-check.php
```

This milestone does not add business scope. It only hardens RC runtime proof readiness.
