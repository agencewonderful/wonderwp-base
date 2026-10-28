import { createHigherOrderComponent } from '@wordpress/compose';

export const with<ExtensionCamelCase>Control = createHigherOrderComponent((BlockEdit) => {
  return (props) => {
    return <BlockEdit {...props} />;
  };
}, 'with<ExtensionCamelCase>Control');
