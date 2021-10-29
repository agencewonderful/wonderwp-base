<?php

namespace WonderWp\Theme\Child\Components\Citation;

use WonderWp\Theme\Core\Component\AbstractComponent;
use WonderWp\Plugin\GutenbergUtils\Bloc\Annotation\Block;
use WonderWp\Plugin\GutenbergUtils\Bloc\Annotation\BlockAttributes;

/**
 * @Block(title="Citation")
 *
 */
class CitationComponent extends AbstractComponent
{
    /**
     * @var string
     * @BlockAttributes(component="PlainText",type="string",componentAttributes={"placeholder":"Citation"})
     */
    protected $blockquote;

    /**
     * @var string
     * @BlockAttributes(component="PlainText",type="string",componentAttributes={"placeholder":"Nom de l'auteur"})
     */
    protected $author;

    /**
     * @var string
     * @BlockAttributes(component="PlainText",type="string",componentAttributes={"placeholder":"Fonction de l'auteur"})
     */
    protected $function;

    /**
     * @param string $blockquote
     * @return CitationComponent
     */
    public function setBlockquote(string $blockquote): CitationComponent
    {
        $this->blockquote = $blockquote;
        return $this;
    }

    /**
     * @param string $author
     * @return CitationComponent
     */
    public function setAuthor(string $author): CitationComponent
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @param string $function
     * @return CitationComponent
     */
    public function setFunction(string $function): CitationComponent
    {
        $this->function = $function;
        return $this;
    }

    public function getMarkup(array $opts = [])
    {
        $markup = '<div class="blockquote-wrapper">';

        if(!empty($this->blockquote)) {
            $markup .= '<blockquote class="citation">
                <svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 86 67" style="enable-background:new 0 0 86 67;" xml:space="preserve">
<style type="text/css">
	.st0{fill:#FF503A;}
</style>
<desc>Created with Sketch.</desc>
<g id="Symbols">
	<g id="Blocs-paragraphe_x2F_bloc-05-bloc-accroche" transform="translate(-191.000000, -29.000000)">
		<g id="Group" transform="translate(191.000000, 31.000000)">
			<path id="_x201C_-copy" class="st0" d="M65.9,65c4.3,0,7.9-1.2,10.7-3.6c2.9-2.4,4.3-5.9,4.3-10.4c0-2.4-0.4-4.4-1.2-6
				c-0.8-1.6-1.9-2.9-3.1-4s-2.6-2-4.1-2.8s-2.8-1.6-4.1-2.4s-2.3-1.7-3.1-2.8c-0.8-1-1.2-2.3-1.2-3.9c0-4.6,1.8-8.7,5.3-12.3
				s9.1-6.6,16.6-9l0,0V-2c-6.9,2.1-12.7,4.7-17.5,7.9s-8.8,6.8-11.8,10.8c-3.1,4-5.3,8.4-6.6,13.1s-2,9.6-2,14.6
				c0,3.3,0.5,6.3,1.5,8.9s2.3,4.7,3.9,6.5c1.6,1.7,3.5,3,5.7,3.9S63.5,65,65.9,65z M17.8,65c4.3,0,7.9-1.2,10.8-3.6S33,55.5,33,51
				c0-2.4-0.4-4.4-1.2-6s-1.9-2.9-3.2-4c-1.3-1.1-2.6-2-4.1-2.8c-1.4-0.8-2.8-1.6-4.1-2.4s-2.3-1.7-3.2-2.8c-0.8-1-1.2-2.3-1.2-3.9
				c0-4.6,1.8-8.7,5.3-12.3s9.1-6.6,16.7-9l0,0V-2c-6.9,2.1-12.7,4.7-17.5,7.9s-8.8,6.8-11.8,10.8S3.4,25.1,2,29.8s-2,9.6-2,14.6
				c0,3.3,0.5,6.3,1.4,8.9s2.2,4.7,3.9,6.5c1.7,1.7,3.6,3,5.7,3.9S15.4,65,17.8,65z"/>
		</g>
	</g>
</g>
</svg>
                '.$this->blockquote.
                '</blockquote>';
        }

        if(!empty($this->author)) {
            $markup .= '<span class="cite-name">'.$this->author.'</span>';
        }

        if(!empty($this->function)) {
            $markup .= '<span class="cite-function">'.$this->function.'</span>';
        }
        $markup .= '</div>';

        return $markup;
    }
}
