public function register()
{
    // ...
    $this->addFilter('my-plugin/my-source-namespace/my-source-key/get_value', [$this, 'getLiaisonValue'], 10, 5);
}

public function getLiaisonValue($value, array $sourceArgs, $blockInstance, string $attributeName, \WP_Post $post): ?string
{
    // Read liaison meta using the repository.
    // Load the linked post.
    // Return the desired value (e.g. the linked post title).
    return $value;
}
