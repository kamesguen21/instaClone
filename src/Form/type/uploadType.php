<?php


namespace App\Form\type;

use App\Entity\Hashtag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class uploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('imageFile', VichImageType::class, [
            'required' => true,
            'allow_delete' => true,
            'download_label' => '...',
            'download_uri' => true,
            'image_uri' => true,
            'asset_helper' => true,
            'attr' => array(
                'accept' => '.png,.jpg,.jpeg'
            )
        ])->add('caption', TextType::class, array('required' => false, 'label' => 'Caption', 'attr' => array(
            'placeholder' => 'Caption'
        )))->add('tagsText', TextType::class, array('required' => false, 'label' => 'Tags', 'attr' => array(
            'placeholder' => 'Tags'
        )))->add('setAsProfile', CheckboxType::class,array('required' => false, 'label' => 'set as profile picture'));

    }
}