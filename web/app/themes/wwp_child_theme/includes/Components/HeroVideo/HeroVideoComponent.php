<?php

namespace WonderWp\Theme\Child\Components\HeroVideo;

use WonderWp\Theme\Core\Component\AbstractComponent;
use WonderWp\Plugin\GutenbergUtils\Bloc\Annotation\Block;
use WonderWp\Plugin\GutenbergUtils\Bloc\Annotation\BlockAttributes;

/**
 * @Block(title="Hero Video")
 */
class HeroVideoComponent extends AbstractComponent
{
    /**
     * @var string
     * @BlockAttributes(component="PlainText",type="string",componentAttributes={"placeholder":"Titre home"})
     */
    protected $title;

    /**
     * @var string
     * @BlockAttributes(component="PlainText",type="string",componentAttributes={"placeholder":"Description home"})
     */
    protected $description;

    /**
     * @var string
     * @BlockAttributes(component="PlainText",type="string",componentAttributes={"placeholder":"Lien home"})
     */
    protected $link;

    /**
     * @var string
     * @BlockAttributes(component="PlainText",type="string",componentAttributes={"placeholder":"Label lien"})
     */
    protected $cta;

    /**
     * @var string
     * @BlockAttributes(component="InnerBlocks",type="string",componentAttributes={"placeholder":"Video Native"})
     */
    protected $subComponents;

    /**
     * @param string $title
     * @return HeroVideoComponent
     */
    public function setTitle(string $title): HeroVideoComponent
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $description
     * @return HeroVideoComponent
     */
    public function setDescription(string $description): HeroVideoComponent
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $link
     * @return HeroVideoComponent
     */
    public function setLink(string $link): HeroVideoComponent
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @param string $cta
     * @return HeroVideoComponent
     */
    public function setCta(string $cta): HeroVideoComponent
    {
        $this->cta = $cta;
        return $this;
    }

    /**
     * @param string $subComponents
     * @return HeroVideoComponent
     */
    public function setSubComponents(string $subComponents): HeroVideoComponent
    {
        $this->subComponents = $subComponents;
        return $this;
    }

    public function getMarkup(array $opts = [])
    {
        $markup = '<div class="hero-side">';

        if (!empty($this->link)) {
            $markup .= '<a href="'.$this->link.'">';
        }

        if (!empty($this->subComponents)) {
            $markup .= $this->subComponents;
        }

        if (!empty($this->title) || !empty($this->description)) {
            $markup .= '<div class="hero-content">
                        <p class="title">'.$this->title.'</p>
                        <p class="description">'.$this->description.'</p>';
            if (!empty($this->cta)) {
              $markup .= '<div class="btn"><span>'.$this->cta.'</span></div>';
            }
            $markup .= '</div>';
        }


        if (!empty($this->link)) {
            $markup .= '</a>';
        }

        $markup .= '</div>';

        return $markup;
    }
}
