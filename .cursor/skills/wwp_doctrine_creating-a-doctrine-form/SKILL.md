---
name: creating-a-doctrine-form
description: >-
  Creates a WonderWP ModelForm for a Doctrine entity admin CRUD: newField() overrides,
  field widgets by type, and i18n labels via {field}.trad keys. Use when building or
  extending the add/edit form for a Doctrine-backed entity.
metadata:
  domain: wonderwp-doctrine
---

# Creating a Doctrine ModelForm

## Goal

Produce `includes/Form/{Entity}Form.php` extending `ModelForm` for admin create/edit of a Doctrine entity.

## Rules

- **Prerequisite:** entity exists (**creating-a-doctrine-entity**).
- **Template:** [assets/EntityForm.php.template](./assets/EntityForm.php.template).
- **Placeholders:** `{Namespace}`, `{Entity}`, `{EntityLower}`, `{PluginConst}` — see [doctrine-archetype.md](../../references/doctrine-archetype.md).
- Labels use `__($fieldName . '.trad', WWP_{PluginConst}_TEXTDOMAIN)` — add matching entries in the plugin `languages/` folder.
- Override `newField(EntityAttribute $attr)` per field type; fall back to `parent::newField($attr)` for simple fields.
- Register the form class on the Manager via `formName` config (**wiring-doctrine-manager-and-services**).

## Workflow

### Step 1: Gather requirements

**Do:**

1. Confirm `{Entity}`, `{Namespace}`, `{PluginConst}`, text domain constant.
2. List form fields and widget types (text, textarea, select, checkbox, hidden, custom).
3. Note read-only or conditional fields (e.g. slug hidden on create).

**Success criteria:** field → widget mapping is clear.

### Step 2: Create the Form class

**Do:**

1. Ensure `includes/Form/` exists.
2. Copy [assets/EntityForm.php.template](./assets/EntityForm.php.template) to `{Entity}Form.php`.
3. Replace placeholders.

**Success criteria:** class extends `ModelForm` with correct namespace.

### Step 3: Implement newField() cases

**Do:**

1. Add `switch ($fieldName)` cases for non-default widgets.
2. Set labels on fields that support `setLabel()`.
3. Match validation expectations (required fields handled by parent form where possible).

**Success criteria:** each non-trivial entity field has an explicit case or documented default.

### Step 4: i18n

**Do:**

1. For each `{field}.trad` key, add French (and other locale) strings under `languages/`.
2. Run or schedule language file copy on activation if the Activator supports it.

**Success criteria:** admin labels render via gettext.

### Step 5: Register on Manager

**Do:**

1. Open `{Plugin}Manager.php`.
2. Confirm `$this->setConfig('formName', … {Entity}Form::class)` and `MODEL_FORM_SERVICE_NAME` registration (see **wiring-doctrine-manager-and-services**).

**Success criteria:** admin add/edit uses the new form.

## Anti-patterns

- Do not duplicate entity mapping logic in the form — read values from the entity instance.
- Do not hardcode French strings in PHP — use `.trad` keys.
