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

### Iteration 4 — debt closure and integration

- Inspected the Currencing-owned diffs and confirmed they are limited to factual documentation/readiness closure plus this orchestration journal.
- Attempted to create a dedicated `rc/currencing-readiness-20260912` branch, but the guarded branch-switch capability rejected the operation because the worktree contains pre-existing `.gating/**` changes.
- Created signed commit `6098cbe452402cd5b249fe5d7431b500c9b97ef3` (`docs: align Currencing RC readiness`) with exactly four Currencing-owned files; none of the 13 `.gating/**` changes were staged or committed.
- Push/set-upstream was attempted through Console MCP and correctly blocked by `working_tree_dirty`; the current branch remains one commit ahead of `origin/master`.
- No stash, reset, checkout, deletion, or unrelated commit was used to bypass the guard.

### Iteration 5 — final acceptance and handoff

- Component-local RC acceptance is green: strict Composer validation, all 11 Currencing gates, PHPUnit 42/42 with 549 assertions, PHPStan with no errors, and PHP-CS-Fixer dry-run with 0 fixable files.
- Canon038 legacy YAML names are absent from active source/config; only historical journal references remain.
- The authorized Currencing scope has no remaining code/runtime defect identified in this run.
- Integration tail is bounded and external to Currencing ownership: publish commit `6098cbe452402cd5b249fe5d7431b500c9b97ef3` only after the pre-existing `.gating/**` worktree changes are independently resolved or moved by their owner, then open/merge the normal remote integration path.

### Follow-up — `.gating/**` worktree closure

- User explicitly authorized resolving the previously out-of-scope `.gating/**` worktree.
- The dirty set was classified as a coherent Gating update: PHPUnit 13 tooling, Canon039/Canon040 registration/calibration, coverage/cache ignores, and targeted rule corrections.
- Fixed one factual Windows portability defect in `.gating/composer.json`: `test:unit` now invokes `@php vendor/bin/phpunit tests/Unit` instead of relying on a global `phpunit` command.
- Installed `.gating` dependencies from the existing lock file successfully.
- `.gating` verification: `composer validate --strict` pass; `composer test:unit` pass (1 test / 2 assertions); `composer test` calibration pass; `composer phpstan` pass; `composer cs:check` pass.
- `.gating` `rector:check` reports four pre-existing modernization candidates outside the original dirty set; they were not auto-applied to avoid expanding this closure wave.
- `.gating` self `composer gate` is not a valid consumer-copy acceptance gate because its local profile targets `App\\Example`, while the embedded tool namespace is `Gating\\Gate`; Canon039 itself passes and Canon040 correctly warns when persistent coverage evidence is absent.

## repository-implementation-20260913-currencing

### Iteration 1 — reconnaissance and baseline

- Workspace: `D:\\PhpstormProjects\\www\\Currencing`.
- Baseline branch/HEAD: `fix/gating-quality-green-20260912` at `1d8cf5a3904ecb4e1506ab04a92053465780a7ef`, tracking origin with ahead/behind `0/0`.
- Pre-existing worktree: 33 `.gating/**` changes, including in-progress Canon041/Canon042 Gating mirrors; these are treated as unrelated shared-gate work and must not be staged, rewritten, or absorbed by Currencing.
- Baseline `composer validate --strict --no-interaction`: pass.
- Baseline `composer currencing:gates`: pass, 11/11 component-local gates.

### Sources read and canonical mapping

- Currencing: `AGENTS.md`, `README.md`, `composer.json`, `composer.prod.json`, `manifest.yaml`, `CMCP_CHANGELOG.md`, `config/bundles.php`, readiness/inventory/release metadata and declared gate surface.
- Objecting, Cruding, Viewing and Interfacing: current `AGENTS.md` where present, `README.md`, `composer.json` and relevant manifest/package contracts; existing direct dependencies and local path/symlink wiring are confirmed.
- Collectioning and Tabling: current `README.md` and `composer.json`; both are Symfony bundles with package identities `collectioning/collection` and `tabling/table` and canonical bundle classes under `App\\Collectioning\\` and `App\\Tabling\\`.
- Canonization: authoritative `Canon022StandaloneApplicationDependencyBaselineRule.md`, `Canon039PhpTestToolingRule.md`, `Canon040PhpTestCoverageRule.md`, `Canon041BehavioralUiTestToolingRule.md`, `Canon042BehavioralUiCoverageRule.md`, plus `GUARD_MATRIX.md` and repository agent projection.
- Gating: current repository execution contract plus the pre-existing embedded `.gating/**` work confirms Canon041/042 are being materialized as executable mirrors, but those uncommitted shared changes are not Currencing-owned.

