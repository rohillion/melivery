<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Melivery</title>

        <!-- Bootstrap core CSS -->
        <link href="assets/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="assets/home/main.css" rel="stylesheet">
        <link href="assets/font-awesome.min.css" rel="stylesheet">
        <link href="assets/home/animate-custom.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
        <script src="assets/jquery-2.1.1.min.js"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
            <![endif]-->

    </head>

    <body data-spy="scroll" data-offset="0" data-target="#navbar-main">
        <div id="navbar-main"> 
            <!-- Fixed navbar -->
            <div class="navbar navbar-inverse navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <!--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>-->
                        <a class="navbar-brand" href="#home"><img alt="melivery" src="/assets/logo_clock.png"/></a>
                    </div>
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="<?php echo URL::route('account.login'); ?>">Iniciar sesi&oacute;n</a></li>
                            <!--<li> <a href="#services" class="smoothScroll"> Services</a></li>
                            <li> <a href="#portfolio" class="smoothScroll"> Portfolio</a></li>
                            <li> <a href="#team" class="smoothScroll"> Team</a></li>-->
                        </ul>
                    </div>
                    <!--/.nav-collapse --> 
                </div>
            </div>
        </div>

        <!-- ==== HEADERWRAP ==== -->
        <div id="headerwrap" name="home" class="presentation-bg">
            <header class="clearfix">
                <div>
                    <img width="150" class="center-block" src="/assets/clock.png" alt="Melivery Clock"/>
                </div>
                <div>
                    <img width="300" class="center-block" src="/assets/melivery.png" alt="Melivery"/>
                </div>
                <h1>Tu local de comidas en minutos.</h1>
                <br><br>
                <a href="<?php echo URL::route('account.signup'); ?>" class="btn btn-link btn-lg">Registrate</a>
                <br><br><br>
                <p>&oacute;</p>
                <br>
                <a href="#services" class="smoothScroll btn btn-lg btn-link">Enterate m&aacute;s</a>
            </header>
        </div>
        <!-- /headerwrap -->

        <!-- ==== ABOUT ==== -->
        <!-- <div id="about" name="about">
            <div class="container">
                <div class="row white">
                    <h2 class="centered"></h2>
                    <hr>
                    <div class="col-md-6"> <img class="img-responsive" src="assets/img/about/about1.jpg" align=""> </div>
                    <div class="col-md-6">
                        <h3>Who we are</h3>
                        <p>Lorem ipsum dolor sit amet, quo meis audire placerat eu, te eos porro veniam. An everti maiorum detracto mea. Eu eos dicam voluptaria, erant bonorum albucius et per, ei sapientem accommodare est. Saepe dolorum constituam ei vel. Te sit malorum ceteros repudiandae, ne tritani adipisci vis.</p>
                        <h3>Why choose us?</h3>
                        <p>Lorem ipsum dolor sit amet, quo meis audire placerat eu, te eos porro veniam. An everti maiorum detracto mea. Eu eos dicam voluptaria, erant bonorum albucius et per, ei sapientem accommodare est. Saepe dolorum constituam ei vel.</p>
                    </div>
                </div>
        <!-- row 
    </div>
