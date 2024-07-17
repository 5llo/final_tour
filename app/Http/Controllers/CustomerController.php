<?php

namespace App\Http\Controllers;
use App\Http\Resources\DesignerResource;
use App\Http\Resources\TourResource;
use App\Models\Customer_tour;
use App\Models\Tour;
use App\Traits\ImageTrait;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\TourService;
class CustomerController extends Controller
{
    protected $tourService;

    public function __construct(TourService $tourService)
    {
        $this->tourService = $tourService;
    }
    use GeneralTrait,ImageTrait;
    public function AllTour(Request $request)  {
try{
      $allTour=Tour::where('status','active')->get();
    $data =  TourResource::collection($allTour);
      return $this->successResponse($data, 'Tour has been updated successfully.');
  } catch (\Exception $ex) {
      return $this->errorResponse($ex->getMessage(), 500);
  }
    }


    public function joinTour(Request $request){

        $validator = Validator::make($request->all(), [
            'tour_id'=>'integer|exists:tours,id|unique:customer_tour,tour_id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'proccess_number' => 'string|max:25|unique:customer_tour,paid_url',
        ]);


        try{$user=Auth::id();
            $exists = Customer_tour::where('tour_id', $request->tour_id)
                                    ->where('customer_id', $user)
                                    ->exists();
           if(!$exists){
            if(!empty($request['proccess_number'])){
             Auth::user()->tours()->attach($request['tour_id'],[
                'paid_url'=>$request['proccess_number']
             ]);


            }
            else{
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
             $customer=  Auth::user()->tours()->attach($request['tour_id'],[
                    'paid_url'=>'/images/Invoices' . '/' . $image_name
                 ]);

                 $image->move(public_path('images/Invoices'), $image_name);





            }
            $data=Auth::user()->tours;
            return $this->successResponse($data,'Tour has been successfully.');}
            else
            return $this->errorResponse('already exists',403);





      //      return $this->successResponse($data, 'Your request is being processed.');
        }

        catch (\Exception $ex) {

            return $this->errorResponse($ex->getMessage(), 500);

        }




    }


}
