<?php 

namespace App\Form\Config;

use Symfony\Component\Form\AbstractType;

class getConfigType extends AbstractType
{
    protected function getConfigurationForm($label, $placeholder, $options = []) {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder,
                'required' => false
            ]
        ], $options);
    }

}