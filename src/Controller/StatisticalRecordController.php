<?php

namespace App\Controller;

use App\Repository\StatisticalRecordRepository;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\EntityManagerInterface;

class StatisticalRecordController extends Controller
{
	/**
     * @var integer HTTP status code - 200 (OK) by default
    */
    protected $statusCode = 200;

    /**
     * @Route("/statistical-record/{id}/edit", name="edit", methods={"PUT"})
     */
    public function update(Request $request, EntityManagerInterface $entityManager, StatisticalRecordRepository $statisticalRecordRepository)
    {
        $request = json_decode(
            $request->getContent(),
            true
        );

        $record = $statisticalRecordRepository->find($request['id']);

        $record->setTimestamp(new \DateTime($request['timestamp']));
        $record->setReferrer($request['referrer']);
        $record->setIp($request['ip']);
        $record->setBrowser($request['browser']);

        $entityManager->persist($record);

        $entityManager->flush();

        return new JsonResponse($statisticalRecordRepository->transformRecord($record), $this->statusCode);
    }

      /**
     * @Route("/statistical-record/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function destroy($id, EntityManagerInterface $entityManager, StatisticalRecordRepository $statisticalRecordRepository)
    {

        $record = $statisticalRecordRepository->find($id);

        $entityManager->remove($record);

        $entityManager->flush();

        return new JsonResponse($id, $this->statusCode);
    }
}