### Target-to-canon mapping

- Canon022 applies because Currencing owns `bin/console` + `config/bundles.php`: direct runtime baseline must include Cruding, Collectioning, Tabling, Viewing, Interfacing, Objecting and EasyAdmin. Currencing currently omits direct `collectioning/collection` and `tabling/table`; transitive availability through Cruding is explicitly insufficient.
- Canon023 applies to local development dependencies: new Collectioning/Tabling direct dependencies require sibling Composer path repositories with `symlink: true`.
- Canon024/033 apply to production manifest parity: `composer.prod.json` must receive matching direct package identities via production VCS repositories without local path repos.
- Canon039 applies and existing PHPUnit dependency/script is present, but the current repository lacks the full persistent branch-coverage contract required by the latest textual rule.
- Canon041 applies because Currencing directly requires FrameworkBundle and is standalone: repository-local `symfony/test-pack`, `symfony/panther`, `@playwright/test`, Playwright config and reproducible execution scripts are required.
- Canon042 requires persistent repository-local behavioral/UI coverage evidence produced by a reproducible workflow; missing evidence is warning-level, so it is a measurable follow-up rather than a reason to fabricate coverage.

### Market / maturity opening mixin

- Mature PHP money libraries model currency/amounts with exact arithmetic and explicit rounding instead of floats; mature rate ecosystems separate currency metadata from exchange-rate providers and commonly add cache/provider abstractions. Currencing's existing exclusion of FX sourcing/conversion therefore remains correct.
- RC safeguards relevant here are deterministic dependency/runtime topology and executable multi-layer test tooling; speculative FX features stay outside scope.

### RC-critical workstream selected

1. Close Canon022/023/024/033 direct dependency parity for Collectioning and Tabling in dev/prod manifests and bundle registration where runtime requires it.
2. Close Canon039/041 test-tooling contract with standard Symfony functional/browser tooling and repository-local Playwright execution surfaces.
3. Add deterministic behavioral/UI evidence production only to the extent it can be factually derived from declared Currencing surfaces; never invent percentages.
4. Re-run component gates, PHPUnit, PHPStan, coding style, Composer validation and applicable canon/Gating checks; keep all `.gating/**` changes untouched.

### Growth workstream — non-blocking

- Later expose Symfony Intl cash-fraction/cash-rounding metadata as an API/DX maturity feature if consumers need it.
- Keep FX rates, historical conversion and quote pricing in Exchanging, not Currencing.

### Iteration 2 — material implementation

- Added direct `collectioning/collection` and `tabling/table` runtime dependencies plus local symlink path repositories in `composer.json`; mirrored the package identities through production VCS repositories in `composer.prod.json` and registered both Symfony bundles in the standalone runtime.
- Added Symfony Test Pack and Panther development dependencies, repository-local Playwright dependency/configuration, a reproducible npm lock file, and generated-artifact ignores.
- Canonicalized the PHPUnit configuration from the legacy `phpunit.dist.xml` filename to `phpunit.xml.dist`, added source/coverage ownership and the Panther extension, and added persistent Xdebug path/branch coverage execution via `composer test:coverage`.
- Added a Symfony `WebTestCase` for `/currencing/conversion-boundary` and a Playwright real-browser boundary test with an automatically managed local PHP web server.
- Composer dependency resolution installed Collectioning/Tabling from the local sibling repositories and refreshed the lock graph; npm tooling installed successfully.

### Iteration 3 — verification and fix

