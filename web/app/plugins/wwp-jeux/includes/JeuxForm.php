<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 09/08/2016
 * Time: 17:16
 */

namespace WonderWp\Plugin\Jeux;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\SimpleXMLElement;
use WonderWp\DI\Container;
use WonderWp\Entity\EntityAttribute;
use WonderWp\Entity\EntityRelation;
use WonderWp\Forms\Fields\AbstractField;
use WonderWp\Forms\Fields\BooleanField;
use WonderWp\Forms\Fields\BtnField;
use WonderWp\Forms\Fields\FieldGroup;
use WonderWp\Forms\Fields\FileField;
use WonderWp\Forms\Fields\HiddenField;
use WonderWp\Forms\Fields\InputField;
use WonderWp\Forms\Fields\PageField;
use WonderWp\Forms\Fields\RadioField;
use WonderWp\Forms\Fields\SelectField;
use WonderWp\Forms\Fields\TextAreaField;
use WonderWp\Forms\FormGroup;
use WonderWp\Forms\FormInterface;
use WonderWp\Forms\FormValidatorInterface;
use WonderWp\Forms\ModelForm;
use WonderWp\Forms\Validation\Validator;
use WonderWp\HttpFoundation\Request;
use WonderWp\Plugin\Forms\Fields\LocaleField;
use WonderWp\Plugin\Forms\Fields\MediaField;
use WonderWp\Plugin\Jeux\Mecaniques\MecaniqueInterface;

/**
 * Class JeuxForm
 * @package WonderWp\Plugin\Jeux
 * Class that defines the form to use when adding / editing the entity
 */
class JeuxForm extends ModelForm
{

    public function setFormInstance(FormInterface $formInstance)
    {
        $formInstance->setName('jeux-form');
        return parent::setFormInstance($formInstance); // TODO: Change the autogenerated stub
    }

    private function _getMecaniques()
    {
        $mecaniques = [];
        foreach (glob(__DIR__ . '/mecaniques/*.php') as $file) {
            if (strpos($file, 'Abstract') === false && strpos($file, 'Interface') === false) {
                $class = __NAMESPACE__ . '\\Mecaniques\\' . basename($file, '.php');
                if (class_exists($class)) {
                    $mecaniques[$class] = $class::getEtiquette();
                }
            }
        }
        return $mecaniques;
    }

    public function buildForm()
    {
        $request = Request::getInstance();
        $modelId = $this->_modelInstance->getId();
        $typeJeux = $request->get('typejeux');
        if (empty($modelId) && empty($typeJeux)) {
            $this->preBuild();
            $f = new RadioField('typeJeux', null, ['label' => 'Type de Jeux']);
            $mecaniques = $this->_getMecaniques();
            $f
                ->setOptions($mecaniques)
                ->generateRadios();
            $this->addField($f);
            return $this;
        } else {
            if (!empty($typeJeux)) {
                $this->_modelInstance->setMecaniqueGain($typeJeux);
            }
            return parent::buildForm();
        }
    }

    public function preBuild()
    {
        //Fieldset Infos Jeux
        $this->_formInstance->addGroup(new FormGroup('jeux', __('infosjeux.trad', WWP_JEUX_TEXTDOMAIN)));
    }

    public function newField(EntityAttribute $attr)
    {
        $fieldName = $attr->getFieldName();
        $entity = $this->getModelInstance();
        $val = $entity->$fieldName;
        $label = __($fieldName . '.trad', $this->_textDomain);

        //Add here particular cases for your different fields
        switch ($fieldName) {
            case'visuel':
                $f = new MediaField($fieldName, $val, ['label' => $label]);
                break;
            case'locale':
                $f = LocaleField::getInstance($fieldName, $val, ['label' => $label]);
                break;
            case 'pageDotation':
            case 'pageReglement':
            case 'pageGagnants':
                $f = new PageField($fieldName, $val, ['label' => $label]);
                break;
            case'mecaniqueGain':
                $f = new HiddenField($fieldName, $val);
                break;
            default:
                $f = parent::newField($attr);
                break;
        }
        return $f;
    }

    public function addField(AbstractField $f, $groupName = '')
    {
        if (empty($groupName)) {
            $groupName = 'jeux';
        }
        parent::addField($f, $groupName);
    }

    public function newRelation(EntityRelation $relationAttr)
    {
        $fieldName = $relationAttr->getFieldName();
        if (method_exists($this, '_generate' . ucfirst($fieldName) . 'Group')) {
            call_user_func(array($this, '_generate' . ucfirst($fieldName) . 'Group'));
        }
    }

