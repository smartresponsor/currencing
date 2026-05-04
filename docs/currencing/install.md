# Currencing install notes

## Component root

Expected project path in the current workspace:

```powershell
D:\PhpstormProjects\www\Currencing
```

## Apply touched-files patch

Use the generated apply script for each wave:

```powershell
powershell -ExecutionPolicy Bypass -File "C:\Users\Admin\Downloads\apply_currencing_<wave>_touched.ps1" `
  -ProjectRoot "D:\PhpstormProjects\www\Currencing" `
  -PatchZip "C:\Users\Admin\Downloads\currencing_<wave>_touched.zip"
```

## Post-apply commands

```powershell
cd D:\PhpstormProjects\www\Currencing
composer dump-autoload
php bin/console cache:clear
```

If Currencing is mounted/symlinked into a host Symfony app, also clear the host app cache.

## Required Symfony concerns

Currencing expects ordinary Symfony wiring:

- service autowiring/autoconfiguration;
- Doctrine ORM;
- Twig for the M9 demo surface;
- routing attributes;
- PHP attributes for Doctrine mapping.

## Twig namespace

M9 uses:

```yaml
twig:
  paths:
    '%kernel.project_dir%/src/Resources/views': Currencing
```

## Database

Canonical table:

```text
currency_currency
```

All future Currencing-owned tables must start with:

```text
currency_
```
