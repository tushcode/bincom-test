<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?= $data['title']; ?></title>
        <meta name="author" content="<?= CONFIG['APP']['name']; ?>" />
        <meta name="description" content="<?= $data['tag']['info']; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <!-- Libs CSS -->
        <link type="text/css" media="all" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
        <link type="text/css" media="all" href="//pkfrom.github.io/404/assets/css/404.min.css" rel="stylesheet" />

        <!-- Favicons -->
        <link rel="apple-touch-icon" sizes="144x144" href="//pkfrom.github.io/404/assets/img/favicons/favicon144x144.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="//pkfrom.github.io/404/assets/img/favicons/favicon114x114.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="//pkfrom.github.io/404/assets/img/favicons/favicon72x72.png" />
        <link rel="apple-touch-icon" href="//pkfrom.github.io/404/assets/img/favicons/favicon57x57.png" />
        <link rel="shortcut icon" href="//pkfrom.github.io/404/assets/img/favicons/favicon.png" />
        <!-- Google Fonts -->
        <link href="http://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700,800,900" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <!-- Load page -->
        <div class="animationload">
            <div class="loader"></div>
        </div>
        <!-- End load page -->

        <!-- Content Wrapper -->
        <div id="wrapper">
            <div class="container">
                <!-- Switcher -->
                <div class="switcher">
                    <input id="sw" type="checkbox" class="switcher-value" />
                    <label for="sw" class="sw_btn"></label>
                    <div class="bg"></div>
                    <div class="text">
                        Turn <span class="text-l">off</span><span class="text-d">on</span><br />
                        the light
                    </div>
                </div>
                <!-- End Switcher -->

                <!-- Dark version -->
                <div id="dark" class="row text-center">
                    <div class="info">
                        <img src="<?= URLROOT . '/uploads/error/404-error.svg'; ?>" width="650" alt="404 error" />
                    </div>
                </div>
                <!-- End Dark version -->

                <!-- Light version -->
                <div id="light" class="row text-center">
                    <!-- Info -->
                    <div class="info">
                        <img src="//pkfrom.github.io/404/assets/img/404-light.gif" alt="404 error" />
                        <!-- end Rabbit -->
                        <p>
                            The page you are looking for was moved, removed,<br />
                            renamed or might never existed.
                        </p>
                        <a href="<?= URLROOT; ?>" class="btn">Go Home</a>
                        <!--<a href="#" class="btn btn-brown">Contact Us</a>-->
                    </div>
                    <!-- end Info -->
                </div>
                <!-- End Light version -->
            </div>
            <!-- end container -->
        </div>
        <!-- end Content Wrapper -->

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="//pkfrom.github.io/404/assets/js/modernizr.custom.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.6.0/jquery.nicescroll.min.js" type="text/javascript"></script>
        <script src="//pkfrom.github.io/404/assets/js/404.min.js" type="text/javascript"></script>
    </body>
</html>
