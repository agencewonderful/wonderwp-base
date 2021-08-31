<!-- components/components/chiffre_cle.php -->

<div class="encadre">
    <div class="grid-2 has-gutter-xl">
        <div>
            <p>ATTRIBUTS :</p>
            <ul>
                <li>Chiffre</li>
                <li>Texte</li>
                <li>Image</li>
            </ul>
        </div>
        <div>
            <p>OPTIONS :</p>
        </div>
    </div>
</div>

<?php

$chiffrecle = new \WonderWp\Theme\Child\Components\ChiffreCle\ChiffreCleComponent();

$chiffrecle
    ->setChiffre('1993')
    ->setText('Notre année de naissance')
    ->setImage('<img width="90" height="90" src="/app/uploads/2021/08/icon-influenceurs.svg" alt="">')
;

echo $chiffrecle->getMarkup();
