---
name: creating-a-new-custom-posttype
description: >-
  Creates a new WonderWP custom post type class, including key, labels, args,
  and internationalization, and wires it into the appropriate plugin folder.
  Use when the user wants to add a custom post type to a WonderWP plugin or
  mentions creating a new CPT.
metadata:
  domain: wonderwp-backend
---

# Creating a Custom Post Type

## Goal

Create a WonderWP custom post type class (`AbstractCustomPostType`) in a plugin’s `includes/PostTypes` folder with key, labels, args, and i18n wired for automatic registration.

## Rules

- When the user asks to create a custom post type, gather the following information.
- **Mandatory:** the custom post type name; the custom post type key; singular and plural labels; the plugin in which the post type should be stored.
- If only the name is provided, you may extrapolate the key and labels from the name and ask the user to confirm.
- **Optional:** description; visibility (public/private, single page, archive); capabilities/privileges; API-specific configuration (e.g. REST settings).
- Keep a running list of information already collected versus what is still missing and reflect it back to the user.
- The custom post type will be automatically registered when the plugin runs as long as the class is placed in the `includes/PostTypes` folder.
- After generating the class: locate the plugin textdomain from the root plugin file; check the `languages` folder for translation files using that textdomain; add or update translation entries for all labels introduced in the PostType class.

## Workflow

### Step 1: Generate the PostType class

**Do:**

1. Determine the target plugin folder and ensure it exists.
2. Inside that plugin, check for an `includes/PostTypes` folder:
   - If it doesn’t exist, create it.
3. Create a PostType class in `includes/PostTypes`:
   - Class name: camel-case version of the post type name with a `PostType` suffix.
   - Extend `AbstractCustomPostType`.
   - Implement `HasCustomTypeDefinitionsInterface` and `HasLabelsInterface`.
4. Locate the appropriate PHP namespace from the plugin folder and apply it to the new class.
5. Copy [assets/PostType.php.tpl](assets/PostType.php.tpl) and adapt the key, labels, supports, icon, and rewrite settings based on the user’s input.

**Success criteria:** PostType class file exists under `includes/PostTypes` with adapted key, labels, args, and namespace.

### Step 2: Update translations

**Do:**

After generating the class:

- Locate the plugin textdomain from the root plugin file.
- Check the `languages` folder for translation files using that textdomain.
- Add or update translation entries for all labels introduced in the PostType class.

**Success criteria:** New label strings are reflected in the project’s translation files for that textdomain.

### Step 3: Document and ship

**Documentation:**

- Look for a `README.md` file in the plugin.
- If found, look for a “Custom Post Type” section:
  - If it exists, add a line describing the new custom post type and where it is registered.
  - If it does not exist, create such a section and document the new CPT.

**Proposing a commit:**

- Once the PostType class and docs are in place, propose a commit to the user.
- Include:
  - A concise commit message referencing the new CPT.
  - The corresponding `git commit` command.

**Success criteria:** README mentions the CPT; commit message and command are proposed.

## Advanced Features

### Example – Basic custom post type

User intent:

> \"I want a custom post type for Events in my plugin.\"

Flow:

1. Collect:
   - Name: `Events`.
   - Key: e.g. `event`.
   - Labels: singular `Event`, plural `Events`.
   - Target plugin.
2. Generate `includes/PostTypes/EventPostType.php` from the template.
3. Set `provideKey()` to `event` and adjust `provideArgs()` options (public, supports, rewrite slug).
4. Update translations with Event labels.
5. Update the plugin README’s “Custom Post Type” section to describe the Events CPT.

### Follow-ups

After creating a custom post type, common follow-ups include:

- Creating a taxonomy for the post type.
- Creating custom fields for the post type.
- Creating blocks for the post type.

Ask the user which direction they want to pursue next and then follow the appropriate WonderWP backend or block editor skills.

## Resources

| Type     | Path | Description |
| -------- | ---- | ----------- |
| Template | [assets/PostType.php.tpl](assets/PostType.php.tpl) | Custom post type class skeleton |