    private function _generateQuestionsGroup()
    {
        //Create field group
        $this->_formInstance->removeGroup('questions');
        $this->_formInstance->addGroup(new FormGroup('questions', __('questions.trad', WWP_JEUX_TEXTDOMAIN)));

        //Get jeux questions
        $thisQuestions = $this->_modelInstance->getQuestions();

        //One field group per question
        if (!empty($thisQuestions)) {
            /** @var JeuxQuestion $question */
            foreach ($thisQuestions as $question) {
                $f = $this->_generateQuestionGroup($question);
                $this->_formInstance->addField($f, 'questions');
            }
        }

        //Add question btn
        $addBtn = new BtnField('add-question', null, ['label' => 'Ajouter une question','inputAttributes'=>['class'=>['add-repeatable'],'data-repeatable' => '_newquestion_']]);
        $this->_formInstance->addField($addBtn, 'questions');

        //One extra field for new steps
        $f = $this->_generateQuestionGroup(new JeuxQuestion());
        $this->_formInstance->addField($f, 'questions');
    }

    private function _generateQuestionGroup(JeuxQuestion $question)
    {
        $i = $question->getId();
        if (empty($i)) {
            $i = '_newquestion_';
        }

        /** @var JeuxQuestion $question */
        $displayRules = array();
        if ($i == '_newquestion_') {
            $displayRules['inputAttributes']['class'] = ['_newquestion_','nouveau-repeatable', 'hidden'];
            $validationRules = [];
        } else {
            $displayRules['inputAttributes']['class'] = ['question','repeatable'];
            $displayRules['after'] = '<button class="button remove-question remove-repeatable">Supprimer</button>';
            $validationRules = [Validator::notEmpty()];
        }
        $displayRules['wrapAttributes'] = ['no-wrap' => true];
        $f = new FieldGroup('questions' . $i, null, $displayRules);

        //Id
        $displayRules = [
            'inputAttributes' => [
                'name' => 'questions[' . $i . '][id]',
                'class' => ['question-id','repeatable-id']
            ]
        ];
        $eId = new HiddenField('question_' . $i . '_id', $question->getId(), $displayRules);
        $f->addFieldToGroup($eId);

        //Title
        $displayRules = [
            'inputAttributes' => [
                'name' => 'questions[' . $i . '][titre]',
                'placeholder' => __('question.titre.trad', WWP_JEUX_TEXTDOMAIN),
                'class' => ['question-titre']
            ]
        ];
        $eTitle = new InputField('question_' . $i . '_titre', $question->getTitre(), $displayRules, $validationRules);
        $f->addFieldToGroup($eTitle);

        //Media
        $displayRules = [
            'label' => __('question.visuel.trad', WWP_JEUX_TEXTDOMAIN),
            'inputAttributes' => ['name' => 'questions[' . $i . '][visuel]'],
            'wrapAttributes' => ['class' => ['visuel-wrap']]
        ];
        $eMedia = new MediaField('question_' . $i . '_visuel', $question->getVisuel(), $displayRules);
        $f->addFieldToGroup($eMedia);

        //Description
        $displayRules = [
            'label' => __('question.contenu.trad', WWP_JEUX_TEXTDOMAIN),
            'inputAttributes' => ['name' => 'questions[' . $i . '][contenu]']
        ];
        $eInstructions = new TextAreaField('question_' . $i . '_contenu', $question->getContenu(), $displayRules);
        $f->addFieldToGroup($eInstructions);

        //Is Active
        $displayRules = [
            'label' => __('question.isActive.trad', WWP_JEUX_TEXTDOMAIN),
            'inputAttributes' => ['name' => 'questions[' . $i . '][isActive]']
        ];
        $eActive = new BooleanField('question_' . $i . '_isActive', $question->getIsActive(), $displayRules);
        $f->addFieldToGroup($eActive);

        return $f;
    }

    private function _generateLotsGroup()
    {
        //Create field group
        $this->_formInstance->removeGroup('lots');
        $this->_formInstance->addGroup(new FormGroup('lots', __('lots.trad', WWP_JEUX_TEXTDOMAIN), ['class' => ['closed']]));

        //Get jeux lots
        $thisLots = $this->_modelInstance->getLots();

        //One field group per lot
        if (!empty($thisLots)) {
            /** @var JeuxLot $lot */
            foreach ($thisLots as $lot) {
                $f = $this->_generateLotGroup($lot);
                $this->_formInstance->addField($f, 'lots');
            }
        }

        //Add lot btn
        $addBtn = new BtnField('add-lot', null, ['label' => 'Ajouter un lot','inputAttributes'=>['class'=>['add-repeatable'],'data-repeatable' => '_newlot_']]);
        $this->_formInstance->addField($addBtn, 'lots');

        //One extra field for new steps
        $f = $this->_generateLotGroup(new JeuxLot());
        $this->_formInstance->addField($f, 'lots');
    }

