<?php

/**
 * This file is part of "LoTGD Bundle Name Color".
 *
 * @see https://github.com/lotgd-core/lodge-name-color-bundle
 *
 * @license https://github.com/lotgd-core/lodge-name-color-bundle/blob/master/LICENSE.txt
 * @author IDMarinas
 *
 * @since 0.1.0
 */

namespace Lotgd\Bundle\LodgeNameColorBundle\Form;

use Laminas\Filter;
use Lotgd\Bundle\LodgeNameColorBundle\LotgdLodgeNameColorBundle;
use Lotgd\Core\Tool\Sanitize;
use Lotgd\Core\Tool\Tool;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class NameColorType extends AbstractType
{
    private $parameter;
    private $tool;
    private $sanitize;

    public function __construct(ParameterBagInterface $parameter, Tool $tool, Sanitize $sanitize)
    {
        $this->parameter = $parameter;
        $this->tool      = $tool;
        $this->sanitize  = $sanitize;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraints = [];
        $required    = false;

        if ( ! $this->parameter->get('lotgd_bundle.lodge_name_color.allowed.bold'))
        {
            $constraints[] = new Assert\Regex([
                'pattern' => '/[`´][b]/',
                'match'   => false,
                'message' => 'lodge_name_color.no_bold',
            ]);
        }

        if ( ! $this->parameter->get('lotgd_bundle.lodge_name_color.allowed.italic'))
        {
            $constraints[] = new Assert\Regex([
                'pattern' => '/[`´][i]/',
                'match'   => false,
                'message' => 'lodge_name_color.no_italic',
            ]);
        }

        //-- Colors count
        $constraints[] = new Assert\Callback(function ($name, ExecutionContextInterface $context)
        {
            $colorCount = substr_count($name, '`') - substr_count($name, '`0');
            $limit = $this->parameter->get('lotgd_bundle.lodge_name_color.allowed.colors');

            if ($colorCount > $limit)
            {
                $context->addViolation('lodge_name_color.colors_count', [
                    '{{ limit }}' => $limit,
                    '{{ value }}' => $colorCount
                ]);
            }
        });

        //-- Check that not change name
        $constraints[] = new Assert\Callback(function ($name, ExecutionContextInterface $context)
        {
            if (strtolower($this->sanitize->fullSanitize($name)) != strtolower($this->sanitize->fullSanitize($this->tool->getPlayerBasename())))
            {
                $context->addViolation('lodge_name_color.no_equal', ['{{ name }}' => $name]);
            }
        });

        $constraints[] = new Assert\Length([
            'min'        => 0,
            'max'        => 40,
            'maxMessage' => 'lodge_name_color.long',
        ]);

        $builder
            ->add('new_name', TextType::class, [
                'required'    => $required,
                'label'       => 'form.name_color.new_name',
                'empty_data'  => '',
                'constraints' => $constraints,
                'filters'     => [
                    new Filter\PregReplace([
                        'pattern'     => '/[`´][ncHw]/',
                        'replacement' => '',
                    ]),
                ],
            ])

            ->add('button_preview', SubmitType::class, ['label' => 'form.name_color.button.preview'])
            ->add('button_change', SubmitType::class, [
                'label' => 'form.name_color.button.change',
                'attr'  => [
                    'class' => 'orange',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => null,
            'translation_domain' => LotgdLodgeNameColorBundle::TRANSLATION_DOMAIN,
        ]);
    }
}
