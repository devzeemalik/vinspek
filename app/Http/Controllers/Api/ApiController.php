<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ApiController extends Controller
{   
    public function login(Request $request){

        $get_res = $request->all();
        
        $data = [
            'email'    => 'required|email',
            'password' => 'required',
        ];

        $validateUser = Validator::make($get_res, $data);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()],
                 400);
        }

        if (Auth::attempt($get_res)) {
            $user = Auth::user();
            $token = $user->createToken('API TOKEN', ['*'], now()->addHours(6))->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $token
            ], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

    }

    public function register(Request $request){

        $get_res = $request->all();

        if ($get_res['user_type'] == 'customer') {
            $data = [
                'user.first_name' => 'required',
                'user.last_name' => 'required',
                'user.email' => 'required|email|unique:users,email',
                'user.phone' => 'required',
                'user.address' => 'required',
                'user.city' => 'required',
                'user.state' => 'required',
                'user.zip' => 'required',
                'user.password' => 'required',
                'user.confirm_password' => 'required|same:user.password',
                'user.work_with_other_inspection_companies' => 'required',
                'user.interested_in_doing_mobile_mechanic_work' => 'required',
                'user.ase' => 'required',
                'user.education' => 'required',
                'user.work_experience' => 'required',
                'vehicle.vin_number' => 'required',
                'vehicle.year' => 'required',
                'vehicle.make' => 'required',
                'vehicle.model' => 'required',
                'vehicle.dealership_name' => 'required',
                'vehicle.stock_number' => 'required',
                'vehicle.seller_contact' => 'required',
                'vehicle.address_permanent' => 'required',
                'vehicle.date' => 'required',
                'vehicle.note' => 'required',
                'vehicle.history_report' => 'required',
                'vehicle.assessment_report' => 'required',
                'payment.card_number' => 'required',
                'payment.mm' => 'required',
                'payment.cvv' => 'required',
                'payment.postal_code' => 'required',
            ];
        } elseif ($get_res['user_type'] == 'inspector') {
            $data = [
                'user.first_name' => 'required',
                'user.last_name' => 'required',
                'user.email' => 'required|email|unique:users,email',
                'user.phone' => 'required',
                'user.address' => 'required',
                'user.city' => 'required',
                'user.state' => 'required',
                'user.zip' => 'required',
                'user.password' => 'required',
                'user.confirm_password' => 'required|same:user.password',
                'user.confirm_password' => 'required|same:user.password',
                'user.work_with_other_inspection_companies' => 'required',
                'user.interested_in_doing_mobile_mechanic_work' => 'required',
                'user.ase' => 'required',
                'user.education' => 'required',
                'user.work_experience' => 'required',
            ];
        }

        $validateUser = Validator::make($get_res, $data);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()],
                 400);
        }

        $user = User::create([
            'name'     => $get_res['user']['first_name'],
            'email'    => $get_res['user']['email'],
            'password' => $get_res['user']['password']
        ]);

        if ($get_res['user_type'] == 'customer') {

            $customer = Customer::create([
                'user_id'     => $user->id,
                'user_type'   => $get_res['user_type'],
                'first_name'  => $get_res['user']['first_name'],
                'last_name'   => $get_res['user']['last_name'],
                'email'       => $get_res['user']['email'],
                'phone'       => $get_res['user']['phone'],
                'address'     => $get_res['user']['address'],
                'city'        => $get_res['user']['city'],
                'state'       => $get_res['user']['state'],
                'zip'         => $get_res['user']['zip'],
                'password'    => $get_res['user']['password'],
                'work_with_other_inspection_companies'     => $get_res['user']['work_with_other_inspection_companies'],
                'interested_in_doing_mobile_mechanic_work' => $get_res['user']['interested_in_doing_mobile_mechanic_work'],
            ]);

            $order = Order::create([
                'user_id'             => $user->id,
                'ase'                 => $get_res['user']['ase'],
                'education'           => $get_res['user']['education'],
                'work_experience'     => $get_res['user']['work_experience'],
                'vin_number'          => $get_res['vehicle']['vin_number'],
                'year'                => $get_res['vehicle']['year'],
                'make'                => $get_res['vehicle']['make'],
                'model'               => $get_res['vehicle']['model'],
                'dealership_name'     => $get_res['vehicle']['dealership_name'],
                'stock_number'        => $get_res['vehicle']['stock_number'],
                'seller_contact'      => $get_res['vehicle']['seller_contact'],
                'address_permanent'   => $get_res['vehicle']['address_permanent'],
                'date'                => $get_res['vehicle']['date'],
                'note'                => $get_res['vehicle']['note'],
                'history_report'      => $get_res['vehicle']['history_report'],
                'assessment_report'   => $get_res['vehicle']['assessment_report'],
            ]);

            $payment = Payment::create([
                'user_id'             => $user->id,
                'card_number'         => $get_res['payment']['card_number'],
                'mm'                  => $get_res['payment']['mm'],
                'cvv'                 => $get_res['payment']['cvv'],
                'postal_code'         => $get_res['payment']['postal_code']
            ]);

        }elseif($get_res['user_type'] == 'inspector'){

            $customer = Customer::create([
                'user_id'     => $user->id,
                'user_type'   => $get_res['user_type'],
                'first_name'  => $get_res['user']['first_name'],
                'last_name'   => $get_res['user']['last_name'],
                'email'       => $get_res['user']['email'],
                'phone'       => $get_res['user']['phone'],
                'address'     => $get_res['user']['address'],
                'city'        => $get_res['user']['city'],
                'state'       => $get_res['user']['state'],
                'zip'         => $get_res['user']['zip'],
                'password'    => $get_res['user']['password'],
                'work_with_other_inspection_companies'     => $get_res['user']['work_with_other_inspection_companies'],
                'interested_in_doing_mobile_mechanic_work' => $get_res['user']['interested_in_doing_mobile_mechanic_work'],
            ]);

        }

        return response()->json([
            'status' => true,
            'message' => 'user register successfully',
            'token' => $user->createToken('API TOKEN', ['*'], now()->addHours(6))->plainTextToken
        ], 200);
        
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully'
        ], 200);
    }


    public function customer_profile(){
        try {
            $customers = Customer::where('user_type', 'customer')->get();

            // Check if orders list is empty
            if ($customers->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No customer found'
                ], 404);
            }

            // Return the customers as a JSON response
            return response()->json([
                'status' => true,
                'customers' => $customers
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function inspector_profile(){
        try {
            $customers = Customer::where('user_type', 'inspector')->get();

            // Check if orders list is empty
            if ($customers->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No inspector found'
                ], 404);
            }

            // Return the customers as a JSON response
            return response()->json([
                'status' => true,
                'customers' => $customers
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function get_order() {
        try {
            // Fetch all orders
            $orders = Order::all();

            // Check if orders list is empty
            if ($orders->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No orders found'
                ], 404);
            }

            // Return the orders if they exist
            return response()->json([
                'status' => true,
                'orders' => $orders
            ], 200);

        } catch (\Throwable $th) {
            // Handle any exceptions
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    

    

   

  
}
