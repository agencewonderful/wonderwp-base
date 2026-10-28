// Merge into {Plugin}Activator::activate()
// Activator must extend AbstractDoctrinePluginActivator.
// For greenfield, prefer DoctrinePluginActivator.php.template.
// Placeholders: see doctrine-archetype.md ({Namespace}, {Entity}).

use {Namespace}\Entity\{Entity};

    public function activate()
    {
        $this->copyLanguageFiles(dirname(dirname(__DIR__)) . '/languages');
        $this->createTables([{Entity}::class]);
    }
