<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Trip;
use App\BidRequest;
use App\Presenters\CaptainPresenter;
use App\Presenters\TripPresenter;
use App\Presenters\BidPresenter;
use Auth;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Notifications\NewBidRequest;
use App;

class BookCaptainController extends Controller
{
    //
    public function __construct(User $user, Trip $trip, BidRequest $bidRequest, CaptainPresenter $captainPresenter, BidPresenter $bidPresenter, TripPresenter $tripPresenter)
	{
		$this->user = $user;
        $this->trip = $trip;
        $this->bidRequest = $bidRequest;
		$this->captainPresenter = $captainPresenter;
        $this->tripPresenter = $tripPresenter;
        $this->bidPresenter = $bidPresenter;
	}

    public function index($captainId) 
    {        
        $param = json_encode([
            'avatar' => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable' => true, 
            'login' => true
        ]);

    	$captainInfo = $this->captainPresenter->captainBioCollection(
	                    $this->user->with(['profile', 'captainInfo', 'review'])->where('id', $captainId)->where('role', 1003)->get()
	                );
    	if(count($captainInfo) == 0)
    		return redirect('/');
    	
        $captainInfo = json_encode($captainInfo[0]);  

    	return view('pages.owner.book-captain', compact('param', 'captainInfo'));
        
    }

    public function bidRequestList(Request $request) 
    {
        $bidRequestCount = $this->bidRequest->whereHas('trip', function($q) {
                                $q->where('user_id',  Auth::user()->id);
                            })                       
                            ->count();

        if($bidRequestCount == 0)
        {
            return response()->json([
                'bidRequestCount'    => 0,
                'bidRequestList'     => []
            ]);
        }

        $bidRequests = $this->bidRequest->whereHas('trip', function($q) {
                                $q->where('user_id',  Auth::user()->id);
                            }) 
                            ->orderBy('created_at', 'desc');

        $bidRequestList = $this->bidPresenter->bidCaptRequestCollection(
            $bidRequests->get()
        );

        return response()->json([
            'bidRequestCount'    => $bidRequestCount,
            'bidRequestList'     => $bidRequestList
        ]);
    }

