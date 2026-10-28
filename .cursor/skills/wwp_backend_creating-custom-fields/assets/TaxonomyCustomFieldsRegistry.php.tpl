use WonderWp\Plugin\Core\Framework\AbstractPlugin\CustomFields\AbstractTaxonomyCustomFieldsRegistry;

class MyTaxonomyCustomFields extends AbstractTaxonomyCustomFieldsRegistry
{
    public function getKey(): string
    {
        return '{{the_computed_snake_case_key}}';
    }

    public function provideTaxonomies(): array
    {
        return [TheGivenTaonomy::provideKey()];
    }

    public function specifyMetaDefinitions(): static
    {
        return $this->setMetaDefinitions(apply_filters('{{the-plugin-basename}}/customfields/{{the-object-key}}metas-definitions', [

        ]));
    }
}
