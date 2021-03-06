<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>eRenovate | The eRenovate Guarantee</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">

</head>
<body>

<audio id="ChatAudio">
    <source src="{{ asset('sounds/chat.mp3') }}">
</audio>

<div id="app">
    <app></app>
</div>
<script>
  window.USER = @php echo json_encode(Auth::user()? Auth::user()->only(['userId', 'role', 'firstname', 'lastname', 'photo', 'profile_link']) : '') @endphp
</script>

@if(Auth::user())
    <script>
      window.intercomSettings = {
        app_id: "zmvmt1x8",
        name: "@php echo Auth::user()['firstname'] ?? null . ' ' . Auth::user()['lastname'] ?? null  @endphp",
        email: "@php echo Auth::user()['email'] @endphp",
        created_at: "@php echo time() @endphp"
      };
    </script>
@endif
{{--<script>(function () {--}}
{{--var w = window;--}}
{{--var ic = w.Intercom;--}}
{{--if (typeof ic === "function") {--}}
{{--ic('reattach_activator');--}}
{{--ic('update', w.intercomSettings);--}}
{{--} else {--}}
{{--var d = document;--}}
{{--var i = function () {--}}
{{--i.c(arguments);--}}
{{--};--}}
{{--i.q = [];--}}
{{--i.c = function (args) {--}}
{{--i.q.push(args);--}}
{{--};--}}
{{--w.Intercom = i;--}}
{{--var l = function () {--}}
{{--var s = d.createElement('script');--}}
{{--s.type = 'text/javascript';--}}
{{--s.async = true;--}}
{{--s.src = 'https://widget.intercom.io/widget/zmvmt1x8';--}}
{{--var x = d.getElementsByTagName('script')[0];--}}
{{--x.parentNode.insertBefore(s, x);--}}
{{--};--}}
{{--if (w.attachEvent) {--}}
{{--w.attachEvent('onload', l);--}}
{{--} else {--}}
{{--w.addEventListener('load', l, false);--}}
{{--}--}}
{{--}--}}
{{--})();--}}
{{--// </script>--}}

<script src="{{ asset('js/functions.js') }}"></script>

<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/main.js') }}"></script>

</body>
</html>
