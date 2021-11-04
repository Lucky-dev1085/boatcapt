<?php

namespace App\Http\Controllers\Captain;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BidRequest;
use App\Bid;
use App\Trip;
use App\Presenters\BidPresenter;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use App\Notifications\CreateBid;
use App;

class BidController extends Controller
{
    //
    private $limit = 10;
    
    public function __construct(BidRequest $bidRequest, Trip $trip, Bid $bid, BidPresenter $bidPresenter)
	{
        $this->bidRequest = $bidRequest;
        $this->bid = $bid;
        $this->trip = $trip;
        $this->bidPresenter = $bidPresenter;
	}

	public function index($tripId) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => false,
            'login'         => true
        ];      

        $param = json_encode($param);

        $bidRequest = $this->bidPresenter->bidRequestCollection(
                            $this->bidRequest->with(['trip', 'trip.profile', 'trip.ownerInfo'])
                                    ->whereHas('trip', function($q) use ($tripId) {
                                        $q->where('tripId',  $tripId);
                                    })
                                    ->get()
                        );
        
        
        if(count($bidRequest) == 0)
            return redirect('/');
        
        $bidRequest = json_encode($bidRequest[0]); 
        return view('pages.captain.create-bid', compact('param', 'bidRequest'));
        
    }

    public function bidDetail($bidId) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => false,
            'login'         => true
        ];      

        $param = json_encode($param);

        $bid = $this->bidPresenter->bidCaptainCollection(
                    $this->bid->where('id',  $bidId)->get()
                );
        
        
        if(count($bid) == 0)
            return redirect('/');
        
        $tripInfo = json_encode($bid[0]);

        $readOnly = 0;

        return view('pages.captain.trip-detail', compact('param', 'tripInfo', 'readOnly'));
        
    }

    public function bidList(Request $request) 
    {
        $bidCount = $this->bid->where('user_id',  Auth::user()->id)                             
                            ->count();

        if($bidCount == 0)
        {
            return response()->json([
                'bidCount'    => 0,
                'bidList'      => []
            ]);
        }

        $bids = $this->bid->where('user_id',  Auth::user()->id)
                            ->orderBy('created_at', 'desc');

        $bidList = $this->bidPresenter->bidCaptainCollection(
            $bids->get()
        );

        return response()->json([
            'bidCount'      => $bidCount,
            'bidList'       => $bidList
        ]);
    }

    public function bidRequestList(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $bidRequestCount = $this->bidRequest->where('user_id',  Auth::user()->id)                          
                                ->count();

        if($bidRequestCount == 0)
        {
            return response()->json([
                'bidRequestCount'    => 0,
                'bidRequestList'     => []
            ]);
        }

        $bidRequests = $this->bidRequest->where('user_id',  Auth::user()->id)
                            ->orderBy('created_at', 'desc')
                            ->offset($offset)
                            ->limit($limit);

        $bidRequestList = $this->bidPresenter->bidRequestCollection(
            $bidRequests->get()
        );

        return response()->json([
            'bidRequestCount'      => $bidRequestCount,
            'bidRequestList'       => $bidRequestList
        ]);
    }

    public function createBid(Request $request) 
    {
    	$validator = Validator::make($request->all(), [
            'tripId' => 'required',
            'chargeType' => 'required|integer',
            'hours' => 'required|integer|min: 1',
            'amount' => 'required|numeric|min: 0.01'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $tripId = $request->tripId;
        $tripInfo = $this->trip->select('id')->where('tripId', $tripId)->first();

        if(is_null($tripInfo))
        {
            return redirect('/');
        }

        $bidRequest = $this->bidRequest->where('user_id', Auth::user()->id)
                             ->where('trip_id',  $tripInfo->id)->first();

        if(is_null($bidRequest))
        {
            return redirect('/');
        }

        $bidData = [
        	'trip_id'			=> $tripInfo->id,
            'user_id'           => Auth::user()->id,
            'chargeType'       	=> $request->chargeType,
            'hours'    			=> $request->hours,
            'amount'    		=> $request->amount,
            'describe'          => $request->describe
        ];

        try {
            DB::beginTransaction();

            $bid = $this->bid->create($bidData);            

            $bidRequest->delete();

            if ( App::environment() === 'local' )
                Auth::user()->notify(new CreateBid($bid));  

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem create bid.'])
                            ->withInput();
        }         

        return redirect('/bid-submitted');        
    }

    public function bidSubmitted() 
    {
    	$param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => false,
            'login'         => true
        ];      

        $param = json_encode($param);
    	return view('pages.captain.bid-submitted', compact('param'));      
    }

	public function bidRequest($tripId) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => false,
            'login'         => true
        ];      

        $param = json_encode($param);

        $bidRequest = $this->bidPresenter->bidRequestCollection(
                            $this->bidRequest->with(['trip', 'trip.profile', 'trip.ownerInfo'])
                            		->whereHas('trip', function($q) use ($tripId) {
                                        $q->where('tripId',  $tripId);
                                    })                                    
                                    ->get()
                        );
        
        
        if(count($bidRequest) == 0)
            return redirect('/');
        
        $bidRequest = json_encode($bidRequest[0]); 
        return view('pages.captain.bid-request-detail', compact('param', 'bidRequest'));
        
    }    

    public function passTrip($tripId) 
    {
    	$bidRequests = $this->bidRequest->where('user_id', Auth::user()->id)
                        ->whereHas('trip', function($q) use ($tripId) {
                            $q->where('tripId',  $tripId);
                        })->get();

        foreach ($bidRequests as $key => $bidRequest) {
            $bidRequest->delete();
        }
            
        return redirect('/dashboard');        
    }
    
}
