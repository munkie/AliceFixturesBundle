<?php

namespace h4cc\AliceFixturesBundle;

use Symfony\Component\OptionsResolver\OptionsResolver;

class FixtureOptionsResolver extends OptionsResolver
{
    public function __construct()
    {
        $this->setDefaults([
            'locale' => 'en_EN',
            'seed' => 1,
            'do_drop' => false,
            'do_persist' => true,
            'order' => 1,
        ]);
    }
}
