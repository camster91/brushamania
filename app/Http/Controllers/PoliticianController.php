<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Traits\PoliticianTrait;
use App\Http\Requests\StorePoliticianRequest;
use App\Http\Requests\UpdatePoliticianRequest;
use Illuminate\Support\Facades\Validator;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;
use Excel;
use App\Exports\PoliticiansExport;
use App\Imports\PoliticiansImport;

class PoliticianController extends Controller
{
    use PoliticianTrait;

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
        return view('registration.politician', [
            'page_title' => 'Politician Registration' 
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePoliticianRequest $request)
    {
        // if(config('app.env') == 'staging') {
        //     $rules = [
        //         'gRecaptchaResponse' => [new GoogleReCaptchaV3ValidationRule('politician_registration')]
        //     ];
        //     $input = [
        //         'gRecaptchaResponse' => $request->gRecaptchaResponse
        //     ];
        //     if (!Validator::make($input, $rules)->passes()) {
        //         return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400);
        //     }
        // }
        $politician_id = $this->checkPolitician($request->firstname, $request->lastname);
        if ($politician_id >= 1) {
            return response()->json(['success' => false, 'msg' => 'Politician Already Registered'], 400); 
        } else {
            $politician = $this->addPolitician($request);
        }
        if (!empty($politician)) {
            return response()->json(['success' => true, 'msg' => 'Rotarian Registered Successfully'], 200);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong'], 400); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($politician)
    {
        $politician = $this->showPolitician($politician);
        return response()->json(['politician' => $politician], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($politician)
    {
        $politician = $this->editPolitician($politician);
        return response()->json(['politician' => $politician], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePoliticianRequest $request, $id)
    {
        $politician = $this->updatePolitician($request, $id);
        if (!empty($politician)) {
            return response()->json(['success' => true, 'msg' => 'Politician Updated Successfully', 'url_slug' => $politician->url_slug], 200);
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
        $this->deletePolitician($id);
        return response()->json(['success' => true, 'msg' => 'Politician Deleted Successfully']);
    }

    public function getList()
    {
        $politicians = $this->getPoliticiansList();
        return response()->json(['politicians' => $politicians], 200);
    }

    public function export() 
    {
        return Excel::download(new PoliticiansExport(), 'politicians.xlsx');
        // return response()->json(['parents' => $this->exportParents($year)], 200);
    }

    public function import(Request $request) 
    {
        Excel::import(new PoliticiansImport, $request->file('file'));
        return response()->json(['success' => true, 'msg' => 'Politician Are Being Imported','file' => $request->hasFile('file')], 200);
    }
}
