---
name: creating-custom-fields
description: >-
  Creates WonderWP custom fields registries for custom post types or taxonomies,
  including panel class scaffolding, meta definitions, and field generation,
  and wires them into the appropriate plugin. Use when the user wants to add
  custom fields to a CPT or taxonomy.
metadata:
  domain: wonderwp-backend
---

# Creating custom fields for custom post types or taxonomies

## Goal

Create a CustomFields registry class for a CPT or taxonomy under `includes/CustomFields`, with filtered meta definitions and field factories.

## Rules

- When the user wants to create custom fields, gather inputs.
- **Mandatory:** target plugin; target object type (custom post type or taxonomy only—other object types are not supported); the object key (CPT key or taxonomy key).
- **Optional:** the custom fields panel title—if not provided, extrapolate one from the object key and append “custom fields”.
- If the custom fields target a **post type**: extend `AbstractPostTypeCustomFieldsRegistry`.
- If they target a **taxonomy**: extend `AbstractTaxonomyCustomFieldsRegistry`.
- Other object types are not supported by this skill.

## Workflow

### Step 1: Prepare the folder and class name

**Do:**

1. Determine the plugin folder.
2. Check for an `includes/CustomFields` folder:
   - If it doesn’t exist, create it.
3. Determine the class name:
   - If a panel title is given: slugify the title, convert to CamelCase, and append `CustomFields`.
   - Otherwise: base it on the object key in CamelCase with `CustomFields` suffix.

**Success criteria:** Folder and class name are decided.

### Step 2: Select base class and namespace

**Do:**

- If the custom fields target a **post type**:
  - Extend `AbstractPostTypeCustomFieldsRegistry`.
- If they target a **taxonomy**:
  - Extend `AbstractTaxonomyCustomFieldsRegistry`.

Locate the appropriate PHP namespace from the plugin folder and apply it to the new class.

**Success criteria:** Base class matches CPT vs taxonomy target.

### Step 3: Shared setup details

**Do:**

- Locate the plugin textdomain from the root plugin file.
- Prepare a panel key:
  - Compute it from the class name as snake_case and append `_metabox`.

**Success criteria:** Textdomain and panel key are identified.

### Step 4: Post type custom fields registry

**Do:**

When the target is a post type, copy [assets/PostTypeCustomFieldsRegistry.php.tpl](assets/PostTypeCustomFieldsRegistry.php.tpl) into the new class file.

Notes:

- Prefer using the CPT class’s `provideKey()` method (if it implements `HasCustomTypeDefinitionsInterface`) instead of hard-coding the post type key.
- Try to locate the post type class in the plugin’s `includes/PostTypes` folder and reuse it here when possible.

**Success criteria:** Post-type registry implements `getKey`, `providePostTypes`, `specifyMetaDefinitions`, `specifyMetasForm`.

### Step 5: Taxonomy custom fields registry

**Do:**

When the target is a taxonomy, copy [assets/TaxonomyCustomFieldsRegistry.php.tpl](assets/TaxonomyCustomFieldsRegistry.php.tpl) into the new class file.

Again, prefer using the taxonomy class’s `provideKey()` when available.

**Success criteria:** Taxonomy registry implements `getKey`, `provideTaxonomies`, `specifyMetaDefinitions`.

### Step 6: Populate meta definitions and fields

**Do:**

Once the panel class exists (post type or taxonomy), populate its `specifyMetaDefinitions()` method with actual fields.

1. Repeatedly ask the user if they want to add a new field until they say they are done.
2. For each field:
   - Ask for a field key.
   - Ask for a field label.
3. For each field, in the custom fields class:
   - Define a `const` for the field key:

     ```php
     const LIST_IMG = 'chef_photo';
     ```

   - Add a protected method returning a `FieldInterface`:
     - For now, assume an `InputField` type.

     ```php
     use WonderWp\Component\Form\Field\FieldInterface;
     use WonderWp\Component\Form\Field\InputField;

     protected function getMediaField(): FieldInterface
     {
         return new InputField(
             '{{the-field-key}}',
             null,
             ['label' => __('{{the-field-label}}', '{{the-field-textdomain}}')]
         );
     }
     ```

   - Reference the field in the meta definitions array:

     ```php
     public function specifyMetaDefinitions(): static
     {
         return $this->setMetaDefinitions(apply_filters('{{the-plugin-basename}}/customfields/{{the-object-key}}metas-definitions', [
             self::LIST_IMG => [$this, 'getMediaField']
         ]));
     }
     ```

4. Continue adding fields until the user is satisfied.

**Success criteria:** Meta definitions map keys to callables; fields match user requests.

### Step 7: Document and ship

**Documentation:**

- Look for a `README.md` in the plugin.
- If none exists, create one.
- Depending on whether the custom fields are for a CPT or taxonomy:
  - Locate or create the object’s documentation section.
  - Document the new fields and their purpose.

**Proposing a commit:**

- Once the user declares they are done adding fields:
  - Propose a commit that includes the new custom fields class and any README updates.
  - Provide a clear commit message and the matching `git commit` command.

**Success criteria:** README describes fields; commit proposed.

## Advanced Features

### Example – Custom fields for a CPT

User intent:

> \"Add custom fields to my Recipe custom post type.\"

Flow:

1. Target plugin and CPT key: e.g. `recipe`.
2. Create `includes/CustomFields/RecipeCustomFields.php` from the post-type template.
3. Implement `getKey()`, `providePostTypes()`, `specifyMetaDefinitions()`, and `specifyMetasForm()`.
4. Ask for fields (e.g., image, difficulty, preparation time), define `const` keys, and add factory methods and meta definitions.
5. Update the plugin README to describe the new Recipe custom fields.

### Follow-ups

Once the base custom fields panel is in place, natural follow-ups include:

- Adding more sophisticated field types or validation logic.
- Creating liaison fields that link two post types (see the liaison custom field skill).
- Connecting custom fields to frontend templates or blocks.

## Resources

| Type     | Path | Description |
| -------- | ---- | ----------- |
| Template | [assets/PostTypeCustomFieldsRegistry.php.tpl](assets/PostTypeCustomFieldsRegistry.php.tpl) | CPT custom fields registry |
| Template | [assets/TaxonomyCustomFieldsRegistry.php.tpl](assets/TaxonomyCustomFieldsRegistry.php.tpl) | Taxonomy custom fields registry |
