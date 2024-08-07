<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PP-FYP')</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Dashboard Charts Script -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabler-icons/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/simplebar/dist/simplebar.css') }}">
</head>

<?php
use App\Models\Company;

$user = auth()->user();

?>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        @auth
        @include('includes.sidebar-superadmin')

        <div class="body-wrapper">
            @include('includes.header')

            @yield('content')
        </div>
        @else
        @yield('content')
        @endauth

    </div>

    <!-- AI Chatbot Script -->
    <script>
        let primaryColor = "#6A1043";
        let company = "People Psyence";
        let companyWebsite = "https://www.peoplepsyence.com/";
        let userId = "{{ $user->id }}";
        let userName = "{{ $user->name }}";
        let bubbleAvatar = "{{ Storage::url("res/message-chatbot.png") }}";

        var botmanWidget = {
            frameEndpoint: '/botman/chat',
            title: 'Onboarding Assistant 💬',
            introMessage: 'Hey ' + userName + ', I am your Onboarding Assistant. How can I help you today?<br><br>Type "help" to see the list of commands.',
            aboutText: company,
            placeholderText: 'Send a message...',
            mainColor: primaryColor,
            headerTextColor: '#fff',
            bubbleBackground: primaryColor,
            bubbleAvatarUrl: bubbleAvatar,
            aboutLink: companyWebsite,
            userId: userId
        };
    </script>
    <!-- AI Chatbot Script End -->

    <script type="text/javascript" src="{{ asset('js/sidebarmenu.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/app.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/simplebar/dist/simplebar.js') }}"></script>
</body>

</html>