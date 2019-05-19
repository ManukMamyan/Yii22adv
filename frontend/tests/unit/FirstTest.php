<?php namespace frontend\tests;

use common\models\LoginForm;
use frontend\models\ContactForm;

class FirstTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testVariable()
    {
        $a = 123;
        $this->assertEquals(123, $a);
        $this->assertLessThan(124, $a);

        $b = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
        ];

        $this->assertArrayHasKey('a', $b);

        $obj = new LoginForm();
        expect('rememberMe equals true', $obj->rememberMe)->equals(true);

        $form = new ContactForm([
            'name' => 'Adam Smith',
            'email' => 'someemail@mail.com',
            'subject' => 'some content',
            'body' => 'Hello World',
        ]);

        $this->assertAttributeEquals('Adam Smith', 'name', $form);
        $this->assertAttributeEquals('someemail@mail.com', 'email', $form);
        $this->assertAttributeEquals('some content', 'subject', $form);
        $this->assertAttributeEquals('Hello World', 'body', $form);
    }
}