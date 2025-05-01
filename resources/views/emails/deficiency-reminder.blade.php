@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ config('app.url') }}/images/logo.svg" alt="{{ config('app.name') }}" style="width: 100px; height: auto;">
</div>

# Reminder: Attend to Reported Deficiencies

Hello {{ $notifiable->name }},

This is a reminder to attend to the reported deficiencies that require your attention. Please review the details below:

@component('mail::panel')
## Deficiency Details

**Location:** {{ $deficiency->inspection->location }}  
**Reported On:** {{ $deficiency->created_at->format('d M Y, H:i') }}  
**Description:** {{ $deficiency->note }}
@endcomponent

@component('mail::button', ['url' => route('officers.deficiencies.view', $deficiency->id), 'color' => 'primary'])
Attend to Deficiency
@endcomponent

> Please ensure that the necessary actions are taken promptly. If you need any additional information, contact the Inspecting Officer.

Thanks,<br>
{{ config('app.name') }}

@endcomponent
