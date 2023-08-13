<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMaterialsAPIRequest;
use App\Http\Requests\API\UpdateMaterialsAPIRequest;
use App\Models\Materials;
use App\Repositories\MaterialsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class MaterialsAPIController
 */
class MaterialsAPIController extends AppBaseController
{
    private MaterialsRepository $materialsRepository;

    public function __construct(MaterialsRepository $materialsRepo)
    {
        $this->materialsRepository = $materialsRepo;
    }

    /**
     * Display a listing of the Materials.
     * GET|HEAD /materials
     */
    public function index(Request $request): JsonResponse
    {
        $materials = Materials::get(['name', 'materials_uuid']);

        return $this->sendResponse($materials->toArray(), 'Materials retrieved successfully');
    }
}

//     /**
//      * Store a newly created Materials in storage.
//      * POST /materials
//      */
//     public function store(CreateMaterialsAPIRequest $request): JsonResponse
//     {
//         $input = $request->all();

//         $materials = $this->materialsRepository->create($input);

//         return $this->sendResponse($materials->toArray(), 'Materials saved successfully');
//     }

//     /**
//      * Display the specified Materials.
//      * GET|HEAD /materials/{id}
//      */
//     public function show($id): JsonResponse
//     {
//         /** @var Materials $materials */
//         $materials = $this->materialsRepository->find($id);

//         if (empty($materials)) {
//             return $this->sendError('Materials not found');
//         }

//         return $this->sendResponse($materials->toArray(), 'Materials retrieved successfully');
//     }

//     /**
//      * Update the specified Materials in storage.
//      * PUT/PATCH /materials/{id}
//      */
//     public function update($id, UpdateMaterialsAPIRequest $request): JsonResponse
//     {
//         $input = $request->all();

//         /** @var Materials $materials */
//         $materials = $this->materialsRepository->find($id);

//         if (empty($materials)) {
//             return $this->sendError('Materials not found');
//         }

//         $materials = $this->materialsRepository->update($input, $id);

//         return $this->sendResponse($materials->toArray(), 'Materials updated successfully');
//     }

//     /**
//      * Remove the specified Materials from storage.
//      * DELETE /materials/{id}
//      *
//      * @throws \Exception
//      */
//     public function destroy($id): JsonResponse
//     {
//         /** @var Materials $materials */
//         $materials = $this->materialsRepository->find($id);

//         if (empty($materials)) {
//             return $this->sendError('Materials not found');
//         }

//         $materials->delete();

//         return $this->sendSuccess('Materials deleted successfully');
//     }
// }
