<?php


namespace App\Controller;


use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{

    /**
     * @Route ("/departments/list", name="listDepartment")
     * @param DepartmentRepository $repository
     * @return JsonResponse
     */
    public function index(DepartmentRepository $repository): JsonResponse
    {
        $listDepartment = $repository->findAll();
        $dataJson = [];
        foreach ($listDepartment as $department){
            $dataJson[$department->getId()] = $department->getNameDepartment();
        }
        return $this->json([
            "data" => $dataJson],
            200);
    }
}