    private function _generateLotGroup(JeuxLot $lot)
    {
        $i = $lot->getId();
        if (empty($i)) {
            $i = '_newlot_';
        }

        /** @var JeuxLot $lot */
        $displayRules = array();
        if ($i == '_newlot_') {
            $displayRules['inputAttributes']['class'] = ['_newlot_','nouveau-repeatable', 'hidden'];
            $validationRules = [];
        } else {
            $displayRules['inputAttributes']['class'] = ['lot','repeatable'];
            $displayRules['after'] = '<button class="button remove-lot remove-repeatable">Supprimer</button>';
            $validationRules = [Validator::notEmpty()];
        }
        $displayRules['wrapAttributes'] = ['no-wrap' => true];
        $f = new FieldGroup('lots' . $i, null, $displayRules);

        //Id
        $displayRules = [
            'inputAttributes' => [
                'name' => 'lots[' . $i . '][id]',
                'class' => ['lot-id','repeatable-id']
            ]
        ];
        $eId = new HiddenField('lot_' . $i . '_id', $lot->getId(), $displayRules);
        $f->addFieldToGroup($eId);

        //Title
        $displayRules = [
            'inputAttributes' => [
                'name' => 'lots[' . $i . '][titre]',
                'placeholder' => __('lot.titre.trad', WWP_JEUX_TEXTDOMAIN),
                'class' => ['lot-titre']
            ]
        ];
        $eTitle = new InputField('lot_' . $i . '_titre', $lot->getTitre(), $displayRules, $validationRules);
        $f->addFieldToGroup($eTitle);

        //Media
        $displayRules = [
            'label' => __('lot.visuel.trad', WWP_JEUX_TEXTDOMAIN),
            'inputAttributes' => ['name' => 'lots[' . $i . '][visuel]'],
            'wrapAttributes' => ['class' => ['visuel-wrap']]
        ];
        $eMedia = new MediaField('lot_' . $i . '_visuel', $lot->getVisuel(), $displayRules);
        $f->addFieldToGroup($eMedia);

        //Description
        $displayRules = [
            'label' => __('lot.contenu.trad', WWP_JEUX_TEXTDOMAIN),
            'inputAttributes' => ['name' => 'lots[' . $i . '][contenu]']
        ];
        $eInstructions = new TextAreaField('lot_' . $i . '_contenu', $lot->getContenu(), $displayRules);
        $f->addFieldToGroup($eInstructions);

        //Mecanique de gain
        $mecanique = $this->_modelInstance->getMecaniqueGain();
        $mecaGroup = $mecanique::generateLotMecanique($lot, 'lots[' . $i . '][mecaniqueGain]');
        $f->addFieldToGroup($mecaGroup);

        //Is Active
        $displayRules = [
            'label' => __('lot.isActive.trad', WWP_JEUX_TEXTDOMAIN),
            'inputAttributes' => ['name' => 'lots[' . $i . '][isActive]']
        ];
        $eActive = new BooleanField('lot_' . $i . '_isActive', $lot->getIsActive(), $displayRules);
        $f->addFieldToGroup($eActive);

        return $f;
    }

    public function handleRequest(array $data, FormValidatorInterface $formValidator)
    {

        //\WonderWp\trace($data);

        $request = Request::getInstance();

        if (!empty($data['typeJeux'])) {
            \WonderWp\redirect($request->server->get('REQUEST_URI') . '&typejeux=' . urlencode($data['typeJeux']));
            return [];
        }

        /*$data = array(
            'id' => null,
            'visuel' => null,
            'titre' => 'Quiz PinKids Âge de glace',
            'contenu' => 'test',
            'locale' => 'fr_FR',
            'startsAt' => '2016-11-08',
            'endsAt' => '2016-11-25',
            'pageDotation' => '6',
            'pageReglement' => '10',
            'pageGagnants' => '4',
            'mecaniqueGain' => 'WonderWp\Plugin\Jeux\Mecaniques\TirageAuSort',
            'lots' => array
            (
                '_newlot_1' => array
                (
                    'id' => '_newlot_1',
                    'titre' => 'test lot 1',
                    'visuel' => 'http://local.wonderwp.com/app/uploads/2016/06/thumb_IMG_3839_1024.jpg',
                    'content' => 'test desc 1',
                    'stock' => '5'
                ),

                '_newlot_2' => array
                (
                    'id' => '_newlot_2',
                    'titre' => 'Test lot 2',
                    'visuel' => null,
                    'content' => 'test desc 2',
                    'stock' => '2'
                ),

                '_newlot_' => array
                (
                    'id' => null,
                    'titre' => null,
                    'visuel' => null,
                    'content' => null,
                    'stock' => null
                )

            )
        );*/

        if (!empty($data['startsAt'])) {
            $data['startsAt'] = \DateTime::createFromFormat('Y-m-d', $data['startsAt']);
        } else {
            $data['startsAt'] = new \DateTime();
        }
        if (!empty($data['endsAt'])) {
            $data['endsAt'] = \DateTime::createFromFormat('Y-m-d', $data['endsAt']);
        } else {
            $data['endsAt'] = new \DateTime();
        }

        $postedData = $data;

        //\WonderWp\trace($postedData);

        //Extract Lots
        $rawLotsData = array();
        if (isset($data['lots'])) {
            $rawLotsData = $data['lots'];
            unset($data['lots']);
        }

        //Extract Questions
        $rawQuestionsData = array();
        if (isset($data['questions'])) {
            $rawQuestionsData = $data['questions'];
            unset($data['questions']);
        }

        $errors = parent::handleRequest($data, $formValidator);
        $this->_formInstance->fill($postedData);

        if (!empty($errors)) {
            return $errors;
        }

        $container = Container::getInstance();
        /** @var EntityManager $em */
        $this->_em = $container->offsetGet('entityManager');
        $em = $this->_em;

        /** @var JeuxEntity $jeux */
        $jeux = $this->_modelInstance;

        //Process Lots
        $lotErrors = $this->_handleLots($rawLotsData);
        $errors = $errors + $lotErrors;

        //Process Questions
        $questionsErrors = $this->_handleQuestions($rawQuestionsData);
        $errors = $errors + $questionsErrors;

        /*//Process Lots
        $lotsErrors = $this->_handleLots($rawLotsData);
        $errors = $errors + $lotsErrors;*/

        $em->flush();

        return $errors;
    }

