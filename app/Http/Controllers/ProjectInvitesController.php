<?php

namespace App\Http\Controllers;

use App\Mail\InvitedToProject;
use App\Project;
use App\ProjectInviteToken;
use App\Services\Projects\ProjectService;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Mail;

class ProjectInvitesController extends Controller
{

    function redeemInviteToken(Request $request, ProjectService $projectService){
        $token = $request->get('token');

        $invite = ProjectInviteToken::getToken($token);
        if(!$invite) abort(401);

        $project = Project::find($invite->project_id);
        $projectService->addResearcher($project->getKey(), Auth::user()->getKey());

        return response()->json([
            'project' => $project,
            'user' => User::find($invite->creator_id)
        ]);
    }

    function sendInviteToken(Request $request){
        $request->validate([
            'project_id' => 'required|int|exists:projects,id',
            'to_email' => 'required|email',
            'callback_url' => 'required|url'
        ]);

        $invitee = Auth::user();
        $project = Project::find($request->input('project_id'));



        // Generating the invitation
        $token = ProjectInviteToken::generateInviteToken(
            $invitee->getKey(),
            $project->getKey()
        );
        // Generating the invitation



        // Sending the email
        $target_email = request()->get('to_email');
        $invite_email = new InvitedToProject([
            'project' => $project->name,
            'user' => $invitee->name,
            'callback' => $request->input('callback_url')
        ]);
        Mail::to($target_email)->send($invite_email);
        // Sending the email



        return response()->json([
            'msg' => "Invite Sent!",
            'token' => $token
        ]);
    }

}
