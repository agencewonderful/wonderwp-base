export function add<ExtensionCamelCase>Support(settings, name) {
  const blocksWith<ExtensionCamelCase>Support = ['core/cover', 'core/media-text'];
  if (blocksWith<ExtensionCamelCase>Support.includes(name)) {
    return {
      ...settings,
      supports: {
        ...settings.supports,
        <slug>: true,
      },
    };
  }
  return settings;
}
