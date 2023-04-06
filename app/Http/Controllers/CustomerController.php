<?php

namespace App\Http\Controllers;

use App\Services\CustomerServiceInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    use ApiResponser;

    /**
     * @var  CustomerServiceInterface
     */
    private CustomerServiceInterface $customerService;

    /**
     * @param  CustomerServiceInterface $customerService
     * 
     * @return void
     */
    public function __construct(CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $customers = $this->customerService->list();

        return $this->apiResponse(
            'Customers were successfully retrieved',
            Response::HTTP_OK,
            [
                'total' => count($customers),
                'customers' => $customers
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * 
     * @return Response
     */
    public function show(string $id): Response
    {
        $customer = $this->customerService->show($id);

        if (is_null($customer)) {
            return $this->apiResponse(
                'Customer not found',
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->apiResponse(
            'Customer was successfully retrieved',
            Response::HTTP_OK,
            $customer
        );
    }
}
