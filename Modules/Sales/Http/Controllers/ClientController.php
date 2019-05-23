<?php

namespace Modules\Sales\Http\Controllers;

use App\Filters\ClientFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\Address;
use Modules\Sales\Entities\Contact;
use Modules\Sales\Http\Requests\ClientRequest;
use JWTAuth;
use Modules\Sales\Entities\Client;
use DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, ClientFilter $filter)
    {
        $client = Client::with('contacts')
            ->with('addresses')
            ->filter($filter)
            ->paginate($request->per_page);
        return response()->json($client);

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('sales::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(ClientRequest $request)
    {

        $client = Client::create([
            'name' => $request->name,
            'tin_number' => $request->tin_number,
            'website' => $request->website,
            'phone' => $request->phone,
            'currency' => $request->currency,
            'payment_term' => $request->payment_term,
            'public_note' => $request->public_note,
            'private_note' => $request->private_note,
            'company_size' => $request->company_size,
            'industry' => $request->industry,
        ]);

        $client->contacts()->createMany($request->contacts);
        $client->addresses()->createMany($request->addresses);

        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Client "%s" created successfully', $client->name)
                ]
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $client = Client::with('contacts')
            ->with('addresses')
            ->where('id', $id)
            ->first();
        return response()->json($client);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('sales::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
