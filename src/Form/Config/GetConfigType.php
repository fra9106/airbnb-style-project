<?php 

namespace App\Form\Config;

use Symfony\Component\Form\AbstractType;

class GetConfigType extends AbstractType
{
    /**
     * Config de base champs form
     *
     * @param [type] $label
     * @param [type] $placeholder
     * @param array $options
     * @return array
     */
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