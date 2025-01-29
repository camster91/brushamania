<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Traits\AutoreplyEmailTrait;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class AutoreplyEmailController extends Controller
{

    use AutoreplyEmailTrait;
    
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
    public function show($id)
    {
        $autoreply = $this->showAutoreplyEmail($id);
        return response()->json(['autoreply' => $autoreply], 200);
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
    public function update(Request $request, $id)
    {
        $autoreply = $this->updateAutoreply($request, $id);
        if (!empty($autoreply)) {
            return response()->json(['success' => true, 'msg' => 'Autoreply Mail Template Updated Successfully'], 200);
        }
        return response()->json(['success' => true, 'msg' => 'Autoreply Mail Template Updated Successfully'], 400);
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
        $autoreply_emails = $this->getAutoreplyEmailList();
        return response()->json(['autoreply_emails' => $autoreply_emails], 200);
    }

    public function testMail(Request $request) {
        if(config('app.env') == 'local') {
            Mail::to($request->test_mail)->send(new TestMail($request->content));
        } else {
            Mail::to($request->test_mail)->queue(new TestMail($request->content));
        }
        return response()->json(['success' => true, 'msg' => 'Test Sent Successfully'], 200);
    }
}
