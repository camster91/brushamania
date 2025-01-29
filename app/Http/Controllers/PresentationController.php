<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Traits\YearTrait;
use App\Http\Controllers\Traits\PresentationTrait;
use App\Http\Requests\UpdatePresentationRequest;

class PresentationController extends Controller
{
    use YearTrait;
    use PresentationTrait;

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($school, $year = null)
    {
        if(!isset($year) || $year == 'undefined') {
            $year = $this->getActiveYear();
        }
        $presentation = $this->showPresentation($school, $year);
        return response()->json(['presentation' => $presentation], 200);
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
    public function update(UpdatePresentationRequest $request, $id)
    {
        if($this->updatePresentation($request, $id)) {
            return response()->json(['success' => true, 'msg' => 'School Presentation Updated Successfully'], 200);
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
        $presentations = $this->getYearPresentationList($year);
        return response()->json(['presentations' => $presentations], 200);
    }

    public function getWithNoAssigned($assigned)
    {
        $year = $this->getActiveYear();
        if ($assigned == 'no-dentist') {
            return response()->json(['schools' => $this->getPresentationWithNoAssigned('dentist_id', $year)], 200);
        }
        return response()->json(['schools' => $this->getPresentationWithNoAssigned('rotarian_id',$year)], 200);
        
        
    }

    public function getWithNoRotarian()
    {
        $year = $this->getActiveYear();
        return response()->json(['schools' => $this->getPresentationWithNoAssigned('rotarian_id',$year)], 200);
    }
}