- Initial PHPUnit verification exposed two real defects in the new proof path: an omitted test-class closing brace and a stale test-kernel identity (`App\\Kernel` versus the actual `App\\Currencing\\Kernel`). The syntax defect was fixed and the canonical PHPUnit config now binds the actual kernel explicitly because `.env.test` is path-policy protected.
- Real HTTP dispatch then exposed a pre-existing runtime wiring defect: the broad `App\\Currencing\\Service\\` resource overrode `controller.service_arguments` tags on `Service/Http/Currency`. `config/services/currency_services.yaml` now excludes `Service/Http/` from the broad registration, preserving the dedicated controller registration.
- `composer test`: pass, 43 tests / 552 assertions after fixes.
- `composer currencing:gates`: pass, 11/11 component gates.
- `composer phpstan`: pass, no errors.
- `composer cs:check`: initial line-ending finding in the new functional test was fixed with the repository formatter; repeat passes with 0 fixable files.
- `composer validate --strict --no-interaction`: pass.
- `composer test:coverage`: pass with Xdebug 3.5.1. Measured repository coverage is Lines 45.55% (307/674), Methods 30.54% (62/203), Branches 71.12% (234/329). Canon040 therefore leaves high line/method test debt; branch coverage clears its 70% threshold. These measurements are factual and no synthetic coverage evidence was created.
- npm audit initially required a lock file; a package-lock-only Playwright update created the reproducible lock, after which `npm audit --audit-level=high` passes with 0 vulnerabilities.
- First Playwright run correctly failed because no web server was running. After adding Playwright-managed standalone `php -S` lifecycle, the database-backed demo reached Doctrine and exposed local schema drift rather than a browser/tooling failure.
- PostgreSQL diagnostics show `currency_currency` has the legacy seven-column shape and zero rows, `currency_translation` is absent, and `doctrine_migration_versions` is empty. The guarded Doctrine dry-run sees the initial `Version20260905234900` CREATE migration as pending; it was deliberately not executed over the already-existing table.
- Browser E2E was therefore bounded to the DB-independent `/currencing/conversion-boundary` contract; `npm test` now passes 1/1 in a real browser while the stale local database remains explicitly documented integration debt.

### Iteration 4 — debt closure and integration preparation

- Read Canon037 directly after Symfony repeatedly regenerated `config/reference.php`. Canon037 explicitly forbids this generated artifact from Git history; `.gitignore` now excludes it and Console MCP performed index-only untracking while preserving the local generated file for diagnostics/runtime.
- Updated readiness documentation and release metadata with the real browser proof, measured Canon040 coverage debt, and exact local PostgreSQL migration-ledger/schema blocker. The former placeholder-credential risk is removed because database connectivity is now proven.
- Canon042 evidence remains intentionally unmaterialized: the current repository has executable PHP and browser proof but does not yet own a complete, defensible functional/behavioral/UI/critical denominator producer. Missing evidence is warning-level under the textual canon; fabricated counters are prohibited.
- Generic named `gating` execution is not available through the safe-check registry in this session (`Unknown check name: gating`); current acceptance uses the consulted textual Canonization rules plus executable Currencing/Composer/PHPUnit/PHPStan/PHP-CS-Fixer/npm/Playwright gates.
- Pre-existing `.gating/**` changes remain untouched and excluded from Currencing integration ownership.
- Signed Currencing integration commit created: `09fa8129acca5b8ae42b6a182e2fb4b8989b6f6d` (`Harden Currencing RC contracts`). It contains the Currencing-owned dependency, runtime, test-tooling, Canon037, documentation and lock/config changes; `.gating/**` is excluded.
- Push attempt through the authorized Console MCP writer was blocked by `working_tree_dirty` because the 33 unrelated `.gating/**` changes remain present. No stash/reset or cross-boundary commit was used to bypass that guard.

### Iteration 5 — final acceptance and handoff

