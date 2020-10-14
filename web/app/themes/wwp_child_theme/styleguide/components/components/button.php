<!-- components/components/button.php -->
<p class="subTitle">Bouton Gutenberg</p>

<div class="encadre">
    <div class="grid-2 has-gutter-xl">
        <div>
            <p>ATTRIBUTS :</p>
            <ul>
                <li>Label</li>
                <li>Lien</li>
                <li>Couleur (variable)</li>
            </ul>
        </div>
        <div>
            <p>OPTIONS :</p>
            <ul>
                <li>Flèche droite</li>
            </ul>
        </div>
    </div>
</div>

<p class="subTitle">Bouton de base</p>
<?php
$button = new \WonderWp\Theme\Child\Components\Button\ButtonComponent();
$button
    ->setLink('/')
    ->setLabel('Valider')
;
echo $button->getMarkup();
?>
<br><hr>

<p class="subTitle">Bouton couleur "brand"</p>
<?php
$button = new \WonderWp\Theme\Child\Components\Button\ButtonComponent();
$button
    ->setLink('/')
    ->setLabel('Valider')
    ->setColor('brand')
;
echo $button->getMarkup();
?>
<br><hr>

<p class="subTitle">Bouton avec flèche</p>
<?php
$button = new \WonderWp\Theme\Child\Components\Button\ButtonComponent();
$button
    ->setLink('/')
    ->setLabel('En savoir plus')
    ->setArrow('true')
;
echo $button->getMarkup();
?>
<br><hr>

<p class="subTitle">Bouton couleur avec flèche</p>
<?php
$button = new \WonderWp\Theme\Child\Components\Button\ButtonComponent();
$button
    ->setLink('/')
    ->setLabel('Valider')
    ->setColor('brand')
    ->setArrow('true')
;
echo $button->getMarkup();
?>
