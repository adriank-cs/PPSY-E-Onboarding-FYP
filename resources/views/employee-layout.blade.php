<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'E-Onboarding')</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.4/imagesloaded.pkgd.min.js"></script>
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

//$companyId = $user->companyUser->CompanyID;

//$company = Company::find($companyId);

//For null check
$companyUser = $user->companyUser ?? null;
$companyId = $companyUser ? $companyUser->CompanyID : null;
$company = $companyId ? Company::find($companyId) : null;

$buttonColor = $company->button_color;
$sidebarColor = $company->sidebar_color;
$company_logo = $company->company_logo;

?>

<style>
    :root,[data-bs-theme = light]{
        --bs-primary:{{ $buttonColor }};
        --bs-secondary:{{ $sidebarColor }};
    }
</style>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        @auth
        @include('includes.sidebar-employee')


        <div class="body-wrapper">
            @include('includes.header', ['company_logo' => $company_logo])
            @include('includes.modal')

            @yield('content')
        </div>
        @else
        @yield('content')
        @endauth

    </div>

    <script>
        let primaryColor = "{{ $buttonColor }}";
        let company = "{{ $company->Name }}"
        let companyWebsite = "{{ $company->Website }}"
        let userId = "{{ $user->id }}"
        let bubbleAvatar = "{{ Storage::url("res/message-chatbot.png") }}"

        var botmanWidget = {
            frameEndpoint: '/botman/chat',
            title: 'Onboarding Assistant 💬',
            introMessage: 'Hey! I am your Onboarding Assistant. How can I help you today?',
            aboutText: company,
            placeholderText: 'Send a message...',
            mainColor: primaryColor,
            bubbleBackground: primaryColor,
            bubbleAvatarUrl: bubbleAvatar,
            aboutLink: companyWebsite,
            userId: userId
        };
    </script>

    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>


    <script type="text/javascript" src="{{ asset('js/sidebarmenu.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/app.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/simplebar/dist/simplebar.js') }}"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    
</body>

</html>