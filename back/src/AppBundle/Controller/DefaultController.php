<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContactInformation;
use AppBundle\Entity\Interlocutor;
use AppBundle\Entity\MailExchange;
use AppBundle\Entity\Structure;
use AppBundle\Entity\StructureType;
use AppBundle\Entity\TelephoneExchange;
use AppBundle\Form\ContactInformationType;
use AppBundle\Form\InterlocutorType;
use AppBundle\Form\MailExchangeType;
use AppBundle\Form\StructureTypeType;
use AppBundle\Form\TelephoneExchangeType;
use Mailjet\MailjetBundle\Client\MailjetClient;
use Mailjet\Resources;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $structures_types = $this->getDoctrine()->getRepository("AppBundle:StructureType")->findAll();
        $newStructuretype = new StructureType();
        $formStructuresType = $this->get('form.factory')->create(StructureTypeType::class, $newStructuretype);
        $formStructuresType->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($formStructuresType->isSubmitted() && $formStructuresType->isValid()){
            $em->persist($newStructuretype);
            $em->flush();
        }
        $newStructure = new Structure();
        $formStructures = $this->get('form.factory')->create(\AppBundle\Form\StructureType::class, $newStructure);
        $formStructures->handleRequest($request);
        if ($formStructures->isSubmitted() && $formStructures->isValid()){
            $em->persist($newStructure);
            $em->flush();
        }
        $newInterlocutor = new ContactInformation();
        $formInterlocutor = $this->get('form.factory')->create(ContactInformationType::class, $newInterlocutor);
        $formInterlocutor->handleRequest($request);
        if ($formInterlocutor->isSubmitted() && $formInterlocutor->isValid()){
            $em->persist($newInterlocutor);
            $em->flush();
        }
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            "formStructures" => $formStructures->createView(),
            "formStructuresType" => $formStructuresType->createView(),
            "formInterlocutor" => $formInterlocutor->createView(),
            "structuresTypes" => $structures_types,
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/get-history", name="history")
     */
    public function getHistoriqueStructureAction(Request $request){
        if ($request->get("interlocutor") != null){
            $inter = $request->get("interlocutor");
            $interEntity = $this->getDoctrine()->getRepository("AppBundle:Interlocutor")->find($inter);
            // FORM TELEPHONE
            $telExchange = new TelephoneExchange();
            if (count($interEntity->getContactInformations()) > 0 && $interEntity->getContactInformations()[0]->getTelephone())
                $telExchange->setTelephoneNumber($interEntity->getContactInformations()[0]->getTelephone());
            $formTel = $this->get('form.factory')->create(TelephoneExchangeType::class, $telExchange, array('action' => $this->generateUrl('history')));
            // FORM MAIL
            $mailExchange = new MailExchange();
            if (count($interEntity->getContactInformations()) > 0 && $interEntity->getContactInformations()[0]->getMail() != null)
                $mailExchange->setEmailAdress($interEntity->getContactInformations()[0]->getMail());
            $formMail = $this->get('form.factory')->create(MailExchangeType::class, $mailExchange, array('action' => $this->generateUrl('history')));
            $formMail->handleRequest($request);
            $formTel->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            if ($formMail->isSubmitted() && $formMail->isValid()) {
                $mailExchange->setDate(new \DateTime());
                $mailExchange->setIsSeen(false);
                $mailExchange->setInterlocutor($interEntity);
                $client = new MailjetClient("bbe7791eafdc50b5f065a49f19fe5dfa", "c421b84dfb234768ba81575e7b1c9020");
                $body = [
                    'FromEmail' => "damien@connectmyresto.com",
                    'FromName' => "Damien Huctin",
                    'Subject' => $mailExchange->getTitle(),
                    'Text-part' => "",
                    'Html-part' => $mailExchange->getDescription(),
                    'Recipients' => [
                        [
                            'Email' => $mailExchange->getEmailAdress()
                        ]
                    ]
                ];
                $resp = $client->post(Resources::$Email, ['body' => $body]);
                $msgID = $resp->getBody()["Sent"][0]["MessageID"];
                $mailExchange->setMailjetId($msgID);
                $em->persist($mailExchange);
                $em->flush();
                if ($resp->success())
                    return $this->redirectToRoute("homepage");
                else
                    return new Response("Une erreur est survenue, le mail n'a pas été envoyé");
            } elseif ($formTel->isSubmitted() && $formTel->isValid()) {
                $telExchange->setDate(new \DateTime());
                $telExchange->setInterlocutor($interEntity);
                $em->persist($telExchange);
                $em->flush();
                return $this->redirectToRoute("homepage");
            } else {
                return $this->render('@App/Exchanges/view_history.html.twig', [
                    "interlocutor" => $interEntity,
                    "formMail" => $formMail->createView(),
                    "formTel" => $formTel->createView(),
                ]);
            }
        }
    }

}
