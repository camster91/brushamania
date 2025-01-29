<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Traits\YearTrait;
use App\Http\Controllers\Traits\ChildTrait;
use App\Http\Controllers\Traits\BrushtrackerTrait;
use App\Http\Controllers\Traits\ParentTrait;
use App\Http\Requests\UpdateChildRequest;
use Excel;
use Auth;
use App\ChildParent;
use App\Exports\ChildrenExport;

class ChildrenController extends Controller
{
    use YearTrait;
    use ChildTrait;
    use BrushtrackerTrait;
    use ParentTrait;

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
        $year = $this->getActiveYear();
        if (isset($request->selected_year)) {
            $year = $request->selected_year;
        }
        $user_id = Auth::user()->id;
        $parent_id = ChildParent::where('user_id', $user_id)->first()->id;
        if(isset($request->id)) {
            $parent_id = $request->id;
        }
        if ($this->addParentRegistration($parent_id, $year)) {
            foreach ($request->children as $child) {
                if (!$this->checkChild($child['firstname'], $child['lastname'], $parent_id)) {
                    $child1 = $this->addChild($child['firstname'], $child['lastname'], $parent_id, $year);
                } else {
                    $child_id = $this->getChild($child['firstname'], $child['lastname'], $parent_id);
                    $child1 = $this->updateChild($child['firstname'], $child['lastname'], $child_id, $year);
                }
                $this->addChildRegistration($child1->id, $year);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($child)
    {
        $child = $this->showChild($child);
        return response()->json(['child' => $child], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($child)
    {
        $child = $this->editChild($child);
        return response()->json(['child' => $child], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChildRequest $request, $id)
    {
        $child = $this->updateChild($request->firstname, $request->lastname, $id);
        if (!empty($child)) {
            return response()->json(['success' => true, 'msg' => 'Child Updated Successfully', 'url_slug' => $child->url_slug], 200);
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
        if($this->deleteChild($id)) {
            $this->deleteChildBrushtracker($id);
            return response()->json(['success' => true, 'msg' => 'Child Deleted Successfully']);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
    }

    public function getList($year = null)
    {
        if(!isset($year) || $year == 'undefined') {
            $year = $this->getActiveYear();
        }
        $children = $this->getChildrenList($year);
        return response()->json(['children' => $children], 200);
    }

    public function getChildrenOfParents()
    {
        $children = $this->getChildrenOfParentList();
        return response()->json(['children' => $children], 200);
    }

    public function export($year) {
        return Excel::download(new ChildrenExport($year), 'children.xlsx');
        // return response()->json(['children' => $this->exportChildren($year)], 200);
    }

}
