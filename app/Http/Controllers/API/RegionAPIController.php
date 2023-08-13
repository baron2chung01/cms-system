<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRegionAPIRequest;
use App\Http\Requests\API\UpdateRegionAPIRequest;
use App\Models\Region;
use App\Repositories\RegionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class RegionAPIController
 */
class RegionAPIController extends AppBaseController
{
    private RegionRepository $regionRepository;

    public function __construct(RegionRepository $regionRepo)
    {
        $this->regionRepository = $regionRepo;
    }

    /**
     * Display a listing of the Regions.
     * GET|HEAD /regions
     */
    public function index(Request $request): JsonResponse
    {
        $regions = Region::get(['name', 'regions_uuid']);

        return $this->sendResponse($regions->toArray(), 'Regions retrieved successfully');
    }
}

//     /**
//      * Store a newly created Region in storage.
//      * POST /regions
//      */
//     public function store(CreateRegionAPIRequest $request): JsonResponse
//     {
//         $input = $request->all();

//         $region = $this->regionRepository->create($input);

//         return $this->sendResponse($region->toArray(), 'Region saved successfully');
//     }

//     /**
//      * Display the specified Region.
//      * GET|HEAD /regions/{id}
//      */
//     public function show($id): JsonResponse
//     {
//         /** @var Region $region */
//         $region = $this->regionRepository->find($id);

//         if (empty($region)) {
//             return $this->sendError('Region not found');
//         }

//         return $this->sendResponse($region->toArray(), 'Region retrieved successfully');
//     }

//     /**
//      * Update the specified Region in storage.
//      * PUT/PATCH /regions/{id}
//      */
//     public function update($id, UpdateRegionAPIRequest $request): JsonResponse
//     {
//         $input = $request->all();

//         /** @var Region $region */
//         $region = $this->regionRepository->find($id);

//         if (empty($region)) {
//             return $this->sendError('Region not found');
//         }

//         $region = $this->regionRepository->update($input, $id);

//         return $this->sendResponse($region->toArray(), 'Region updated successfully');
//     }

//     /**
//      * Remove the specified Region from storage.
//      * DELETE /regions/{id}
//      *
//      * @throws \Exception
//      */
//     public function destroy($id): JsonResponse
//     {
//         /** @var Region $region */
//         $region = $this->regionRepository->find($id);

//         if (empty($region)) {
//             return $this->sendError('Region not found');
//         }

//         $region->delete();

//         return $this->sendSuccess('Region deleted successfully');
//     }
// }
