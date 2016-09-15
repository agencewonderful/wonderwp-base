<!--components/components/accordion.php-->
<!-- This markup is generated by the AccordionComponent class, please do not write by hand -->
<?php
//This is how to use the accordion

//Init component
$accordion = new \WonderWp\Theme\Components\AccordionComponent();

//Add elements to the accordion
$accordion->addBlock('First tab','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet aperiam at corporis deleniti, eius enim eveniet excepturi fugit incidunt iure labore molestiae nemo non perspiciatis provident quae quasi veniam voluptatibus.');
$accordion->addBlock('Second tab','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet aperiam at corporis deleniti, eius enim eveniet excepturi fugit incidunt iure labore molestiae nemo non perspiciatis provident quae quasi veniam voluptatibus.');
$accordion->addBlock('Third tab','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet aperiam at corporis deleniti, eius enim eveniet excepturi fugit incidunt iure labore molestiae nemo non perspiciatis provident quae quasi veniam voluptatibus.');
$accordion->addBlock('Fourth tab','Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet aperiam at corporis deleniti, eius enim eveniet excepturi fugit incidunt iure labore molestiae nemo non perspiciatis provident quae quasi veniam voluptatibus.');

//Get markup
echo $accordion->getMarkup();
?>