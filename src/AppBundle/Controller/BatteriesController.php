<?php
/**
 * Created by PhpStorm.
 * User: m.panko
 * Date: 4/18/2016
 * Time: 14:45
 */

namespace AppBundle\Controller;

use AppBundle\Entity\BatteryJournal;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class BatteriesController extends Controller
{
    /**
     * @Route("/batteries/statistic", name="statistic")
     */
    public function statisticAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:BatteryJournal');

        $batteries = $repository->getAggregatedData();

        return $this->render(
            'batteries/statistic.html.twig',
            array('batteries' => $batteries)
        );
    }

    /**
     * @Route("/batteries/add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $batteryJournal = new BatteryJournal();
        //$batteryJournal->setUsername('Write your name');
        //$batteryJournal->setType('Write battery type');
        //$batteryJournal->setAmount('Write count of batteries');

        $form = $this->createFormBuilder($batteryJournal)
            ->add('username', TextType::class)
            ->add('type', TextType::class)
            ->add('amount', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Add batteries'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($batteryJournal);
            $em->flush();

            return $this->redirectToRoute('statistic');
        }


        return $this->render('batteries/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}