- Post-commit `composer currencing:gates`: pass, 11/11.
- Post-commit `composer test`: pass, 43/43 tests with 552 assertions.
- Post-commit `npm test`: pass, 1/1 Playwright real-browser boundary test with repository-managed web server.
- Final pre-commit quality evidence also remains green: strict Composer validation, PHPStan no errors, PHP-CS-Fixer 0 fixable files, npm audit 0 vulnerabilities, and Xdebug coverage production succeeds.
- Canon022/023/024/033 direct dependency and package-wiring gaps are closed; Canon039 and Canon041 tooling contracts are executable; Canon037 generated-reference tracking is closed by index-only untracking plus ignore coverage.
- Remaining non-hard debt is explicit rather than hidden: Canon040 line/method coverage is 45.55%/30.54% (branches 71.12%), Canon042 has no fabricated denominator/evidence file, and the local PostgreSQL demo path remains blocked by an unmanaged legacy schema with an empty migration ledger. The pending initial CREATE migration was not applied destructively over that existing table.
- Currencing-owned repository changes are committed. The only worktree dirt after the final handoff commit is expected to remain the pre-existing `.gating/**` work; remote publication stays guard-blocked until that separate work is resolved by its owner.

## repository-implementation-20260914-currencing

### Reconnaissance and baseline

- Workspace: `D:\\PhpstormProjects\\www\\Currencing`; baseline branch `fix/gating-quality-green-20260912`, HEAD `0a787bd40686a1fdc199257a82f4e1c1ae7d53f9`, tracking origin and ahead by 2 commits.
- Pre-existing worktree dirt is confined to shared `.gating/**` changes (39 paths at baseline) and remains outside Currencing ownership.
- Read Currencing `AGENTS.md`, `README.md`, `composer.json`, prior orchestration journal, plus current Objecting, Cruding, Viewing, Interfacing, Collectioning, Tabling, Gating and Canonization contracts.
- Consulted normative Canonization material: `GUARD_MATRIX.md`, Canon043, Canon044 and Canon045, alongside previously applicable Canon000/007/008/017/018/019/022/023/024/033/037/038/039/040/041/042.

### Target-to-canon mapping

- Canon043: every locally linked first-party sibling must use exact `dev-master`; root development stability is `dev`; every path repository pins `options.versions[package] = dev-master`.
- Canon044: Currencing Entity mappings must keep Objecting-owned persisted system fields entity-native (`uuid`, `slug`, `created_at`, etc.), never `object_*`/`objecting_*` columns or mapped properties.
- Canon045: root development Composer repositories must expose the full reachable local first-party closure. Current contour covers direct siblings plus known transitive edges Cruding→Collectioning/Tabling, Tabling→Collectioning and Viewing→Interfacing.

### Market / maturity opening mixin

- Mature money implementations keep exact amount/currency/rounding semantics explicit; enterprise payment APIs operate in currency minor units whose scale varies by currency.
- Currencing therefore remains bounded to identity/metadata/precision/normalization/formatting/rounding policy and must not absorb FX sourcing, historical rates or quote pricing.

### RC-critical workstream

1. Close Canon043 development Composer identity drift without changing business behavior.
2. Re-resolve the lock graph against canonical sibling branch identities.
3. Verify Canon044/045 and the complete repository-declared quality/test/browser gate contour.
4. Integrate only Currencing-owned files; keep shared `.gating/**` work excluded.

### Growth workstream — non-blocking

- Later consider explicit cash-fraction/cash-rounding metadata exposure if a consumer needs it; keep FX ownership in Exchanging.

### Implementation

- Changed development `minimum-stability` from `stable` to `dev`.
- Canonicalized Collectioning and Tabling dependency constraints to exact `dev-master`.
- Added `options.versions` package identity pins for Collectioning, Cruding, Interfacing, Objecting, Tabling and Viewing local path repositories.
- Package-scoped Composer update refreshed the lock graph; first-party packages now resolve on `dev-master`. The same resolution also advanced `doctrine/orm` from 3.7.0 to 3.7.1 as an allowed dependency update of the selected graph.
- Hardened `playwright.config.js` so local E2E proof uses an isolated Currencing port (`8127` by default) and never reuses an unrelated pre-existing server. This closes a factual acceptance-gate defect discovered when port 8000 was serving another workspace application.

### Verification and acceptance

