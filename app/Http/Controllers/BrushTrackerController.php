<?php

namespace App\Http\Controllers;

use App\Child;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\YearTrait;
use App\Http\Controllers\Traits\BrushtrackerTrait;
use App\Http\Requests\UpdateBrushtrackerRequest;

class BrushTrackerController extends Controller
{
    use YearTrait;
    use BrushtrackerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $brush = $this->updateChildBrushtracker($request);
        return response()->json(['success' => true, 'msg' => 'Saved Successfully', 'brush' => $brush], 200); 
        // if($is_saved) {
        //     return response()->json(['success' => true, 'msg' => 'Saved Successfully', 'year' => $this->getActiveBrushtrackerYear($request->brush_date)], 200);
        // }
        // return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrushtrackerRequest $request, $id)
    {
        $year = $this->getActiveYear();
        $is_updated = $this->updateBrushtracker($request, $id, $year);
        return response()->json([
            'registration' => $is_updated
        ], 200);
        if ($is_updated) {
            return response()->json(['success' => true, 'msg' => 'Saved Successfully'], 200);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getList()
    {
        $year = $this->getActiveYear();
        $children = $this->getBrushtrackerChildrenList($year);
        foreach ($children as $child) {
            $is_active = $this->isChildTrackingActive($child->id, $year);
            $child->is_active = $is_active;
            $child = $this->getListBrushtracker($year, $child, $is_active);
        }
        return response()->json(['children' => $children], 200);
    }

    public function getComplete()
    {
        $year = $this->getActiveYear();
        $children = $this->getChildrenWithCompleteBrushes($year);
        return response()->json(['children' => $children], 200);
    }

    public function getDays()
    {
        $req = new \stdClass();
        $req->today = date('w', strtotime("-3 hours"));
        $req->yesterday = date('w', strtotime("-27 hours"));
        return response()->json([
            'days' => $req
        ], 200);
    }

    public function sendCert(Request $request) 
    {
        $parent_user = $this->sendCertificate($request);
        return response()->json(['success' => true, 'parent' => $parent_user], 200); 
    }

    public function sendCertAll(Request $request)
    {
        $this->sendCertificateAll($request);
        return response()->json(['success' => true, 'year' => $request->year], 200); 
    }

    public function resetDays($child_id)
    {
        $this->resetBrushtrackerDays($child_id);
        $year = $this->getActiveYear();
        $is_active = $this->isChildTrackingActive($child_id, $year);
        $child = Child::find($child_id);
        $child->is_active = $is_active;
        $child = $this->getListBrushtracker($year, $child, $is_active);
        
        return response()->json([
            'success' => true,
            'msg' => 'Days are reset',
            'child' => $child
        ]);
    }
}
