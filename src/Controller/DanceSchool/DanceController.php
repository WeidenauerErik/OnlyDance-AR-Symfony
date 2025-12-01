<?php

namespace App\Controller\DanceSchool;

use App\Entity\Dance;
use App\Entity\Steps;
use App\Repository\DanceSchoolRepository;
use App\Repository\DanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard/danceschool/{schoolId}/dances')]
class DanceController extends AbstractController
{
    #[Route('/', name: 'danceschool_dances_index', methods: ['GET'])]
    public function index(int $schoolId, DanceSchoolRepository $repo): Response
    {
        $school = $repo->find($schoolId);

        return $this->render('danceSchool/dance/index.html.twig', [
            'school' => $school,
            'dances' => $school->getDances()
        ]);
    }

    #[Route('/add', name: 'danceschool_dances_add', methods: ['POST'])]
    public function add(
        int $schoolId,
        Request $request,
        DanceSchoolRepository $schoolRepo,
        EntityManagerInterface $em
    ): Response {

        if (!$this->isCsrfTokenValid('add_dance', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException("Invalid CSRF token");
        }

        $jsonInput = $request->request->get('json');

        $data = json_decode($jsonInput, true);

        if (!$data) {
            $this->addFlash('danger', 'Invalid JSON format!');
            return $this->redirectToRoute('danceschool_dances_index', ['schoolId' => $schoolId]);
        }

        if (!isset($data['name']) || !isset($data['BPM']) || !isset($data['data']) || !is_array($data['data'])) {
            $this->addFlash('danger', 'JSON must contain name, BPM and data[]');
            return $this->redirectToRoute('danceschool_dances_index', ['schoolId' => $schoolId]);
        }

        $school = $schoolRepo->find($schoolId);

        $dance = new Dance();
        $dance->setName($data['name']);
        $dance->setBPM($data['BPM']);
        $dance->setOwner($school);

        $em->persist($dance);

        // Add Steps
        foreach ($data['data'] as $stepData) {

            $required = [
                'm1_x','m1_y','m1_toe','m1_heel','m1_rotate',
                'm2_x','m2_y','m2_toe','m2_heel','m2_rotate'
            ];

            foreach ($required as $field) {
                if (!array_key_exists($field, $stepData)) {
                    $this->addFlash('danger', "Missing field '$field' in step");
                    return $this->redirectToRoute('danceschool_dances_index', ['schoolId' => $schoolId]);
                }
            }

            $step = new Steps();
            $step->setDanceId($dance);

            $step->setM1X($stepData['m1_x']);
            $step->setM1Y($stepData['m1_y']);
            $step->setM1Toe($stepData['m1_toe']);
            $step->setM1Heel($stepData['m1_heel']);
            $step->setM1Rotate($stepData['m1_rotate']);

            $step->setM2X($stepData['m2_x']);
            $step->setM2Y($stepData['m2_y']);
            $step->setM2Toe($stepData['m2_toe']);
            $step->setM2Heel($stepData['m2_heel']);
            $step->setM2Rotate($stepData['m2_rotate']);

            $em->persist($step);
        }

        $em->flush();

        $this->addFlash('success', 'Dance added successfully!');
        return $this->redirectToRoute('danceschool_dances_index', ['schoolId' => $schoolId]);
    }

    #[Route('/delete/{danceId}', name: 'danceschool_dances_delete', methods: ['POST'])]
    public function delete(
        int $schoolId,
        int $danceId,
        DanceRepository $repo,
        EntityManagerInterface $em,
        Request $request
    ): Response {

        if (!$this->isCsrfTokenValid('delete_dance_'.$danceId, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException("Invalid CSRF token");
        }

        $dance = $repo->find($danceId);

        foreach ($dance->getSteps() as $step) {
            $em->remove($step);
        }

        $em->remove($dance);
        $em->flush();

        $this->addFlash('success', 'Dance deleted!');
        return $this->redirectToRoute('danceschool_dances_index', ['schoolId' => $schoolId]);
    }
}
