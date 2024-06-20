<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'E-Onboarding')</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Dashboard Charts Script -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabler-icons/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/simplebar/dist/simplebar.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <?php
        use App\Models\Company;

        $user = auth()->user();
        $companyId = $user->companyUser->CompanyID;
        $company = Company::find($companyId);
        $buttonColor = $company->button_color;
        $sidebarColor = $company->sidebar_color;
        $company_logo = $company->company_logo;
    ?>

    <style>
        :root, [data-bs-theme=light] {
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
    </style>

</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        @auth
        @include('includes.sidebar-admin', ['company_logo' => $company_logo])

        <div class="body-wrapper">
            @include('includes.header')
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
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/JeK4sp4TPfE?enablejsapi=1" title="Homepage Dashboard" frameborder="0" allowfullscreen></iframe>
                                <div class="carousel-caption d-none d-md-block">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <h5>Profile Page</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/f49wJ6VdB0s?enablejsapi=1" title="Profile Page" frameborder="0" allowfullscreen></iframe>
                                <div class="carousel-caption d-none d-md-block">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <h5>Manage Accounts</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/62mRllHbGRc?enablejsapi=1" title="Manage Accounts" frameborder="0" allowfullscreen></iframe>
                                <div class="carousel-caption d-none d-md-block">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <h5>Onboarding Progress Tracking</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/rz5sBtCLPOk?enablejsapi=1" title="Onboarding Progress Tracking" frameborder="0" allowfullscreen></iframe>
                                <div class="carousel-caption d-none d-md-block">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <h5>Manage Onboarding Modules</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/cZSIRGKdUrU?enablejsapi=1" title="Manage Onboarding Modules" frameborder="0" allowfullscreen></iframe>
                                <div class="carousel-caption d-none d-md-block">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <h5>Discussion</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/PZBtiGkcptY?enablejsapi=1" title="Discussion" frameborder="0" allowfullscreen></iframe>
                                <div class="carousel-caption d-none d-md-block">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <h5>View Colleagues</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/CwfKEx4l4g8?enablejsapi=1" title="View Colleagues" frameborder="0" allowfullscreen></iframe>
                                <div class="carousel-caption d-none d-md-block">
                                </div>
                            </div>
                            <div class="carousel-item">
                                <h5>Login Streak Leaderboard Gamification</h5>
                                <iframe class="carousel-video" width="100%" height="400" src="https://www.youtube.com/embed/zBNPrFvqEWk?enablejsapi=1" title="Login Streak Leaderboard Gamification" frameborder="0" allowfullscreen></iframe>
                                <div class="carousel-caption d-none d-md-block">
                                </div>
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
            introMessage: 'Hey! I am your Onboarding Assistant. How can I help you today?<br><br>Type "help" to see the list of commands.',
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
