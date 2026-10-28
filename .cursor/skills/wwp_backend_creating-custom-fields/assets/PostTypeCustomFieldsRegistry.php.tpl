use WonderWp\Component\Panel\PostFieldPanel\PostFieldPanelInterface;
use WonderWp\Plugin\Core\Framework\AbstractPlugin\CustomFields\AbstractPostTypeCustomFieldsRegistry;

class MyCustomPostTypeCustomFields extends AbstractPostTypeCustomFieldsRegistry
{
    public function getKey(): string
    {
        return '{{the_computed_snake_case_key}}';
    }

    public function providePostTypes(): array
    {
        return [TheGivenCustomPostType::provideKey()];
    }

    public function specifyMetaDefinitions(): static
    {
        return $this->setMetaDefinitions(apply_filters('{{the-plugin-basename}}/customfields/{{the-object-key}}metas-definitions', [

        ]));
    }

    public function specifyMetasForm(): ?PostFieldPanelInterface
    {
        return $this->metabox = $this->createMetasForm(
            $this->getKey(),
            __('{{the-panel-title}}', '{{the-plugin-textdomain}}'),
            static::providePostTypes()
        );
    }
}