    private function _handleLots($rawLotsData)
    {

        if (isset($rawLotsData['_newlot_'])) {
            unset($rawLotsData['_newlot_']);
        }

        $errors = array();

        /** @var JeuxEntity $jeux */
        $jeux = $this->_modelInstance;

        /** @var EntityManager $em */
        $em = $this->_em;

        //Remove old lots
        //aka those which are in my collection but not the posted ids
        $lotsToRemove = $jeux->getLots()->filter(
            function ($entry) use ($rawLotsData) {
                return !in_array($entry->getId(), array_keys($rawLotsData));
            }
        );

        if (!empty($lotsToRemove)) {
            foreach ($lotsToRemove as $e) {
                $jeux->removeRelatedEntity('lots',$e,'setJeux');
                $em->remove($e);
            }
            $em->flush();
        }

        //Recreate lots
        if (!empty($rawLotsData)) {
            foreach ($rawLotsData as $val) {
                $id = $val['id'];
                if(is_array($val['mecaniqueGain'])){
                    $val['mecaniqueGain'] = json_encode($val['mecaniqueGain']);
                }

                if (strpos($val['id'],'_newrepeatable_')===false) {
                    $lot = $em->find(JeuxLot::class, $id);
                } else {
                    if (empty($val['titre'])) {
                        continue;
                    } //pas de donnees postees pour ajouter une nouvelle lot
                    $lot = new JeuxLot();
                    $em->persist($lot);
                    $jeux->addRelatedEntity('lots',$lot,'setJeux');
                    $val['id'] = $jeux->getId();
                }
                $lot->populate($val);

                $em->flush();

            }
        }
        $em->flush();

        return $errors;
    }

    private function _handleQuestions($rawQuestionsData)
    {

        if (isset($rawQuestionsData['_newquestion_'])) {
            unset($rawQuestionsData['_newquestion_']);
        }

        $errors = array();

        /** @var JeuxEntity $jeux */
        $jeux = $this->_modelInstance;

        /** @var EntityManager $em */
        $em = $this->_em;

        //Remove old questions
        //aka those which are in my collection but not the posted ids
        $questionsToRemove = $jeux->getQuestions()->filter(
            function ($entry) use ($rawQuestionsData) {
                return !in_array($entry->getId(), array_keys($rawQuestionsData));
            }
        );

        if (!empty($questionsToRemove)) {
            foreach ($questionsToRemove as $e) {
                $jeux->removeRelatedEntity('questions',$e,'setJeux');
                $em->remove($e);
            }
            $em->flush();
        }

        //Recreate questions
        if (!empty($rawQuestionsData)) {
            foreach ($rawQuestionsData as $val) {
                $id = $val['id'];

                if (strpos($val['id'],'_newrepeatable_')===false) {
                    $question = $em->find(JeuxQuestion::class, $id);
                } else {
                    if (empty($val['titre'])) {
                        continue;
                    } //pas de donnees postees pour ajouter une nouvelle question
                    $question = new JeuxQuestion();
                    $em->persist($question);
                    $jeux->addRelatedEntity('questions',$question,'setJeux');
                    $val['id'] = $jeux->getId();
                }
                $question->populate($val);

                $em->flush();

            }
        }
        $em->flush();

        return $errors;
    }

}