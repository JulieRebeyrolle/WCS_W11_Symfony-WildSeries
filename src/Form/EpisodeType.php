<?php

namespace App\Form;

use App\Entity\Episode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class EpisodeType extends AbstractType
{
    public $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->trans('episode.title.label')
            ])
            ->add('number', TextType::class, [
                'label' => $this->translator->trans('episode.number.label')
            ])
            ->add('synopsis', TextType::class, [
                'label' => $this->translator->trans('episode.synopsis.label')
            ])
            ->add('season_id', null, [
                'label' => $this->translator->trans('episode.season_id.label'),
            'choice_label' => 'id'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
