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

    <?php
    use App\Models\Company;

    $user = auth()->user();

    // Null check for companyUser and CompanyID
    $companyUser = $user->companyUser ?? null;
    $companyId = $companyUser ? $companyUser->CompanyID : null;
    $company = $companyId ? Company::find($companyId) : null;

    $buttonColor = $company ? $company->button_color : '#007bff'; // Default to Bootstrap primary color
    $sidebarColor = $company ? $company->sidebar_color : '#6c757d'; // Default to Bootstrap secondary color
    $company_logo = $company ? $company->company_logo : 'path/to/default/logo.png'; // Default logo path
    ?>

    <style>
        :root,[data-bs-theme=light] {
            --bs-primary: {{ $buttonColor }};
            --bs-secondary: {{ $sidebarColor }};
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
        }

        .carousel-item h5 {
            text-align: center;
        }

        .modal-title {
            width: 100%;
            text-align: center;
        }
    </style>
</head>

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

    <!-- Tutorials Modal -->
    <div class="modal fade" id="tutorialsModal" tabindex="-1" aria-labelledby="tutorialsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tutorialsModalLabel">Tutorials</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="tutorial-carousel" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <h5>Homepage Dashboard</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/6CEbLxMVDVs?enablejsapi=1" title="Homepage Dashboard" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="carousel-item">
                                <h5>Profile Page</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/h8MZeYZ2APE?enablejsapi=1" title="Profile Page" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="carousel-item">
                                <h5>Onboarding Modules and Quiz</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/8ahzfOtjigw?enablejsapi=1" title="Onboarding Modules and Quiz" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="carousel-item">
                                <h5>Discussion Forum</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/oPUE7opVecg?enablejsapi=1" title="Discussion Forum" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="carousel-item">
                                <h5>View Colleague Information</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/ffBfehf6WTw?enablejsapi=1" title="View Colleague Information" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="carousel-item">
                                <h5>Login Streak Gamification</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/xONkjleN64s?enablejsapi=1" title="Login Streak Gamification" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#tutorial-carousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#tutorial-carousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Chatbot Script -->
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
    <!-- AI Chatbot Script End -->

    <script type="text/javascript" src="{{ asset('js/sidebarmenu.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/app.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('lib/simplebar/dist/simplebar.js') }}"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
        let players = [];

        function onYouTubeIframeAPIReady() {
            const iframes = document.querySelectorAll('.carousel-video');
            iframes.forEach((iframe, index) => {
                players[index] = new YT.Player(iframe, {
                    events: {
                        'onReady': onPlayerReady,
                    }
                });
            });
        }

        function onPlayerReady(event) {
            const player = event.target;
            document.addEventListener('keydown', function(event) {
                if (event.code === 'Space') {
                    event.preventDefault();
                    if (player.getPlayerState() === YT.PlayerState.PLAYING) {
                        player.pauseVideo();
                    } else {
                        player.playVideo();
                    }
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            const carousel = document.getElementById('tutorial-carousel');
            if (event.code === 'ArrowLeft') {
                event.preventDefault();
                carousel.querySelector('.carousel-control-prev').click();
            } else if (event.code === 'ArrowRight') {
                event.preventDefault();
                carousel.querySelector('.carousel-control-next').click();
            }
        });
    </script>
</body>

</html>
