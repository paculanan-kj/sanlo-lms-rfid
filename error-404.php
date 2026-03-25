<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Page Not Found - 404 Error</title>
    <meta content="The page you are looking for does not exist" name="description">
    <meta content="404, error, not found" name="keywords">


    <!-- Favicons -->
    <link href="assets/logo/ndk-logo.png" rel="icon">
    <link href="assets/logo/ndk-logo.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4154f1;
            --secondary-color: #717ff5;
            --dark-color: #012970;
            --light-color: #f6f9ff;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
        }

        .error-404 {
            position: relative;
            overflow: hidden;
        }

        .error-404 h1 {
            font-size: 8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0;
            line-height: 1;
            text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .error-404 h2 {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: var(--dark-color);
        }

        .error-404 .error-img {
            max-width: 400px;
            margin: 1.5rem 0;
        }

        .error-404 .back-btn {
            background-color: var(--primary-color);
            border: none;
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(65, 84, 241, 0.3);
        }

        .error-404 .back-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(65, 84, 241, 0.4);
        }

        .error-404 .back-home {
            color: var(--primary-color);
            font-weight: 600;
            margin-top: 1rem;
            display: inline-block;
            transition: all 0.3s;
        }

        .error-404 .back-home:hover {
            color: var(--secondary-color);
        }

        .error-404 .back-home i {
            margin-right: 5px;
        }

        .floating {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        @media (max-width: 768px) {
            .error-404 h1 {
                font-size: 6rem;
            }

            .error-404 .error-img {
                max-width: 300px;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="container">
            <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                <div id="particles-js" class="particles"></div>

                <h1 class="floating">404</h1>
                <h2>Oops! The page you are looking for doesn't exist.</h2>

                <img src="assets/img/not-found.svg" class="error-img floating" alt="Page Not Found">

                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-primary back-btn">
                        <i class="bi bi-house-door"></i> Return to Homepage
                    </a>
                </div>

                <div class="text-center mt-5">
                    <p class="text-muted">If you believe this is an error, please contact the developer.</p>
                </div>
            </section>
        </div>
    </main>

    <!-- Core JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Particles JS -->
    <script src="assets/js/2.0.0/particles.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            particlesJS("particles-js", {
                particles: {
                    number: {
                        value: 40,
                        density: {
                            enable: true,
                            value_area: 800
                        }
                    },
                    color: {
                        value: "#4154f1"
                    },
                    shape: {
                        type: "circle",
                        stroke: {
                            width: 0,
                            color: "#000000"
                        }
                    },
                    opacity: {
                        value: 0.3,
                        random: true,
                        anim: {
                            enable: false
                        }
                    },
                    size: {
                        value: 5,
                        random: true,
                        anim: {
                            enable: false
                        }
                    },
                    line_linked: {
                        enable: true,
                        distance: 150,
                        color: "#4154f1",
                        opacity: 0.2,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 2,
                        direction: "none",
                        random: true,
                        straight: false,
                        out_mode: "out",
                        bounce: false
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: {
                            enable: true,
                            mode: "grab"
                        },
                        onclick: {
                            enable: true,
                            mode: "push"
                        },
                        resize: true
                    },
                    modes: {
                        grab: {
                            distance: 140,
                            line_linked: {
                                opacity: 0.5
                            }
                        },
                        push: {
                            particles_nb: 3
                        }
                    }
                },
                retina_detect: true
            });
        });
    </script>
</body>

</html>