</div>--> 
        <!-- container --> 

        <!-- ==== SERVICES ==== -->
        <div id="services" name="services">
            <div class="container">
                <div class="row">
                    <h2 class="centered"></h2>
                    <br><br>
                    <!--<hr>
                    <div class="col-lg-8 col-lg-offset-2">
                        <p class="large">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
                    </div>-->
                    <div class="col-lg-4 callout"> <i class="fa fa-gears fa-3x"></i>
                        <h3>Configur&aacute; tu local.</h3>
                        <p>Pod&eacute;s configurar tus sucursales, el m&eacute;todo de entrega, la carta, tu &aacute;rea de cobertura y m&aacute;s!</p>
                    </div>
                    <div class="col-lg-4 callout"> <i class="fa fa-clock-o fa-3x"></i>
                        <h3>Pedidos en tiempo real.</h3>
                        <p>Recib&iacute; pedidos al instante en tu panel de control. Acept&aacute; o cancel&aacute; pedidos sin tener que esperar a que tus clientes se decidan por tel&eacute;fono.</p>
                    </div>
                    <div class="col-lg-4 callout"> <i class="fa fa-money fa-3x"></i>
                        <h3>Sin comisiones ni costos ocultos.</h3>
                        <p>Guard&aacute; tu dinero! Tu esfuerzo es demasiado alto como para pagar comisiones absurdas.</p>
                    </div>
                </div>
                <!-- row --> 
            </div>
        </div>
        <!-- container --> 

        <!-- ==== PORTFOLIO ==== -->
        <div id="portfolio" name="portfolio">
            <div class="container">
                <div class="row">
                    <h2 class="centered">SOMOS BETA!</h2>
                    <hr>
                    <div class="col-lg-8 col-lg-offset-2 centered">
                        <p class="large">Significa que estamos en nuestra versi&oacute;n de prueba. Por lo tanto los vendedores que se registren ahora obtienen automaticamente una cuenta GR&Aacute;TIS de por vida!.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /container --> 

        <!-- ==== TEAM MEMBERS ==== -->
        <div id="team" name="team">
            <div class="container">
                <div class="row centered">
                    <!-- <h2 class="centered">MEET OUR TEAM</h2>
                    <hr> -->
                    <div class="col-lg-8 col-lg-offset-2 centered">
                        <a href="<?php echo URL::route('account.signup'); ?>" class="btn btn-flat btn-lg btn-danger">Registrate en 5 minutos</a>
                    </div>
                </div>
            </div>
            <!-- row --> 
        </div>
        <!-- container --> 

        <!-- ==== CONTACT ==== -->
        <!-- <div id="contact" name="contact">
            <div class="container">
                <div class="row">
                    <h2 class="centered">CONTACT US</h2>
                    <hr>
                    <div class="col-md-4 centered"> <i class="fa fa-map-marker fa-2x"></i>
                        <p>321 Awesome Street<br>
                            New York, NY 17022</p>
                    </div>
                    <div class="col-md-4"> <i class="fa fa-envelope-o fa-2x"></i>
                        <p>info@companyname.com</p>
                    </div>
                    <div class="col-md-4"> <i class="fa fa-phone fa-2x"></i>
                        <p> +1 800 123 1234</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 centered">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
                        <form id="contact" method="post" class="form" role="form">
                            <div class="row">
                                <div class="col-xs-6 col-md-6 form-group">
                                    <input class="form-control" id="name" name="name" placeholder="Name" type="text" required />
                                </div>
                                <div class="col-xs-6 col-md-6 form-group">
                                    <input class="form-control" id="email" name="email" placeholder="Email" type="email" required />
                                </div>
                            </div>
                            <textarea class="form-control" id="message" name="message" placeholder="Message" rows="5"></textarea>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <button class="btn btn btn-lg" type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>-->
        <!-- container -->

        <div id="footerwrap">
            <div class="container">
                <div class="row">
                    <div class="col-md-8"> <span class="copyright">Copyright &copy; 2015 Melivery.</span> </div>
                    <div class="col-md-4">
                        <ul class="list-inline social-buttons">
                            <li><a target="_blank" href="https://twitter.com/meliveryonline"><i class="fa fa-twitter"></i></a> </li>
                            <li><a target="_blank" href="https://facebook.com/melivery"><i class="fa fa-facebook"></i></a> </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript
            ================================================== --> 
        <!-- Placed at the end of the document so the pages load faster --> 

        <script type="text/javascript" src="assets/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/home/jquery.easing.1.3.js"></script> 
        <script type="text/javascript" src="assets/home/smoothscroll.js"></script> 
        <script type="text/javascript" src="assets/home/jquery-func.js"></script>
    </body>
</html>