    public function bidRequestDetail($bidRequestId) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => true,
            'login'         => true
        ];      

        $param = json_encode($param);

        $tripInfo = $this->bidPresenter->bidCaptRequestCollection(
                            $this->bidRequest->where('id', $bidRequestId)->get()
                        );

        if(count($tripInfo) == 0)
            return redirect('/');
        
        $tripInfo = json_encode($tripInfo[0]); 

        return view('pages.owner.trip-detail', compact('param', 'tripInfo'));
        
    }

    public function bookCaptain(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'captainId'         => 'required|integer',
            'startLocation'     => 'required',
            'startTime'         => 'required|date',
            'endLocation'       => 'required',
            'endTime'           => 'required|date',
            'tripNature'        => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $lat = null;
        $lon = null;

        if((App::environment() === 'local')){
            //Formatted address
            $formattedAddr = str_replace(' ','+',$request->startLocation);            
            //Send request and receive json data by address

            try {                
                $geocodeFromAddr = @file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false&key='.env('GOOGLE_API_KEY'));
                $output = json_decode($geocodeFromAddr);

                $lat = isset($output->results[0]->geometry->location->lat) ? $output->results[0]->geometry->location->lat : $lat;
                $lon = isset($output->results[0]->geometry->location->lng) ? $output->results[0]->geometry->location->lng : $lon;
                
            } catch (Exception $e) {
            }

            if(!($lat && $lon))
            {
                return redirect()->back()
                        ->withErrors(['message' => 'The start location is not a valid location.'])
                        ->withInput();
            }
                        
        }

        if(strtotime($request->startTime) >= strtotime($request->endTime))
        {
            return redirect()->back()
                        ->withErrors(['message' => 'The start time may not be greater than end time.'])
                        ->withInput();
        }

        $tripId = strtoupper(str_random(6));
        $find = false;
        while (!$find) {
            $count = $this->trip->where('tripId', $tripId)->count();
            if($count > 0)
            {
                $tripId = strtoupper(str_random(6));
            }
            else
            {
                $find = true;
            }
        }

        try {
            DB::beginTransaction();

            $this->trip->create([
                        'tripId'            => $tripId,
                        'user_id'           => Auth::user()->id,
                        'startLocation'     => $request->startLocation,
                        'startTime'         => date('Y-m-d H:i:s', strtotime($request->startTime)),
                        'endLocation'       => $request->endLocation,
                        'endTime'           => date('Y-m-d H:i:s', strtotime($request->endTime)),
                        'tripNature'        => $request->tripNature,
                        'describe'          => $request->describe,
                    ]);
            

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem complete booking.'])
                            ->withInput();
        }        

        return redirect('/'.$request->captainId.'/'.$tripId.'/book-confirm');
    }

    public function bookConfirm($captainId, $tripId) 
    {
        $param = json_encode([
            'avatar' => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable' => true, 
            'login' => true
        ]);

        $captainInfo = $this->captainPresenter->captainBioCollection(
                        $this->user->with(['profile', 'captainInfo', 'review'])->where('id', $captainId)->where('role', 1003)->get()
                    );
        if(count($captainInfo) == 0)
            return redirect('/');
        
        $captainInfo = json_encode($captainInfo[0]); 

        $tripInfo = $this->tripPresenter->transformCollection(
                            $this->trip->where('tripId', $tripId)->get()
                        );

        if(count($tripInfo) == 0)
            return redirect('/');
        
        $tripInfo = json_encode($tripInfo[0]); 

        return view('pages.owner.book-captain-confirm', compact('param', 'captainInfo', 'tripInfo'));
    }

    public function requestCaptain(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'captainId'  => 'required|integer',
            'tripId'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/');
        }

        $tripInfo = $this->trip->where('tripId', $request->tripId)->first();

        if(is_null($tripInfo))
        {
            return redirect('/');
        }

        $trip_id = $tripInfo['id'];

        $bidRequests = $this->bidRequest->where('trip_id', $trip_id);

        $captList = [intval($request->captainId)];

        if(isset($request->sendOther))
        {
            $lat = 0;
            $lon = 0;

            if((App::environment() === 'local')){
                //Formatted address
                $formattedAddr = str_replace(' ','+',$tripInfo->startLocation);            
                //Send request and receive json data by address

                try {                
                    $geocodeFromAddr = @file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false&key='.env('GOOGLE_API_KEY'));
                    $output = json_decode($geocodeFromAddr);

                    $lat = isset($output->results[0]->geometry->location->lat) ? $output->results[0]->geometry->location->lat : $lat;
                    $lon = isset($output->results[0]->geometry->location->lng) ? $output->results[0]->geometry->location->lng : $lon;
                    
                } catch (Exception $e) {
                }
                            
            }

            if((App::environment() === 'local') && !($lat && $lon))
            {
                return redirect()->back()
                        ->withErrors(['message' => 'The start location is not a valid location.'])
                        ->withInput();
            }

            $captains = $this->user->select('users.id')
                                ->selectSub('(POW((69.1*(lon-'.$lon.')*COS('.$lat.'/57.3)), 2)+POW((69.1*(lat-'.$lat.')), 2))', 'away')
                                ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id')
                                ->where('role', '1003')
                                ->whereHas('profile', function($q) {
                                    $q->where('isActive', '1');
                                })
                                ->where('users.id', '<>', $request->captainId)
                                ->orderBy('away')
                                ->limit(5)
                                ->get();                

            $bidRequests->whereNested(function($q) use ($request, $captains, &$captList) {
                $q->orWhere('user_id', $request->captainId);
                foreach ($captains as $key => $captain) {                    
                    $q->orWhere('user_id', $captain->id);
                    $captList[] = $captain->id;
                }
            });
            
        }

        $bidRequestList = $bidRequests->get();

        if(count($bidRequestList) == count($captList))
        {
            return redirect('/'.$request->captainId.'/'.$request->tripId.'/send-request-captain');
        }

        foreach ($bidRequestList as $bidRequest) {
            $key = array_search($bidRequest->user_id, $captList); 

            if($captList[$key] == $bidRequest->user_id)
            {
                array_splice($captList, $key, 1);
            }
        }

        try {
            DB::beginTransaction();

            foreach ($captList as $captainId) {

                $bidRequest = $this->bidRequest->create([
                        'trip_id'     => $trip_id,
                        'user_id'     => $captainId
                    ]);
                if ( App::environment() === 'local' )
                    Auth::user()->notify(new NewBidRequest($bidRequest));   
            }            

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect('/');
        }    

        return redirect('/'.$request->captainId.'/'.$request->tripId.'/send-request-captain');
    }

    public function requestSend($captainId, $tripId) 
    {
        $param = json_encode([
            'avatar' => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable' => true, 
            'login' => true
        ]);

        $captainInfo = $this->captainPresenter->captainBioCollection(
                        $this->user->with(['profile', 'captainInfo', 'review'])->where('id', $captainId)->where('role', 1003)->get()
                    );
        if(count($captainInfo) == 0)
            return redirect('/');
        
        $captainInfo = json_encode($captainInfo[0]); 

        return view('pages.owner.request-captain', compact('param', 'captainInfo'));
    }

    public function anyRequest() 
    {        
        $param = json_encode([
            'avatar' => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable' => true, 
            'login' => true
        ]); 

        return view('pages.owner.request-any-captain', compact('param'));
        
    }

    public function bookAnyCaptain(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'startLocation'     => 'required',
            'startTime'         => 'required|date',
            'endLocation'       => 'required',
            'endTime'           => 'required|date',
            'tripNature'        => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $lat = null;
        $lon = null;

        if((App::environment() === 'local')){
            //Formatted address
            $formattedAddr = str_replace(' ','+',$request->startLocation);            
            //Send request and receive json data by address

            try {                
                $geocodeFromAddr = @file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false&key='.env('GOOGLE_API_KEY'));
                $output = json_decode($geocodeFromAddr);

                $lat = isset($output->results[0]->geometry->location->lat) ? $output->results[0]->geometry->location->lat : $lat;
                $lon = isset($output->results[0]->geometry->location->lng) ? $output->results[0]->geometry->location->lng : $lon;
                
            } catch (Exception $e) {
            }

            if(!($lat && $lon))
            {
                return redirect()->back()
                        ->withErrors(['message' => 'The start location is not a valid location.'])
                        ->withInput();
            }
                        
        }

        if(strtotime($request->startTime) >= strtotime($request->endTime))
        {
            return redirect()->back()
                        ->withErrors(['message' => 'The start time may not be greater than end time.'])
                        ->withInput();
        }

        $tripId = strtoupper(str_random(6));
        $find = false;
        while (!$find) {
            $count = $this->trip->where('tripId', $tripId)->count();
            if($count > 0)
            {
                $tripId = strtoupper(str_random(6));
            }
            else
            {
                $find = true;
            }
        }

        try {
            DB::beginTransaction();

            $this->trip->create([
                        'tripId'            => $tripId,
                        'user_id'           => Auth::user()->id,
                        'startLocation'     => $request->startLocation,
                        'startTime'         => date('Y-m-d H:i:s', strtotime($request->startTime)),
                        'endLocation'       => $request->endLocation,
                        'endTime'           => date('Y-m-d H:i:s', strtotime($request->endTime)),
                        'tripNature'        => $request->tripNature,
                        'describe'          => $request->describe,
                    ]);
            

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem complete booking.'])
                            ->withInput();
        }        

        return redirect('/'.$tripId.'/request-any-confirm');
    }

    public function bookAnyConfirm($tripId) 
    {
        $param = json_encode([
            'avatar' => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable' => true, 
            'login' => true
        ]);

        $tripInfo = $this->tripPresenter->transformCollection(
                            $this->trip->where('tripId', $tripId)->get()
                        );

        if(count($tripInfo) == 0)
            return redirect('/');
        
        $tripInfo = json_encode($tripInfo[0]); 

        return view('pages.owner.book-any-captain-confirm', compact('param', 'tripInfo'));
    }

    public function requestAnyCaptain(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'tripId'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/');
        }

        $tripInfo = $this->trip->where('tripId', $request->tripId)->first();

        if(is_null($tripInfo))
        {
            return redirect('/');
        }

        $trip_id = $tripInfo['id'];

        $lat = 0;
        $lon = 0;

        if((App::environment() === 'local')){
            //Formatted address
            $formattedAddr = str_replace(' ','+',$tripInfo->startLocation);            
            //Send request and receive json data by address

            try {                
                $geocodeFromAddr = @file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false&key='.env('GOOGLE_API_KEY'));
                $output = json_decode($geocodeFromAddr);

                $lat = isset($output->results[0]->geometry->location->lat) ? $output->results[0]->geometry->location->lat : $lat;
                $lon = isset($output->results[0]->geometry->location->lng) ? $output->results[0]->geometry->location->lng : $lon;
                
            } catch (Exception $e) {
            }
                        
        }

        if((App::environment() === 'local') && !($lat && $lon))
        {
            return redirect()->back()
                    ->withErrors(['message' => 'The start location is not a valid location.'])
                    ->withInput();
        }

        $captains = $this->user->select('users.id')
                            ->selectSub('(POW((69.1*(lon-'.$lon.')*COS('.$lat.'/57.3)), 2)+POW((69.1*(lat-'.$lat.')), 2))', 'away')
                            ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id')
                            ->where('role', '1003')
                            ->whereHas('profile', function($q) {
                                $q->where('isActive', '1');
                            })
                            ->where('users.id', '<>', $request->captainId)
                            ->orderBy('away')
                            ->limit(5)
                            ->get();            
                        
        $captList = [];

        $bidRequests = $this->bidRequest->where('trip_id', $trip_id);

        $bidRequests->whereNested(function($q) use ($captains, &$captList) {
            foreach ($captains as $key => $captain) {
                
                $q->orWhere('user_id', $captain->id);
                $captList[] = $captain->id;
            }
        });

        $bidRequestList = $bidRequests->get();

        if(count($bidRequestList) == count($captList))
        {
            return redirect('/'.$request->tripId.'/send-request-any-captain');
        }

        foreach ($bidRequestList as $bidRequest) {
            $key = array_search($bidRequest->user_id, $captList); 

            if($captList[$key] == $bidRequest->user_id)
            {
                array_splice($captList, $key, 1);
            }
        }

        try {
            DB::beginTransaction();

            foreach ($captList as $captainId) {

                $bidRequest = $this->bidRequest->create([
                        'trip_id'     => $trip_id,
                        'user_id'     => $captainId
                    ]);
                if ( App::environment() === 'local' )
                    Auth::user()->notify(new NewBidRequest($bidRequest));   
            }            

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect('/');
        }  

        return redirect('/'.$request->tripId.'/send-request-any-captain');
    }

    public function requestAnySend($tripId) 
    {
        $param = json_encode([
            'avatar' => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable' => true, 
            'login' => true
        ]);

        $tripInfo = $this->trip->where('tripId', $tripId)->first();

        if(is_null($tripInfo))
        {
            return redirect('/');
        }

        $trip_id = $tripInfo->id;

        $captainList = json_encode($this->bidPresenter->bidCaptRequestCollection(
            $this->bidRequest->where('trip_id', $trip_id)->get()
        ));

        return view('pages.owner.send-request-any', compact('param', 'captainList'));
    }
}
