<?php

namespace Page;

class BasePageObject
{

    public $continueBtn = '.m-test-checkboxes__continue, [type=submit]';
    public $backButton = '.m-back__bbutton';
    public $helpButton = '.js-help-btn';

    public function __construct($actor)
    {
        $this->tester = $actor;
    }
}
