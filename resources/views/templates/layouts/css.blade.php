{{-- webpage logo --}}
<link rel = "shortcut icon" href = "/favicon.ico" type = "image/x-icon" />

{{-- bootstrap stylesheet CDN --}}
<link rel = "stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel = 'stylesheet' href = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" type = 'text/css' media = 'all' />
<link rel = 'stylesheet' href = "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.css" type = 'text/css' media = 'all' />

{{-- font awesome stylesheet CDN --}}
<link rel = "stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

{{-- tailwind stylesheet --}}
<link href = '{{asset("css")}}/app.css' rel="stylesheet" type="text/css" media='all' />

{{-- google recaptcha --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://js.stripe.com/v3/"></script>

<style>
    .bootstrap-timepicker-widget {
        z-index : 2048;
        top     : inherit !important;
        left    : inherit !important;
    }
    
    .bootstrap-timepicker-widget.timepicker-orient-bottom:after {
        visibility : hidden;
    }
    
    .bootstrap-timepicker-widget.timepicker-orient-bottom:before {
        visibility : hidden;
    }
    
    .date-picker > div > div > table > thead > tr > th {
        background : transparent !important;
    }
    
    .table-condensed > thead .datepicker-switch {
        color : grey !important;
    }
    
    .table-condensed > thead .dow {
        color : royalblue !important;
    }
    
    .table-condensed > thead .next {
        color : purple !important;
    }
    
    .table-condensed > thead .prev {
        color : purple !important;
    }
    
    .datepicker.dropdown-menu {
        position                : absolute;
        top                     : 100%;
        left                    : 0;
        z-index                 : 1000;
        float                   : left;
        display                 : none;
        min-width               : 160px;
        list-style              : none;
        background-color        : #ffffff;
        border                  : 1px solid #ccc;
        -webkit-box-shadow      : 0 5px 10px rgba(0, 0, 0, 0.2);
        -moz-box-shadow         : 0 5px 10px rgba(0, 0, 0, 0.2);
        box-shadow              : 0 5px 10px rgba(0, 0, 0, 0.2);
        -webkit-background-clip : padding-box;
        -moz-background-clip    : padding;
        background-clip         : padding-box;
        color                   : #333333;
        font-family             : "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size               : 13px;
        line-height             : 1.428571429;
    }
    
    .time-picker > div > table > tbody > tr > td {
        border : 0 !important;
    }
    </style>

{{-- scripts that need to be loaded at the head --}}

{{-- Ajax --}}
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
{{-- bootstrap --}}
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    let TeamLost = {
        token: '{{csrf_token()}}',
    }
    </script>

<script>
    function scrollToContent(c) {
        var offset = c.offset(); 
        offset.left -= 20;
        offset.top -= 80;

        $('html, body').animate({
            scrollTop:  offset.top,
            scrollLeft: offset.left
        });
    }
</script>
