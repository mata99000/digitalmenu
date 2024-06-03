
<!DOCTYPE html>
<html >
<head>
    <meta charset="utf-8"/>
    <title>Mateja&#x27;s Stupendous Site</title>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="Webflow" name="generator"/>
    <link href="{{ asset('style.css') }}" rel="stylesheet" type="text/css"/>
     <link href="https://assets-global.website-files.com/66413335a09102da6c5071bb/css/matejas-stupendous-site-ea7d48.webflow.c9d63ea69.css" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="anonymous"/>
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
    <script type="text/javascript">
        WebFont.load({
            google: {
                families: ["Droid Serif:400,400italic,700,700italic","Great Vibes:400","Oswald:200,300,400,500,600,700"]
            }
        });
    </script>
    <script type="text/javascript">
        !function(o, c){
            var n = c.documentElement, t = " w-mod-";
            n.className += t + "js";
            ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch")
        }(window, document);
    </script>
    @livewireStyles

</head>
<body class="body">
    <div class="w-layout-blockcontainer container-2 w-container">
        <div data-animation="default" data-collapse="small" data-duration="400" data-easing="ease-in-quart" data-easing2="ease" role="banner" class="navbar w-nav">
            <div class="w-container">
                <a href="/" class="w-nav-brand" wire:navigate>
                    <h3 class="heading-2">Restoran Homoljski Motivi</h3>
                </a>
                <nav role="navigation" class="w-nav-menu">
                    <a href="/categories" class="w-nav-link" wire:navigate='/categories'>Meni</a>
                    <a href="#" class="w-nav-link">About</a>
                    <a href="tel:+381012852154" class="w-nav-link">Contact</a>
                    <a href="#" class="w-nav-link">Link</a>
                </nav>
                <div class="w-nav-button">
                    <div class="w-icon-nav-menu"></div>
                </div>
            </div>
        </div>
    </div>
    @yield('content')
    @livewire('order-form')
   @livewireScripts
    <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=66413335a09102da6c5071bb" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script><script src="https://assets-global.website-files.com/66413335a09102da6c5071bb/js/webflow.d035cdcb0.js" type="text/javascript"></script>
</body>
</html>