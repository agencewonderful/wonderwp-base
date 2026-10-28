export const register<ExtensionCamelCase>Attribute = (settings, name) => {
  // Only affect one block
  if (name !== '<block-name>') return settings;

  return {
    ...settings,
    attributes: {
      ...settings.attributes,
      <slug>: {
        type: 'string',
        default: '',
      },
    },
  };
};
