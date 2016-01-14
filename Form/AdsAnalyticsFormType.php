<?php
/**
 * Created by PhpStorm.
 * User: tigran
 * Date: 8/7/15
 * Time: 10:39 AM
 */
namespace LSoft\AdBundle\Form ;

use LSoft\AdBundle\Controller\Admin\AdsAnalyticsController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdsAnalyticsFormType extends AbstractType
{

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('ad', 'entity', array('class'=>'LSoft\AdBundle\Entity\Ad', 'label'=>'Select Ad',
				'multiple'=>true, 'expanded'=>false,
				'required'=>false, 'choice_label'=>'name', 'choices_as_values'=>true, 'placeholder' => 'Chose ads',
				))
			->add('from', 'datetime', array('date_widget' => "single_text", 'time_widget' => "single_text", 'required'=>false, 'label'=>'From'))
			->add('to', 'datetime', array('date_widget' => "single_text", 'time_widget' => "single_text", 'required'=>false, 'label'=>'To'))
			->add('chart_type', 'choice', array( 'choices'=>array(
				AdsAnalyticsController::DAYS=>'Day', AdsAnalyticsController::WEEK=>'Week', AdsAnalyticsController::MONTH=>'Month', AdsAnalyticsController::YEAR=>'Year'),
				'label'=>'Chart types', 'required'=>false, 'choices_as_values'=>false, 'placeholder' => 'Chart show by',
				'attr'=>array('class'=>'form-control')))
			->add('filter', 'submit', array('label' => 'Filter', 'attr' => array('class'=>'btn btn-info btn-square')))
			->add('clear', 'submit', array('label' => 'Clear', 'attr' => array('class'=>'btn btn-default')))
		;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'ad_filter_chart';
	}
}