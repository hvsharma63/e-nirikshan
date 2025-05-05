@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ config('app.url') }}/images/logo.svg" alt="{{ config('app.name') }}" style="width: 100px; height: auto;">
</div>

# New Deficiency Reported

Hello {{ $notifiable->name }},

A new deficiency has been reported that requires your attention. Please review the details below:

@component('mail::panel')
## Deficiency Details

**Location:** {{ $deficiency->inspection->location }}  
**Reported On:** {{ $deficiency->created_at->format('d M Y, H:i') }}  
**Description:** {{ $deficiency->note }}
@endcomponent

@component('mail::button', ['url' => url('/deficiencies/' . $deficiency->id), 'color' => 'primary'])
Review Deficiency
@endcomponent

> If you need any additional information, please contact the Inspecting Officer.

Thanks,<br>
{{ config('app.name') }}

@endcomponent
