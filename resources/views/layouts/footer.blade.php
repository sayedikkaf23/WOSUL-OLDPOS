@php
$languages = (isset($logged_user_data['languages']))?$logged_user_data['languages']:[];
$selected_language = (isset($logged_user_data['selected_language']))?$logged_user_data['selected_language']:'en - English';
@endphp
<footer class="container-fluid p-3 {{ (isset($fixed_footer) && $fixed_footer) ? "fixed-bottom" : "" }}">
    <div class="d-flex justify-content-between">
        <span>&nbsp;© {{ date("Y") }}-{{ date("Y", strtotime("+1 year")) }} {{ config('app.company') }} &middot; <span class="text-muted">v4.7</span></span>

        <languageswitchercomponent :languages="{{ json_encode($languages) }}" :selected_language="{{ json_encode($selected_language) }}" on_footer="true" ></languageswitchercomponent>
        
    </div>
</footer>