- `composer validate --strict --check-lock --no-interaction`: pass.
- Canon044 targeted scans under `src/`: no `object_` or `objecting_` mapped-field candidates found.
- `composer currencing:gates`: pass, 11/11 component gates.
- `composer test`: pass, 43 tests / 552 assertions.
- `composer phpstan`: pass, no errors across 97 analyzed files.
- `composer cs:check`: pass, 0 fixable files across 105 files.
- `composer test:coverage`: pass; measured Lines 45.55% (307/674), Methods 30.54% (62/203), Branches 71.12% (234/329). Canon040 line/method debt remains explicit and non-hard; branch threshold is satisfied.
- `npm test`: pass, 1/1 real-browser Currencing boundary test after E2E server isolation.
- `npm audit --audit-level=high`: pass, 0 vulnerabilities at all reported severities.
- Direct execution of the embedded `.gating/gate.ps1` is blocked by Console MCP policy because repository PowerShell execution is restricted to `tool/` or `bin/`; no bypass was attempted. Normative Canon043/044/045 text was applied directly, while executable component gates and targeted deterministic evidence are green.

### Follow-up coverage hardening

- Continued strictly inside Currencing after the RC commit; shared `.gating/**` remains untouched.
- Expanded `CurrencyRoundingPolicyResolverTest` to cover every canonical rounding context and the unknown-policy failure path, targeting real policy-contract behavior rather than synthetic coverage.
- Added DTO/value-object contract assertions for `CurrencyChoiceDTO`, `CurrencyRoundingPolicyDTO`, and `CurrencyRoundingPolicyName`, plus public HTTP normalization validation-path coverage for JSON/form payloads, optional strings, rounding mode, and rounding context.
- The new malformed-JSON case exposed a real boundary defect: `CurrencyNormalizeHttpService::payload()` executed before the endpoint try/catch, so invalid JSON escaped as an uncaught `InvalidArgumentException`. The HTTP service now translates payload-decoding failures into the canonical HTTP 400 JSON error contract.
- Follow-up PHPUnit: pass, 66 tests / 620 assertions.
- Follow-up coverage: Lines 51.47% (350/680), Methods 35.47% (72/203), Branches 84.04% (279/332), Paths 22.00% (132/600). Line coverage is now above the Canon040 high-debt 50% boundary; method coverage remains the principal non-hard debt.
- `composer currencing:gates`: pass, 11/11; PHPStan: pass; PHP-CS-Fixer: pass after normalizing one test-file line ending; changed-PHP lint: pass for all 3 changed PHP files; Playwright: pass, 1/1 real-browser test on isolated port 8127.

### PostgreSQL reconciliation and post-merge parity repair

- The prior squash merge into `master` was not content-equivalent to the accepted feature head: early RC files such as the canonical PHPUnit/Playwright/generated-reference surfaces were absent. This repair branch therefore starts from accepted head `8f0803b52dc089297b4562fda4b4f47c4a7c5be1` so the next PR restores exact RC parity rather than reconstructing it piecemeal.
- The local legacy `currency_currency` table contained zero rows and the migration ledger was empty. The initial migration now supports an additive empty-legacy reconciliation path and explicitly aborts if a non-canonical legacy table contains rows; no table drop/recreate was used.
- Guarded migrations established `currency_translation`, canonical Objecting columns/indexes/FK, and a three-version Doctrine ledger. A read-only `schema:diff` script exposed final PostgreSQL identity/default/index-name drift, which was materialized through forward migrations.
- Full `doctrine:schema:validate` now passes mapping and database synchronization; `composer schema:diff` reports nothing to update.
- Real standalone `/currencing/demo` initially exposed a stale DQL path `objectState.objectActive`; `CurrencyRepository` now queries the actual embeddable property `objectState.active`, after which `/currencing/demo` returns HTTP 200.
- Playwright's managed server moved from default port 8127 to 8128 so the E2E lifecycle remains isolated from the Console MCP-managed demo runtime while preserving the `CURRENCING_E2E_PORT` override.

## repository-implementation-20260920-currencing

