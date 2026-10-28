import { addFilter } from '@wordpress/hooks';
import { with<ExtensionCamelCase>Control } from './<slug>-control';
import { register<ExtensionCamelCase>Attribute } from './<slug>-attribute';
import { add<ExtensionCamelCase>Support } from './<slug>-support';

/**
 * 1) Declare the attribute
 */
addFilter(
  'blocks.registerBlockType',
  '<myplugin>/add-<slug>-attribute',
  register<ExtensionCamelCase>Attribute
);

/**
 * 2) Declare the control
 */
addFilter(
  'editor.BlockEdit',
  '<myplugin>/add-<slug>-control',
  with<ExtensionCamelCase>Control
);

/**
 * 3) Declare the support (if any)
 */
addFilter(
  'blocks.registerBlockType',
  '<myplugin>/add-<slug>-support',
  add<ExtensionCamelCase>Support
);
