$childRoot = $this->getConfig('path.child.root', '');
if (!empty($childRoot)) {
    $this->setConfig('discoveryPathsRoots', [
        'my-plugin-child' => $this->getConfig('path.child.root') . 'includes' . DIRECTORY_SEPARATOR,
        'my-plugin' => $this->getConfig('path.root') . 'includes' . DIRECTORY_SEPARATOR,
    ]);
}