### Reconnaissance and baseline

- Workspace: `D:\\PhpstormProjects\\www\\Currencing`; branch `fix/currencing-rc-parity-db-rebased-20260914`, baseline HEAD `5a66cbb00c2e924405279cd03afbcbdd4c163ab7`, ahead of upstream by one commit.
- Pre-existing worktree dirt: 20 modified `.gating/**` files. They are shared Gating work and remain outside this Currencing change set.
- Read current Currencing `AGENTS.md`, `README.md`, `composer.json`, `manifest.yaml`, readiness/inventory/RC docs, runtime money services, entity, exception and parser tests.
- Read the current Objecting, Cruding, Viewing, Interfacing, Gating and Canonization repository contracts available through their AGENTS/README/Composer/manifest surfaces.
- Consulted normative Canonization rules Canon011, Canon012, Canon013, Canon017, Canon018, Canon021, Canon022, Canon030, Canon043, Canon044 and Canon045, including their Evidence Contracts.

### Target-to-canon mapping

- Canon011: monetary boundary failures stay explicit; integer-range overflow becomes `CurrencyInvalidAmountException` rather than a PHP arithmetic `TypeError`.
- Canon012: money normalization stays typed through DTOs, enums and service interfaces.
- Canon013: RC logic is deterministic production behavior, not placeholder success.
- Canon017/018: current runtime identity remains `currencing/currency` => `App\\Currencing\\` plus `Currency*`.
- Canon021: no generic CRUD is introduced; Cruding ownership is unchanged.
- Canon022/043/045: existing platform dependencies and local `dev-master` path-repository contour are preserved.
- Canon030/044: no Doctrine schema or Objecting field mapping change is introduced.

### Market / maturity opening mixin

- Mature money libraries keep amount/currency/rounding exact and avoid accidental floating-point arithmetic in normalization.
- RC therefore requires deterministic minor-unit integer-range handling. FX sourcing, historical rates and quote pricing remain outside Currencing.
- Growth remains separate: cash-fraction/cash-rounding metadata exposure is a later API/DX capability.

### RC-critical workstream selected

- Harden `CurrencyDecimalParser` against integer overflow and `PHP_INT_MIN` formatting failure.
- Add boundary regression coverage and verify without absorbing pre-existing `.gating/**` work.

### Implementation

- Replaced intermediate integer multiplication/rounding with decimal-string scaling and carry arithmetic.
- Added supported minor-unit range validation and signed integer boundary conversion.
- Added `CurrencyInvalidAmountException::outOfRange()`.
- Added tests for `PHP_INT_MAX`, `PHP_INT_MIN`, overflow rejection and rounding at the integer boundary.

### Verification and dependency blocker

- Changed-PHP syntax lint passes for all observed changed PHP files, including all three Currencing files in this wave.
- `composer validate --strict --check-lock --no-interaction`: pass.
- `composer phpstan`: pass, no errors across 97 analyzed files.
- `composer currencing:gates`: pass, all 11 declared Currencing gates green.
- Full `composer test` executes 69 tests and 625 assertions; 68 tests complete successfully, while the standalone functional boot test errors before request dispatch because the current Cruding dependency container definition loses the explicit tagged-iterator argument for `CrudBulkMutationHandlerResolver`.
- Cruding's own `config/services.yaml` defines the resolver with `$handlers: !tagged_iterator cruding.bulk_mutation_handler`, but a later broad `App\\Cruding\\Resolver\\` resource registration redefines the same service. This is a Cruding-owned dependency defect; Currencing must not hard-code Cruding's internal resolver to mask it.
- `composer cs:check` is not globally green because it reports pre-existing formatting/line-ending debt in files outside this wave (including two existing migrations and `CurrencyRepository.php`). No broad formatter was run, to avoid absorbing unrelated changes.

### RC status

- The selected Currencing monetary boundary defect is implemented and covered by regression tests.
- Component-local structural/runtime/API/schema/readiness gates are green.
- Full standalone PHPUnit acceptance remains externally blocked by the current Cruding service-definition regression; fixing Cruding requires a separate task in its owning repository.



