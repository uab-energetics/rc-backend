@component('mail::message')
# You've been invited to collaborate on a project!

{{$user}} has invited to you be a researcher on project: {{$project}}

follow the instructions in the invite link below to get started.

@component('mail::button', ['url' => $callback])
Redeem Invite
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
