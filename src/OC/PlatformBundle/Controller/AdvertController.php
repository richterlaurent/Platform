<?php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdvertController extends Controller
{
    public function indexAction($page, Request $request)
    {
        if($page < 1){
            $this->createNotFoundException('Page "'.$page.'" inexistante.');
        }

        $nbPerPage = 4;

        $query = $this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Advert')->getAdverts();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', $page),
            $nbPerPage,
            array('defaultSortFieldName' => 'a.date', 'defaultSortDirection' => 'desc')
        );

        $nbPages = $pagination->getTotalItemCount();

        if($page>$nbPages){
            $this->createNotFoundException('La page "'.$page.'" n\'existe pas');
        }

        return $this->render('OCPlatformBundle:Advert:index.html.twig', array('pagination'=>$pagination));

    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->find("OCPlatformBundle:Advert", $id);

        if (null === $advert){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listApplications = $em->getRepository("OCPlatformBundle:Application")->findBy(array('advert'=>$advert));
        $listAdvertSkills = $em->getRepository("OCPlatformBundle:AdvertSkill")->findBy(array('advert'=>$advert));

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert'=>$advert, 'listApplications'=>$listApplications, 'listAdvertSkills'=>$listAdvertSkills));
    }


    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find(1);

        // Création d'une première candidature
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        // Création d'une deuxième candidature par exemple
        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé.");

        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        $em->persist($application1);
        $em->persist($application2);
        $em->flush();


        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('info', 'Annonce bien enregistrée.');
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }
        // Si pas en post, on afficher le formulaire =>
        return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $advert = $em->find("OCPlatformBundle:Advert", $id);

        if (null === $advert){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('info', 'Annonce bien modifiée.');
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId() ) );
        }else{
            return $this->render('OCPlatformBundle:Advert:edit.html.twig', array('advert'=>$advert));
        }
    }

    public function deleteAction($id, Request $request){

        $em = $this->getDoctrine()->getManager();
        $advert = $em->find("OCPlatformBundle:Advert", $id);

        if(null===$advert){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');
            return $this->redirectToRoute('oc_platform_home', array('id' => $advert->getId() ) );
        }else{
            return $this->render('OCPlatformBundle:Advert:delete.html.twig', array('advert'=>$advert));
        }
    }


    public function menuAction($limit){
        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->findBy(
            array(),
            array('date' => 'desc'),
            $limit,
            0
        );
        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }


    /**
     * @param $days
     *
     * Action calling the "purge" in the oc_platform.purger.advert_purger service
     * Returns an html response with the number of adverts deleted
     *
     * @return Response
     */
    public function purgeAction($days){
        $advertPurger = $this->get('oc_platform.purger.advert_purger');
        $count = $advertPurger->purge($days);
        return new Response("<body>$count annonce(s) a(ont) été supprimée(s).</body>");
    }


    /**
     *
     * Action called with the url : /platform/test
     *
     * Used to do wathever you want, for example insert advert in your DDB.
     * For now : it returns a html response with the slug generated by the new advert
     *
     * @return Response
     */
    public function testAction(){
        // new advert
        $advert = new Advert();
        $advert->setAuthor("Marcel En Retard");
        $advert->setTitle("Pour Delete Days");
        $advert->setContent("Blablablablablabla.....");
        $advert->setDate(new \DateTime('-33 days'));

        $em = $this->getDoctrine()->getManager();

        // add every Category to the advert
        $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();
        foreach($listCategories as $category){
            $advert->addCategory($category);
        }

        // add every skill with expert level as AdvertSkill entities to the advert
        $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();
        foreach($listSkills as $skill){
            $advertSkill = new AdvertSkill();
            $advertSkill->setAdvert($advert);
            $advertSkill->setSkill($skill);
            $advertSkill->setLevel("Expert");
            $em->persist($advertSkill);
        }

        $em->persist($advert);
        $em->flush();

        return new Response('Slug généré : '.$advert->getSlug());
    }

}
