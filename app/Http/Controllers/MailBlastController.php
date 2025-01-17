<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Traits\MailBlastTrait;
use App\MailBlast;

class MailBlastController extends Controller
{
    use MailBlastTrait;

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
        $mailblast = $this->addMailBlast($request);
        if(!empty($mailblast)) {
            return response()->json(['success' => true, 'msg' => 'Mail Template Added Successfully', 'url_slug' => $mailblast->url_slug, 'id' => $mailblast->id], 200);
        }
        return response()->json(['success' => true, 'msg' => 'Mail Template Not Saved'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mailblast = $this->showMailBlast($id);
        return response()->json(['mail' => $mailblast], 200);
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
        $mailblast = $this->updateMailBlast($request, $id);
        if (!empty($mailblast)) {
            return response()->json(['success' => true, 'msg' => 'Mail Template Updated Successfully', 'url_slug' => $mailblast->url_slug, 'id' => $mailblast->id], 200);
        }
        return response()->json(['success' => true, 'msg' => 'Mail Template Not Saved'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->deleteMailBlast($id);
        return response()->json(['success' => true, 'msg' => 'Mail Template Deleted Successfully']);
    }

    public function saveRecipient(Request $request)
    {
        if($this->saveMailBlastRecipients($request)) {
            return response()->json(['success' => true, 'msg' => 'Mailblast Updated Successfully']);
        }
    }

    public function sendMail(Request $request)
    {
        if ($this->sendMailBlast($request)) {
            return response()->json(['success' => true, 'msg' => 'Mail Sent Successfully']);
        }
    }

    public function getList()
    {
        $mailblasts = $this->getMailBlastList();
        return response()->json(['mailblasts' => $mailblasts], 200);
    }
}
