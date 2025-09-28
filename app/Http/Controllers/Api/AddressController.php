<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\UserAddressRepositoryInterface;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Requests\SetDefaultAddressRequest;
use App\Models\Address;


class AddressController extends Controller
{
    protected UserAddressRepositoryInterface $addressRepository;

    public function __construct(UserAddressRepositoryInterface $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * List all addresses for the authenticated user.
     */
    public function index(Request $request)
    {
        $addresses = $this->addressRepository->listForUser($request->user());

        return response()->json([
            'data' => $addresses
        ]);
    }

    /**
     * Store a new address for the authenticated user.
     */
    public function store(StoreAddressRequest $request)
    {
        $address = $this->addressRepository->createForUser($request->user(), $request->validated());

        return response()->json([
            'message' => 'Address created successfully',
            'data' => $address
        ], 201);
    }

    /**
     * Update an existing address.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        // $this->authorize('update', $address); // optional: policy check

        $updated = $this->addressRepository->update($address, $request->validated());

        return response()->json([
            'message' => 'Address updated successfully',
            'data' => $updated
        ]);
    }

    /**
     * Delete an existing address.
     */
    public function destroy(Request $request, Address $address)
    {
        // $this->authorize('delete', $address); // optional: policy check

        $this->addressRepository->delete($address);

        return response()->json([
            'message' => 'Address deleted successfully'
        ]);
    }

    /**
     * Set a specific address as default.
     */
    public function setDefault(SetDefaultAddressRequest $request, Address $address)
    {
        // $this->authorize('update', $address); // optional: policy check

        $updated = $this->addressRepository->setDefault($request->user(), $address);

        return response()->json([
            'message' => 'Default address set successfully',
            'data' => $updated
        ]);
    }
}
