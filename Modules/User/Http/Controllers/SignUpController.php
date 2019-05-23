<?php

namespace Modules\User\Http\Controllers;

use Carbon\Carbon;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\ChartOfAccount;
use Modules\User\Entities\Company;
use Modules\User\Entities\Role;
use Modules\User\Entities\Permission;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\SignUpRequest;

class SignUpController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('user::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(SignUpRequest $request)
    {
        // Create a company; set valid_to 30 days from now
        $company = Company::create([
            'name' => $request->companyName,
            'phone' => $request->companyPhone,
            'address' => $request->companyAddress,
            'valid_to' => (new Carbon())->addDays(45),
            'pricing_plan_id' => $request->pricing_plan_id,
            ]
        );

        Landlord::addTenant('company_id', $company->id);
        // Create 2 roles Admin, Bill Collector
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Admin Account'
        ]);
        $bill_collector = Role::create([
            'name' => 'bill_collector',
            'display_name' => 'Bill Collector',
            'description' => 'Bill Collector'
        ]);
        $manager = Role::create([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Manager'
        ]);
        $operator = Role::create([
            'name' => 'operator',
            'display_name' => 'Operator',
            'description' => 'Operator'
        ]);
        // Admin will have all permissions
        $allPermissions = Permission::all();
        $managerPermissions = Permission::where('manager_permission_group', 1)->get();
        $operatorPermissions = Permission::where('operator_permission_group', 1)->get();

        $admin->attachPermissions($allPermissions);
        $bill_collector->attachPermissions([]);

        $manager->attachPermissions($managerPermissions);
        $operator->attachPermissions($operatorPermissions);

        //Create an Admin User
        $user = new User;
        $user->name = $request->adminName;
        $user->email = $request->adminEmail;
        $user->password = bcrypt($request->adminPassword);
        $user->company_id = $company->id;
        $user->active = 1;
        $user->immutable = 1;
        $user->save();
//        return response()->json($user);
        $user->attachRole($admin);

        // Create Base Chart of Accounts
        $asset = ChartOfAccount::create([
            'code' => '10000',
            'name' => 'Assets',
            'description' => 'List of all assets',
        ]);

        $liability = ChartOfAccount::create([
            'code' => '20000',
            'name' => 'Liabilities',
            'description' => 'List of all liabilities',
        ]);

        $income = ChartOfAccount::create([
            'code' => '30000',
            'name' => 'Income',
            'description' => 'List of all income',
        ]);

        $expense = ChartOfAccount::create([
            'code' => '40000',
            'name' => 'Expenses',
            'description' => 'List of all expenses',
        ]);

        $equity = ChartOfAccount::create([
            'code' => '50000',
            'name' => 'Equity',
            'description' => 'List of all equity',
        ]);

        ChartOfAccount::create([
            'code' => '10001',
            'name' => 'Cash On Hand',
            'description' => 'Cash',
            'is_payment_account' => 1,
            'parent_account_id' => $asset->id
        ]);
        ChartOfAccount::create([
            'code' => '20001',
            'name' => 'Accounts Payable',
            'description' => 'Accounts Payable',
            'parent_account_id' => $liability->id
        ]);
        ChartOfAccount::create([
            'code' => '30001',
            'name' => 'Sales/Services',
            'description' => 'Sales/Services',
            'parent_account_id' => $income->id
        ]);
        ChartOfAccount::create([
            'code' => '40001',
            'name' => 'Utilities',
            'description' => 'Utility expenses',
            'parent_account_id' => $expense->id
        ]);
        ChartOfAccount::create([
            'code' => '40002',
            'name' => 'Meals and Entertainment',
            'description' => 'Meals and Entertainment',
            'parent_account_id' => $expense->id
        ]);
        ChartOfAccount::create([
            'code' => '50001',
            'name' => 'Owner Investment/Drawings',
            'description' => 'Owner Investment/Drawings',
            'parent_account_id' => $equity->id
        ]);

        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Company "%s" created successfully', $company->name)
                ]
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('user::edit');
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
