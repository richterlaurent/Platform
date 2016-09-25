<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdvertController extends Controller
{
    public function indexAction($page)
    {

        if($page < 1){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }else {

            // Notre liste d'annonce en dur
            $listAdverts = array(
                array(
                    'title'   => 'Recherche développpeur Symfony',
                    'id'      => 1,
                    'author'  => 'Alexandre',
                    'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                    'date'    => new \Datetime()),
                array(
                    'title'   => 'Mission de webmaster',
                    'id'      => 2,
                    'author'  => 'Hugo',
                    'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                    'date'    => new \Datetime()),
                array(
                    'title'   => 'Offre de stage webdesigner',
                    'id'      => 3,
                    'author'  => 'Mathieu',
                    'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                    'date'    => new \Datetime())
            );

            return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts'=> $listAdverts));
        }
    }


    public function viewAction($id)
    {
        $advert = array(
            'title'   => 'Recherche développpeur Symfony2',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert'=>$advert) );
    }


    public function addAction(Request $request)
    {

        $antispam = $this->container->get('oc_platform.antispam');
            $text = "Bonjour je suis nouveau sur cette platform et je trouve qu'elle fonctionne à merveille! Bravo.. Haha..";

        if($spam = $antispam->isSpam($text)){
            throw new \Exception('Votre message a été détecté comme spam.');
        }

        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('info', 'Annonce bien enregistrée.');
            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        }
        // Si pas en post, on afficher le formulaire =>
        return $this->render('OCPlatformBundle:Advert:add.html.twig', array());
    }

    public function editAction($id, Request $request){

        $advert = array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );

        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('info', 'Annonce bien modifiée.');
            return $this->redirectToRoute('oc_platform_view', array('id'=>$id));
        }else{
            return $this->render('OCPlatformBundle:Advert:edit.html.twig', array('advert'=>$advert));
        }
    }

    public function deleteAction($id, Request $request){

        $advert = array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );

        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');
            return $this->redirectToRoute('oc_platform_home', array('id'=>$id));
        }else{
            return $this->render('OCPlatformBundle:Advert:delete.html.twig', array('advert'=>$advert));
        }

    }


    public function menuAction($limit){

        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de Webmaster'),
            array('id' => 9, 'title' => 'Offre de stage WebDesigner'),
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }



}
