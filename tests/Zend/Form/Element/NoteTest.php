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
 * Test class for Zend_Form_Element_Text
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Form
 */
class Zend_Form_Element_NoteTest extends PHPUnit\Framework\TestCase
{
    protected $element;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->element = new Zend_Form_Element_Note('foo');
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

    public function testNoteElementSubclassesXhtmlElement()
    {
        $this->assertTrue($this->element instanceof Zend_Form_Element_Xhtml);
    }

    public function testNoteElementInstanceOfBaseElement()
    {
        $this->assertTrue($this->element instanceof Zend_Form_Element);
    }

    public function testNoteElementUsesNoteHelperInViewHelperDecoratorByDefault()
    {
        $decorator = $this->element->getDecorator('viewHelper');
        $this->assertInstanceOf(Zend_Form_Decorator_ViewHelper::class, $decorator);

        $decorator->setElement($this->element);
        $helper = $decorator->getHelper();
        $this->assertEquals('formNote', $helper);
    }

    public function testNoteElementValidationIsAlwaysTrue()
    {
        // Solo
        $this->assertTrue($this->element->isValid('foo'));

        // Set required
        $this->element->setRequired(true);
        $this->assertTrue($this->element->isValid(''));
        // Reset
        $this->element->setRequired(false);

        // Examining various validators
        $validators = array(
            array(
                'options' => array('Alnum'),
                'value'   => 'aa11?? ',
            ),
            array(
                'options' => array('Alpha'),
                'value'   => 'aabb11',
            ),
            array(
                'options' => array(
                    'Between',
                    false,
                    array(
                        'min' => 0,
                        'max' => 10,
                    )
                ),
                'value' => '11',
            ),
            array(
                'options' => array('Date'),
                'value'   => '10.10.2000',
            ),
            array(
                'options' => array('Digits'),
                'value'   => '1122aa',
            ),
            array(
                'options' => array('EmailAddress'),
                'value'   => 'foo',
            ),
            array(
                'options' => array('Float'),
                'value'   => '10a01',
            ),
            array(
                'options' => array(
                    'GreaterThan',
                    false,
                    array('min' => 10),
                ),
                'value' => '9',
            ),
            array(
                'options' => array('Hex'),
                'value'   => '123ABCDEFGH',
            ),
            array(
                'options' => array(
                    'InArray',
                    false,
                    array(
                        'key'      => 'value',
                        'otherkey' => 'othervalue',
                    )
                ),
                'value' => 'foo',
            ),
            array(
                'options' => array('Int'),
                'value'   => '1234.5',
            ),
            array(
                'options' => array(
                    'LessThan',
                    false,
                    array('max' => 10),
                ),
                'value' => '11',
            ),
            array(
                'options' => array('NotEmpty'),
                'value'   => '',
            ),
            array(
                'options' => array(
                    'Regex',
                    false,
                    array('pattern' => '/^Test/'),
                ),
                'value' => 'Pest',
            ),
            array(
                'options' => array(
                    'StringLength',
                    false,
                    array(
                        6,
                        20,
                    )
                ),
                'value' => 'foo',
            ),
        );

        foreach ($validators as $validator) {
            // Add validator
            $this->element->addValidators(array($validator['options']));

            // Testing
            $this->assertTrue($this->element->isValid($validator['value']));

            // Remove validator
            $this->element->removeValidator($validator['options'][0]);
        }
    }
}
