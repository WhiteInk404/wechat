<?php

class SensitiveWordsTest extends TestCase
{
    public function testFilter()
    {
        $content = SensitiveWords::filter('用一张假钞，买一把假枪');
        $this->assertContains('***', $content, $content);
    }
}
