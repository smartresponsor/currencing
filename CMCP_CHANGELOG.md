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

### Iteration 4 — debt closure and integration

- Signed commit created: `f904e72792508cc08ee0ca969899ae9ca34f3131` (`Canonize Currencing config identity`).
- Commit includes 35 Currencing-owned files and excludes all four pre-existing `.gating/**` modifications.
- Post-commit worktree contains only the four pre-existing `.gating/**` modifications; Currencing task changes are committed.
- Push attempt through the authorized safe Git writer was blocked by `working_tree_dirty` because those four unrelated `.gating/**` files remain modified. They were not stashed, reset, staged, or committed because they predate this task and are outside this task's ownership.
- Branch after commit: `release/currencing-canonicalization-20260910`, ahead of upstream by one commit at this point.

## engine-20260912081325-currencing-887257

### Iteration 1 — reconnaissance and baseline

- Workspace: `D:\\PhpstormProjects\\www\\Currencing`.
- Baseline branch: `checkpoint/verify-master-after-yaml-retire`.
- Baseline HEAD: `24e8b0feb8fecfddf4f3e6fedf74e3386c80b080`, tracking `origin/master`, ahead `0`, behind `0`.
- Pre-existing worktree changes: 13 `.gating/**` changes; they are unrelated shared-gate work and remain outside Currencing ownership for this task.
- `composer validate --strict --no-interaction`: pass.
- `composer currencing:gates`: pass, all 11 repository gates green.
- Legacy Canon038 config names are absent from active source/config and survive only as historical journal references.

### Sources read and contracts applied

- Currencing: `AGENTS.md`, `README.md`, `composer.json`, `manifest.yaml`, `CMCP_CHANGELOG.md`, readiness/inventory/release metadata, active `currency_*` Symfony config, repository status and scripts.
- Objecting: `AGENTS.md`, `README.md`, `composer.json`; Currencing keeps entity-native identity/system fields and consumes Objecting field packs without leaking Doctrine entities across boundaries.
- Cruding: `AGENTS.md`, `README.md`, `composer.json`; generic CRUD mechanics/routes remain outside Currencing.
- Viewing: `AGENTS.md`, `README.md`, `composer.json`; rendering/fallback stays in the presentation boundary.
- Interfacing: `AGENTS.md`, `README.md`, `composer.json`; Currencing exports DTO/service/template context rather than owning shell composition.
- Gating: `AGENTS.md`, `README.md`, `composer.json`; executable enforcement is separate from normative Canonization text.
- Canonization: `AGENTS.md`, `README.md`, `composer.json`, architecture guard matrix, and `Canon038ConfigYamlSubjectPrefixRule.md`; previous mapped rules Canon000/007/008/010/017/018/019/033 remain applicable and are revalidated against current package identity/tree.

### Target-to-canon mapping

- Canon000/018: `currencing/currency` => component namespace `App\\Currencing\\` and subject prefix `Currency*`.
- Canon007: PSR-4 identity remains `App\\Currencing\\ => src/` with role-first tree.
- Canon008/023: Objecting, Cruding, Viewing and Interfacing are explicit Composer dependencies with local symlink path repositories.
- Canon017: current docs/release metadata must describe the actual current runtime and verification state.
- Canon019: no `src/Domain`, Port, Adapter, Adaptor, Resource or Surface architecture root.
- Canon033: dev/prod Composer identity parity applies when the production manifest exists.
- Canon038: active component-owned config filenames use the `currency_` subject prefix; retired `currencing*.yaml` names are no longer active.

### Market / maturity opening mixin

- Mature money libraries such as Brick Money treat currency, context/scale and explicit rounding as first-class immutable semantics; Symfony Intl exposes canonical currency metadata including fraction digits and cash rounding metadata.
- SaaS/enterprise payment systems generally separate currency metadata/normalization from exchange-rate sourcing and quote pricing. Currencing's boundary is therefore correctly centered on metadata, precision, normalization, formatting and policy, with FX ownership remaining in Exchanging.
- Baseline expectation: exact decimal/minor-unit handling, explicit rounding policy, stable ISO metadata, deterministic validation and non-leaky integration DTOs.
- Advanced growth expectation: expose cash-fraction/cash-rounding metadata and richer diagnostics without moving FX pricing/provider responsibility into Currencing.

### RC-critical workstream selected

1. Close factual documentation/readiness drift around the already-shipped template-context surface and current verification status.
2. Run PHPUnit, PHPStan and coding-style gates against the current master-equivalent tree.
3. Repair only factual in-scope failures; keep all `.gating/**` changes untouched.
4. Integrate a coherent Currencing-only change set and verify final branch/worktree state.

### Growth workstream — non-blocking

- Consider a later cash-rounding metadata API/DX extension using Symfony Intl cash fraction/rounding information.
- Keep exchange-rate sourcing, historical FX and conversion pricing out of Currencing.

### Iteration 2 — material implementation

- Updated `docs/currencing/inventory.md` to include the shipped `CurrencyTemplateContextDTO`, `CurrencyTemplateContextProviderInterface`, and `GET /currencing/template-context` surface.
- Updated `docs/currencing/readiness.md` so component-local verification no longer lists PHPUnit/PHPStan as outstanding after they have been executed successfully.
- Updated `delivery/release/currencing-rc-readiness.json` to reflect the current local RC proof while leaving host/browser/remote CI concerns explicitly external.
- No source/runtime behavior was broadened; the change is factual Canon017 documentation/readiness closure only.

### Iteration 3 — verification and fix

- `composer currencing:gates`: pass after documentation/readiness edits, all 11 repository gates green.
- `composer test`: pass, 42 tests / 549 assertions on PHP 8.4.13.
- `composer phpstan`: pass, 96 files, no errors.
- `composer cs:check`: pass, 0 fixable files across 104 files.
- `composer validate --strict --no-interaction`: pass.
- No additional in-scope runtime/code failures were found; `.gating/**` remains explicitly excluded from this task.


