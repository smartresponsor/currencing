# CMCP orchestration journal

## engine-20260911142557-currencing-c7ae81

### Iteration 1 — reconnaissance and baseline

- Workspace: `D:\PhpstormProjects\www\Currencing`
- Baseline branch: `release/currencing-canonicalization-20260910`
- Baseline HEAD: `758109fa3bce0e0d20a6b3e3cd3cc011c1557162`
- Upstream: `origin/release/currencing-canonicalization-20260910`, ahead `0`, behind `0`.
- Pre-existing worktree changes: four `.gating/**` files. They are treated as pre-existing shared-gate work and are not part of this Currencing patch wave.
- `composer validate --strict`: pass.
- `composer currencing:gates`: pass before this task's changes.

### Sources read

- Currencing: `AGENTS.md`, `README.md`, `composer.json`, `manifest.yaml`, representative runtime/config/entity files and local gate references.
- Objecting: `AGENTS.md`, `README.md`, `composer.json`, `MANIFEST.json`; applied its entity-native system-column and field-pack ownership contract.
- Cruding: `AGENTS.md`, `README.md`, `composer.json`; Currencing must keep generic CRUD mechanics outside this component.
- Viewing: `AGENTS.md`, `README.md`, `composer.json`; rendering remains a presentation-boundary concern.
- Interfacing: `AGENTS.md`, `README.md`, `composer.json`; shell/template ownership remains outside Currencing.
- Gating: `AGENTS.md`, `README.md`, `composer.json`; executable canon remains separate from normative Canonization text.
- Canonization: architecture README plus Canon000, Canon007, Canon008, Canon010, Canon017, Canon018, Canon019, Canon033 and Canon038 normative rule files.

### Target-to-canon mapping

- Canon000: `currencing/currency` establishes `Currency*` as the PHP subject prefix.
- Canon007: filesystem, namespace and declared type must remain literal under `App\\Currencing\\ => src/`.
- Canon008: Objecting, Cruding, Viewing and Interfacing are explicit runtime Composer dependencies with local path repositories.
- Canon010: identity/config migration must update code, tests, tools, manifests and documentation together.
- Canon017: current documentation/manifests must describe the current runtime rather than the retired bare-`App\\` topology.
- Canon018: Composer identity `currencing/currency` maps to component namespace `App\\Currencing\\` and subject prefix `Currency*`.
- Canon019: no `src/Domain`, Port/Adapter/Adaptor or competing architecture root.
- Canon033: development/production manifest identity parity applies if `composer.prod.json` exists.
- Canon038: component-owned YAML filenames must use the `currency_` subject prefix; current `doctrine_currencing.yaml` is explicitly a non-canonical shape under the rule.

### Market / maturity baseline

- Mature PHP money libraries keep amount/currency/rounding semantics explicit and immutable-oriented.
- Symfony Intl already owns ICU currency metadata including symbols, fraction digits and cash-rounding metadata.
- Currencing therefore keeps FX sourcing/conversion pricing outside its responsibility and uses metadata/normalization/formatting as its bounded capability.

### RC-critical workstream selected

1. Reconcile manifest/documentation identity with `currencing/currency` and `App\\Currencing\\`.
2. Rename component-owned YAML configuration to Canon038 `currency_*` names and update every tracked caller/reference.
3. Re-run structural/runtime/composer/static/test gates and repair only factual in-scope failures.
4. Integrate only Currencing-owned changes; never absorb the pre-existing `.gating/**` modifications into this task's commit.

### Growth workstream (not RC blocking)

- Evaluate explicit cash-fraction/cash-rounding metadata exposure from Symfony Intl as a later API/DX maturity item.
- Do not add FX-rate sourcing, historical conversion, or payment/order/tax business ownership to Currencing.

### Iteration 2 — material implementation

- Added Canon038 subject-prefixed configuration surfaces: `currency_component.yaml`, `currency_doctrine.yaml`, `currency_routes.yaml`, and `currency_services.yaml`.
- Retargeted runtime bootstraps, tests, tools, release metadata, manifest, and current documentation to those canonical paths.
- Reconciled `manifest.yaml` with Composer identity `currencing/currency`, namespace `App\\Currencing`, and actual public service/entity contracts.
- Reconciled README/AGENTS/current operational documentation from retired bare `App\\...` examples to the actual `App\\Currencing\\...` runtime.
- Repository-wide searches show zero callers of the four legacy YAML paths.
- Physical deletion of the four legacy YAML files is blocked: the authorized repository writer rejects file deletion and the task capability envelope explicitly forbids destructive operations. No shell or policy bypass was attempted.

### Iteration 3 — verification and fix

- First post-migration gate pass exposed an omitted final money-normalize route, an omitted template-context service alias, and stale old-path assertions in runtime gates; all were fixed.
- `composer currencing:gates`: pass (11/11 repository gates).
- `composer test`: pass, 42 tests / 549 assertions.
- `composer phpstan`: initial two `property.unusedType` findings on Doctrine generated IDs; resolved with narrow local `@phpstan-ignore property.unusedType` comments documenting Doctrine assignment semantics; re-run passes.
- `composer cs:check`: pass, 0 fixable files.
- `composer validate --strict --no-interaction`: pass.
- Changed-PHP lint: pass for all 14 changed PHP files observed by the runner; four are pre-existing `.gating/**` changes and remain outside this task's ownership.
- Shared generic safe-check runner has no allowlisted check named `gating`; repository-declared Currencing gates are the available executable proof path in this task session.

### Remaining bounded RC tail

- Canon038 is not factually complete while the four legacy YAML filenames still physically exist, even though they have zero callers and their canonical replacements are active and verified.
- Required cleanup when deletion/rename capability is authorized: remove `config/packages/currencing.yaml`, `config/packages/doctrine_currencing.yaml`, `config/routes/currencing.yaml`, and `config/services/currencing.yaml` without changing their already-migrated callers.

