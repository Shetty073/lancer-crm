<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\FollowUp;
use Illuminate\Http\Request;

class FollowupsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent());

        $enquiry = Enquiry::findorfail($data->enquiry_id);
        $follow_up = FollowUp::create([
            'date_time' => $data->date_time,
            'remark' => $data->remark,
            'outcome' => $data->outcome,
        ]);

        $follow_up->enquiry()->associate($enquiry);
        $follow_up->save();

        return back();
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
        $data = json_decode($request->getContent());

        $follow_up = FollowUp::findorfail($id);
        $follow_up->update([
            'date_time' => $data->date_time,
            'remark' => $data->remark,
            'outcome' => $data->outcome,
        ]);
        $follow_up->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $follow_up = FollowUp::findorfail($id);
        $follow_up->delete();

        return back();
    }
}
