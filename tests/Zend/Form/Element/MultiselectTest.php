<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * Test class for Zend_Form_Element_Multiselect
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Form
 */
class Zend_Form_Element_MultiselectTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Zend_Form_Element_Multiselect
     */
    public $element;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->element = new Zend_Form_Element_Multiselect('foo');
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    public function tearDown(): void
    {
    }

    public function getView()
    {
        $view = new Zend_View();
        $view->addHelperPath(dirname(__FILE__) . '/../../../../library/Zend/View/Helper/');
        return $view;
    }

    public function testMultiselectElementInstanceOfMultiElement()
    {
        $this->assertTrue($this->element instanceof Zend_Form_Element_Multi);
    }

    public function testMultiselectElementInstanceOfXhtmlElement()
    {
        $this->assertTrue($this->element instanceof Zend_Form_Element_Xhtml);
    }

    public function testMultiselectElementInstanceOfBaseElement()
    {
        $this->assertTrue($this->element instanceof Zend_Form_Element);
    }

    public function testMultiselectElementIsAnArrayByDefault()
    {
        $this->assertTrue($this->element->isArray());
    }

    public function testMultiselectElementUsesSelectHelperInViewHelperDecoratorByDefault()
    {
        $decorator = $this->element->getDecorator('viewHelper');
        $this->assertInstanceOf(Zend_Form_Decorator_ViewHelper::class, $decorator);
        $decorator->setElement($this->element);
        $helper = $decorator->getHelper();
        $this->assertEquals('formSelect', $helper);
    }

    public function testMultipleOptionSetByDefault()
    {
        $this->assertNotNull($this->element->multiple);
        $this->assertEquals('multiple', $this->element->multiple);
    }

    public function testHasDefaultSeparator()
    {
        $this->assertEquals('<br />', $this->element->getSeparator());
    }

    public function testCanSetSeparator()
    {
        $this->testHasDefaultSeparator();
        $this->element->setSeparator("\n");
        $this->assertEquals("\n", $this->element->getSeparator());
    }

    public function testMultiOptionsEmptyByDefault()
    {
        $options = $this->element->getMultiOptions();
        $this->assertIsArray($options);
        $this->assertEmpty($options);
    }

    public function testCanSetMultiOptions()
    {
        $this->testMultiOptionsEmptyByDefault();
        $this->element->addMultiOption('foo', 'foovalue');
        $this->assertEquals('foovalue', $this->element->getMultiOption('foo'));
        $this->element->setMultiOptions(array('bar' => 'barvalue', 'baz' => 'bazvalue'));
        $this->assertEquals(array('bar' => 'barvalue', 'baz' => 'bazvalue'), $this->element->getMultiOptions());
        $this->element->addMultiOptions(array('bat' => 'batvalue', 'foo' => 'foovalue'));
        $this->assertEquals(array('bar' => 'barvalue', 'baz' => 'bazvalue', 'bat' => 'batvalue', 'foo' => 'foovalue'), $this->element->getMultiOptions());
        $this->element->addMultiOption('test', 'testvalue');
        $this->assertEquals(array('bar' => 'barvalue', 'baz' => 'bazvalue', 'bat' => 'batvalue', 'foo' => 'foovalue', 'test' => 'testvalue'), $this->element->getMultiOptions());
    }

    /**
     * @group ZF-2824
     */
    public function testCanSetMultiOptionsUsingAssocArraysWithKeyValueKeys()
    {
        $options = array(
            array(
                'value' => '1',
                'key'   => 'aa',
            ),
            array(
                'key'   => '2',
                'value' => 'xxxx',
            ),
            array(
                'value' => '444',
                'key'   => 'ssss',
            ),
        );
        $this->element->addMultiOptions($options);
        $this->assertEquals($options[0]['value'], $this->element->getMultiOption('aa'));
        $this->assertEquals($options[1]['value'], $this->element->getMultiOption(2));
        $this->assertEquals($options[2]['value'], $this->element->getMultiOption('ssss'));
    }

    /**
     * @group ZF-2824
     */
    public function testCanSetMultiOptionsUsingConfigWithKeyValueKeys()
    {
        $config = new Zend_Config_Xml(dirname(__FILE__) . '/../_files/config/multiOptions.xml', 'testing');
        $this->element->setMultiOptions($config->options->toArray());
        $this->assertEquals($config->options->first->value, $this->element->getMultiOption('aa'));
        $this->assertEquals($config->options->second->value, $this->element->getMultiOption(2));
        $this->assertEquals($config->options->third->value, $this->element->getMultiOption('ssss'));

        $config = new Zend_Config_Ini(dirname(__FILE__) . '/../_files/config/multiOptions.ini', 'testing');
        $this->element->setMultiOptions($config->options->toArray());
        $this->assertEquals($config->options->first->value, $this->element->getMultiOption('aa'));
        $this->assertEquals($config->options->second->value, $this->element->getMultiOption(2));
        $this->assertEquals($config->options->third->value, $this->element->getMultiOption('ssss'));
    }

    public function testCanRemoveMultiOption()
    {
        $this->testMultiOptionsEmptyByDefault();
        $this->element->addMultiOption('foo', 'foovalue');
        $this->assertEquals('foovalue', $this->element->getMultiOption('foo'));
        $this->element->removeMultiOption('foo');
        $this->assertNull($this->element->getMultiOption('foo'));
    }

    public function testOptionsAreRenderedInFinalMarkup()
    {
        $options = array(
            'foovalue' => 'Foo',
            'barvalue' => 'Bar'
        );
        $this->element->addMultiOptions($options);
        $html = $this->element->render($this->getView());
        foreach ($options as $value => $label) {
            $this->assertMatchesRegularExpression('/<option.*value="' . $value . '"[^>]*>' . $label . '/s', $html, $html);
        }
    }

    public function testTranslatedOptionsAreRenderedInFinalMarkupWhenTranslatorPresent()
    {
        $translations = array(
            'ThisShouldNotShow'   => 'Foo Value',
            'ThisShouldNeverShow' => 'Bar Value'
        );
        $translate = new Zend_Translate('array', $translations, 'en');
        $translate->setLocale('en');

        $options = array(
            'foovalue' => 'ThisShouldNotShow',
            'barvalue' => 'ThisShouldNeverShow'
        );

        $this->element->setTranslator($translate)
                      ->addMultiOptions($options);

        $html = $this->element->render($this->getView());
        foreach ($options as $value => $label) {
            $this->assertStringNotContainsString($label, $html, $html);
            $this->assertMatchesRegularExpression('/<option.*value="' . $value . '"[^>]*>' . $translations[$label] . '/s', $html, $html);
        }
    }

    public function testOptionLabelsAreTranslatedWhenTranslateAdapterIsPresent()
    {
        $translations = include dirname(__FILE__) . '/../_files/locale/array.php';
        $translate    = new Zend_Translate('array', $translations, 'en');
        $translate->setLocale('en');

        $options = array(
            'foovalue' => 'Foo',
            'barvalue' => 'Bar'
        );
        $this->element->addMultiOptions($options)
                      ->setTranslator($translate);
        $test = $this->element->getMultiOption('barvalue');
        $this->assertEquals($translations[$options['barvalue']], $test);

        $test = $this->element->getMultiOptions();
        foreach ($test as $key => $value) {
            $this->assertEquals($translations[$options[$key]], $value);
        }
    }

    public function testOptionLabelsAreUntouchedIfTranslatonDoesNotExistInnTranslateAdapter()
    {
        $translations = include dirname(__FILE__) . '/../_files/locale/array.php';
        $translate    = new Zend_Translate('array', $translations, 'en');
        $translate->setLocale('en');

        $options = array(
            'foovalue' => 'Foo',
            'barvalue' => 'Bar',
            'testing'  => 'Test Value',
        );
        $this->element->addMultiOptions($options)
                      ->setTranslator($translate);
        $test = $this->element->getMultiOption('testing');
        $this->assertEquals($options['testing'], $test);
    }

    public function testMultiselectIsArrayByDefault()
    {
        $this->assertTrue($this->element->isArray());
    }

    /**
     * @group ZF-5568
     */
    public function testOptGroupTranslationsShouldWorkAfterPopulatingElement()
    {
        $translations = array(
            'ThisIsTheLabel'      => 'Optgroup label',
            'ThisShouldNotShow'   => 'Foo Value',
            'ThisShouldNeverShow' => 'Bar Value'
        );
        $translate = new Zend_Translate('array', $translations, 'en');
        $translate->setLocale('en');

        $options = array(
            'ThisIsTheLabel' => array(
                'foovalue' => 'ThisShouldNotShow',
                'barvalue' => 'ThisShouldNeverShow',
            ),
        );

        $this->element->setTranslator($translate)
                      ->addMultiOptions($options);

        $this->element->setValue('barValue');

        $html = $this->element->render($this->getView());
        $this->assertStringContainsString($translations['ThisIsTheLabel'], $html, $html);
    }

    /**
     * @group ZF-5937
     */
    public function testAddMultiOptionShouldWorkAfterTranslatorIsDisabled()
    {
        $options = array(
            'foovalue' => 'Foo',
        );
        $this->element->setDisableTranslator(true)
                      ->addMultiOptions($options);
        $test = $this->element->getMultiOption('foovalue');
        $this->assertEquals($options['foovalue'], $test);
    }

    /**
     * @group ZF-11667
     */
    public function testSimilarErrorMessagesForMultiElementAreNotDuplicated()
    {
        $this->element->setConcatJustValuesInErrorMessage(true);

        // create element with 4 checkboxes
        $this->element->setMultiOptions(array(
            'multiOptions' => array(
                array('key' => 'a', 'value' => 'A'),
                array('key' => 'b', 'value' => 'B'),
                array('key' => 'c', 'value' => 'C'),
                array('key' => 'd', 'value' => 'D'),
            )
        ));

        // check 3 of them
        $this->element->setValue(array('A', 'B', 'D'));

        // later on, fails some validation on submit
        $this->element->addError('some error! %value%');

        $this->assertEquals(
            array('some error! A; B; D'),
            $this->element->getMessages()
        );
    }
}
